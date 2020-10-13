<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201013a\User;
use App\Models\Sswh\X201013a\Reserve;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201013aController extends Common
{
    //百事可乐·武汉百事--预约盖念店
    protected $itemName = 'x201013a';
    const END_TIME = '2020-10-31 23:59:59';


    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'num_total' => 3,
                'num_today' => 1
            ]);
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
     * 提交预约
     * @param  Request  $request
     * @return JsonResponse
     */
    public function reserve(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '预约时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交预约'], 422);
        }
        if ($user->num_total <= 0) {
            return response()->json(['error' => '您已预约3次,预约次数已用完'], 422);
        }
        if ($user->num_today <= 0) {
            return response()->json(['error' => '您今天已预约过了,请明天再来'], 422);
        }
        $date = $request->reserve_date;
        $time = $request->reserve_time;
        $redisCountKey = 'wx:'.$this->itemName.':reserveCount:'.$date;
        $redis = app('redis');
        $redis->select(12);
        $count = $redis->hGet($redisCountKey,$time);
        if ($count>=80) {
            return response()->json(['error' => '预约失败,该时段预约人数已满'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'num' => 'required',
            'reserve_date' => 'required',
            'reserve_time' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话不能为空',
            'num.required' => '预约人数不能为空',
            'reserve_date.required' => '预约日期不能为空',
            'reserve_time.required' => '预约时间不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $redisCount = $redis->hIncrBy($redisCountKey, $time, 1); //预约数累计+1
        if ($redisCount > 80) {
            $redis->hIncrBy($redisCountKey, $time, -1);  //超发 中奖数回退
            return response()->json(['error' => '预约失败,请重新预约'], 422);
        }
        RESERVE::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'num' => $request->num,
            'reserve_date' =>$request->reserve_date,
            'reserve_time' => $request->reserve_time
        ]);
        $user->num_total--;
        $user->num_today--;
        $user->save();
        $user->reserves;
        return Helper::Json(1, '预约提交成功', ['user' => $user]);
    }


    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        foreach ($redis->keys('wx:'.$this->itemName.':reserveCount:*') as $v) {
            $redis->del($v);
        }
        echo '应用初始化成功';
        exit();
    }
}
