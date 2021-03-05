<?php

namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200424\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200424Controller extends Common
{

    //大桥·厨艺答题
    protected $itemName = 'x200424';

    const END_TIME = '2020-05-03 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, ['topic_num' => 3]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1
        ]);

    }

    /*
     * 提交信息
     * */
    public function post(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'address.required' => '邮寄地址不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 答题
     */
    public function topic(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->topic_num <= 0) {
            return response()->json(['error' => '今天答题次数已用完'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交答题'], 422);
        }
        $user->topic_num--;
        $user->save();
        return $this->returnJson(1, "答题成功", ['user' => $user]);
    }

    /*
     * 抽奖
     */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->prize_id != 0) {
            return response()->json(['error' => '您已经中奖了'], 422);
        }
        if ($user->status == 1) {
            return response()->json(['error' => '今天抽奖机会已用完'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, $dateStr); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('大桥厨艺答题_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        $user->status = 1;
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
            'resultPrize' => $resultPrize
        ]);
    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dataArr = [
            'test',
            '20200430',
            '20200501',
            '20200502',
            '20200503'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:'.$v, ['0' => 0, '1' => 0]);
        }

        echo '应用初始化成功';
        exit();
    }
}
