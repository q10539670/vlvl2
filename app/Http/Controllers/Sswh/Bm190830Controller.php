<?php

namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Bm190830\Team as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bm190830Controller extends Controller
{
    const END_TIME = '2019-09-04 12:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function team(Request $request)
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
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /*
     * 提交报名
     */
    public function enter(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '报名已截止'], 422);
        }
        if (!$team = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'team_name' => 'required',
            'team_peoples' => ['required','regex:/^[0-9]*$/'],
            'team_introduction' => 'required',
            'phone' => ['required','regex:/^1[34578]\d{9}$/'],
            'team_age' => ['required','regex:/^(?:[0-9][0-9]?|1[01][0-9]|150)$/'],
        ], [
            'team_name.required' => '舞团名称不能为空',
            'phone.required' => '负责人电话不能为空',
            'team_peoples.required' => '舞团人数不能为空',
            'team_age.required' => '舞团成员均龄不能为空',
            'team_introduction.required' => '舞团简介不能为空',
            'team_age.regex' => '舞团成员均龄异常',
            'team_peoples.regex' => '舞团人数错误',
            'phone.regex' => '手机号输入错误',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $team->fill($request->all());
        $team->save();
        return $this->returnJson(1, "报名成功", ['team' => $team]);
    }
}
