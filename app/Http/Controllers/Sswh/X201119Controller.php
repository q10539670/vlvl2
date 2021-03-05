<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201119\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201119Controller extends Common
{
    //武汉院子
    protected $itemName = 'x201119';
    const END_TIME = '2021-11-10 23:59:59';



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
        $user->reserves;
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /**
     * 提交报名
     * @param  Request  $request
     * @return JsonResponse
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '预约时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->name) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'room' => 'required',
            'num' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
            'room.required' => '房号不能为空',
            'num.required' => '参与人数不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user->fill($request->all());
        $user->save();
        return Helper::Json(1, '报名成功', ['user' => $user]);
    }
}
