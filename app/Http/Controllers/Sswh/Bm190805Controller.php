<?php

namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Bm190805\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bm190805Controller extends Controller
{
    const END_TIME = '2019-08-12 23:59:59';

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
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'truename' => 'required',
            'phone' => 'required',
            'sex' => ['required','regex:/^[0,1]*$/'],
            'age' => ['required','regex:/^(?:[0-9][0-9]?|1[01][0-9]|150)$/'],
            'cooking_age' => ['required','regex:/^(?:[0-9][0-9]?|1[01][0-9]|150)$/'],
            'specialty' => 'required'
        ], [
            'truename.required' => '姓名不能为空',
            'phone.required' => '电话不能为空',
            'sex.required' => '性别不能为空',
            'age.required' => '年龄不能为空',
            'cooking_age.required' => '厨龄不能为空',
            'specialty.required' => '拿手菜不能为空',
            'sex.regex' => '性别异常',
            'age.regex' => '年龄异常',
            'coking_age.regex' => '厨龄异常'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($request->all());
        $user->save();
        return $this->returnJson(1, "报名成功", ['user' => $user]);
    }
}
