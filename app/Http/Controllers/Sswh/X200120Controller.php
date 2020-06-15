<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200120\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200120Controller extends Common
{
    //长沙美的
    protected $itemName = 'x200120';

    const END_TIME = '2020-01-31 23:59:59';


    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            $userInfo = [
                'images' => '',
                'openid' => $openid
            ];
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }


    /*
     * 上传照片
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'images' => 'required|image|max:' . (1024 * 6),
        ], [
            'images.required' => '图片不能为空',
            'images.image' => '只能上传图片类型',
            'images.max' => '图片大小不能超过6M',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('images')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->images->store('/wx_items/x200120', $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        if ($user->upload_num == 0) {
            $user->images = $path;
        } else {
            $user->images .= '|'.$path;
        }
        $user->upload_num++;
        $user->save();
        return $this->returnJson(1, "上传成功", ['user' => $user]);
    }
}
