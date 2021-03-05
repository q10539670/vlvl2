<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200823\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200823Controller extends Common
{
    //金桥 报名
    protected $itemName = 'x200823';

    const END_TIME = '2021-08-03 23:59:59';

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
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);

    }

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName.':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:16',
            'phone' => 'required|regex:/^1[3456789]\d{9}$/|unique:x200823_user,phone'
        ], [
            'name.required' => '姓名不能为空',
            'name.max' => '姓名太长',
            'phone.required' => '电话不能为空',
            'phone.regex' => '电话格式错误',
            'phone.unique' => '该电话号码已预约'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($request->all());
        $user->save();
        return $this->returnJson(1, "预约成功", ['user' => $user]);
    }
}
