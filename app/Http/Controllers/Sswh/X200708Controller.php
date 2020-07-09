<?php


namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200708\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200708Controller extends Common
{
    //兰州·中海铂悦府
    protected $itemName = 'x200708';

    const END_TIME = '2020-07-11 14:30:00';

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
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'phone.required' => '电话不能为空',
            'name.required' => '名字不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }
}
