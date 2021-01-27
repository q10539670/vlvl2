<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X210127\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X210127Controller extends Common
{
    //保利
    protected $itemName = 'x210127';
    const END_TIME = '2021-01-31 23:59:59';


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
     * 提交成绩
     * @param  Request  $request
     * @return JsonResponse
     */
    public function score(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'score' => 'required',
        ], [
            'score.required' => '姓名不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($user->score < 100 && $request->score >= 100) {
            $user->score = $request->score;
            $user->prize = '天麓城30元年货兑换券';
            $user->prized_at = now()->toDateTimeString();
        }
        if($user->score < $request->score) {
            $user->score = $request->score;
        }
        $user->num++;
        $user->save();
        return Helper::Json(1, '成绩提交成功', ['user' => $user]);
    }
}
