<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200528\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200528Controller extends Common
{
    //山海大观
    protected $itemName = 'x200528';

    const END_TIME = '2020-06-01 23:59:59';

    //test:测试 gold:正式
    const TYPE = 'gold';

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
                'game_num' => 3,
            ]);
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
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->game_num == 0) {
            return response()->json(['error' => '今日游戏次数已用尽'], 422);
        }
        $user->game_num--;
        $user->save();
        return $this->returnJson(1, "游戏成功", ['user' => $user]);
    }

    /*
     * 随机抽奖
     *  status     1:中奖  2：未中奖
     * */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //项目是否上线
        if (self::TYPE == 'gold') {
            if (time() > strtotime(self::END_TIME)) {
                return response()->json(['error' => '活动已结束'], 422);
            }
            //阻止重复提交
            if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
                return response()->json(['error' => '不要重复提交'], 422);
            }
            if ($user->status != 0) {
                return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
            }
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, self::TYPE); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('山海大观_抽奖', ['message' => $e->getMessage()]);
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
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
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
        $dateArr = [
            'test',
            'gold'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dateArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['1' => 0, '2' => 0, '3' => 0, '0' => 0]);
        }
        $redis->set('wx:' . $this->itemName . ':prize_num', '910');
        echo '应用初始化成功';
        exit();
    }
}
