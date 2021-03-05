<?php

namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X210114\User;
use App\Models\Sswh\X210114\Works;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X210114Controller extends Common
{
    //金茂社群
    protected $itemName = 'x210114';
    const END_TIME = '2021-02-17 23:59:59';

    protected $prod = 'wx';   //cdnn

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /**
     * 所有作品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function works(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $works = Works::where('status', 1)->get();
        foreach ($works as $work) {
            $work->user;
        }
        if (env('APP_ENV') == 'local') {
            $prefix = asset('/storage/') . '/';
        } else {
            $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        }
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'works' => $works
        ]);
    }

    public function detail(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!$detail = Works::where('id', $request->id)->where('status', 1)->first()) {
            return response()->json(['error' => '作品ID错误'], 422);
        }
        return $this->returnJson(1, "查询成功", ['detail' => $detail]);
    }

    /*
     * 上传照片
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'auth' => 'required',
            'comment' => 'required',
            'image' => 'required|image|max:' . (1024 * 6),
        ], [
            'title.required' => '标题不能为空',
            'auth.required' => '作者不能为空',
            'comment.required' => '正文不能为空',
            'image.required' => '图片不能为空',
            'image.image' => '只能上传图片类型',
            'image.max' => '图片大小不能超过6M'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/x210114', $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        Works::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'auth' => $request->auth,
            'comment' => preg_replace("/\n|\r\n/i","<br>",$request->comment),
            'image' => $path,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ]);
        $user->submit_num++;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user, 'prefix' => 'https://' . $this->prod . '.sanshanwenhua.com/statics/']);
    }
}
