<?php

namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200507\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200507Controller extends Common
{

    //中国中铁·金桥璟园答题领红包
    protected $itemName = 'x200507';

    //红包开关
    const OPEN_SEND_REDPACK = false;
    const END_TIME = '2020-05-09 20:10:59';  //活动截止时间
    const START_TIME = '2020-05-08 20:00:00';  //活动开始时间

    /**
     * 获取/记录 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $redis = app('redis');
        $redis->select(12);
        $prizeNum = $redis->get('wx:' . $this->itemName . ':prize_num');
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'prize_num' => $prizeNum,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
            'start_time' => self::START_TIME
        ]);

    }

    /*
     * 答题
     */
    public function topic(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':topic', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交答题'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '答题活动还未开始'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $prizeNum = $redis->get('wx:' . $this->itemName . ':prize_num');
        if ($prizeNum == 0) {
            return response()->json(['error' => '红包已被抢完啦'], 422);
        }
        if ($user->status != 0) {
            return response()->json(['error' => '你已答题，无法再次答题'], 422);
        }

        $validator = Validator::make($request->all(), [
            'topic_num' => 'required',
        ], [
            'topic_num.required' => '答题数目不能为空',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->topic_num = $request->topic_num;
        $user->status = 1;
        $user->save();
        return $this->returnJson(1, "答题成功", ['user' => $user]);
    }

    /*
     * 随机抽奖
     *  status     1中奖  2：未中奖【红包发送失败】     3:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }

        $redis = app('redis');
        $redis->select(12);
        $prizeNum = $redis->get('wx:' . $this->itemName . ':prize_num');
        if ($prizeNum == 0) {
            return response()->json(['error' => '红包已被抢完啦'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '答题活动还未开始'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->money != 0) {
            return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
        }
        if ($user->topic_num < 3) {
            return Helper::json(1, '很遗憾,您答对题目少于3题');
        }
        $redis = app('redis');
        $redis->select(12);
        $timeStr = date('Y-m-d H:i:s');
        if ($timeStr > '2020-05-08 20:00:00') {
            $timeStr = 'gold';
        } else {
            $timeStr = 'test';
        }
//        $timeStr = 'gold';
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        try {
            $resultPrize = $user->fixRandomPrize($redisCountBaseKey, $timeStr); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('sswh')->info('中国中铁·金桥璟园红包_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }

        if (self::OPEN_SEND_REDPACK == true) {
            $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
            $redisNum = $redis->IncrBy('wx:' . $this->itemName . ':prize_num', -1); //总中奖数累计-1
            //超发 中奖数回退 此次抽奖失效
            if ($redisCount > $resultPrize['resultPrize']['limit'] || $redisNum < 0) {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
                $redis->IncrBy('wx:' . $this->itemName . ':prize_num', 1); //总中奖数回退
                return response()->json(['error' => '红包已发完'], 422);
            }
        }
        if ($resultPrize['resultPrize']['money'] != 0) {
            $resultRedpack = $user->sendRedpack($resultPrize['resultPrize']['money'], $user->openid, $user->id, self::OPEN_SEND_REDPACK);
            $user->redpack_return_msg = $resultRedpack['return_msg'];
            $user->status = $resultRedpack['result_code'] == 'SUCCESS' ? 1 : 2;
            $user->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
            //红包发送失败 中奖数回退 并转未中奖
            if ($resultRedpack['result_code'] != 'SUCCESS') {
                if (self::OPEN_SEND_REDPACK == true) {
                    $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  // 红包发送失败  中奖数回退
                    $redis->hIncrBy($redisCountKey, $resultPrize['prizeConf']['failSendpack']['prize_id'], 1);  // 不中奖加1
                    $redis->IncrBy('wx:' . $this->itemName . ':prize_num', 1); //总中奖数回退
                }
                $resultPrize['resultPrize'] = $resultPrize['prizeConf']['failSendpack'];
            }
        } else {
            $user->status = 3;
        }
        $user->money = $resultPrize['resultPrize']['money']*100;
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        $prizeNum = $redis->get('wx:' . $this->itemName . ':prize_num');
        return Helper::json(1, '抽奖成功', ['user' => $user/*,'resultPrize'=>$resultPrize*/,'prize_num' => $prizeNum]);
    }


    /*
     * 测试接口
     * */
    public function test(Request $request)
    {
        $user = User::where(['openid' => $request->openid])->first();
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        return $user->fixRandomPrize($redisCountBaseKey, $dateStr, $user->virtual_address_code);
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
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:'. $v, ['1' => 0, '2' => 0,'20' => 0, '21' => 0, '22' => 0]);
        }
        $redis->set('wx:' . $this->itemName . ':prize_num', '910');
        echo '应用初始化成功';
        exit();
    }
}
