<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201224a\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201224aController extends Common
{
    //金茂
    protected $itemName = 'x201224a';
    const END_TIME = '2021-12-07 23:59:59';

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
            'is_active_time' => time() > strtotime(self::END_TIME) ? 0 : 1
        ]);
    }
    /**
     * 提交报名
     * @param  Request  $request
     * @return JsonResponse
     */
    public function apply(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '报名时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':apply', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'mobile' => 'required',
            'comment' => 'required',
            'xm' => 'required',
            'fh' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
            'comment.required' => '留言不能为空',
            'xm.required' => '项目不能为空',
            'fh.required' => '房号不能为空'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($data);
        $user->save();
        return Helper::Json(1, '留言成功', ['user' => $user]);
    }
}
