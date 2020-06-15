<?php

namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\X191115\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191115Controller extends Common
{
    protected $itemName = 'x191029';

    const END_TIME = '2019-12-06 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到表中
            User::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
            ]);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time'=>self::END_TIME
        ]);
    }

    /*
     * 提交报名
     */
    public function signUp(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '报名已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':sign', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->name != '') {
            return response()->json(['error' => '您已经报名了,请不要重复报名'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => ['required','regex:/^1[34578]\d{9}$/'],
            'pty' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '手机号码不能为空',
            'phone.regex' => '手机号输入错误',
            'pty.required' => '节目类型不能为空',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($request->all());
        $user->save();
        return $this->returnJson(1, "报名成功", ['user' => $user]);
    }
}
