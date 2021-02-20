<?php

namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X210205\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X210205Controller extends Common
{

    //世纪山水答题领红包
    protected $itemName = 'x210205';
    protected $timeStr = 'gold';

    //红包开关
    const OPEN_SEND_REDPACK = false;
    const END_TIME = '2021-02-16 23:59:59';  //活动截止时间
//    const START_TIME = '2020-05-08 20:00:00';  //活动开始时间

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
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
        ]);

    }

    /**
     * 报名
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
        ], [
            'mobile.required' => '电话不能为空',
            'name.required' => '名字不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($request->all());
        $user->save();
        return Helper::json(1, "提交成功", ['user' => $user]);
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
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->prize_id != 0) {
            return Helper::Json(2,'您已中奖');
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
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->prize_id != 0) {
            return Helper::Json(2,'您已中奖,无法再次抽奖');
        }
        if ($user->topic_num < 3) {
            return Helper::json(1, '很遗憾,您答对题目少于3题');
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        try {
            $resultPrize = $user->fixRandomPrize($redisCountBaseKey, $this->timeStr); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('sswh')->info('中国中铁·世纪山水红包_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $user->status = 1;
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            return response()->json(['error' => '红包已发完'], 422);
        }

        if ($resultPrize['resultPrize']['money'] != 0 || in_array($resultPrize['resultPrize']['prize_id'], [4,5,6])) {
            $resultRedpack = $user->sendRedpack($resultPrize['resultPrize']['money'], $user->openid, $user->id, self::OPEN_SEND_REDPACK);
            $user->redpack_return_msg = $resultRedpack['return_msg'];
            $user->status = $resultRedpack['result_code'] == 'SUCCESS' ? 1 : 2;
            $user->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
            //红包发送失败 中奖数回退 并转未中奖
            if ($resultRedpack['result_code'] != 'SUCCESS') {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  // 红包发送失败  中奖数回退
                $redis->hIncrBy($redisCountKey, $resultPrize['prizeConf']['failSendpack']['prize_id'], 1);  // 不中奖加1
                $randomCode = mt_rand(0, 2);
                $resultPrize['resultPrize'] = $resultPrize['finalConf'][$randomCode];
            }
        }
        $user->money = $resultPrize['resultPrize']['money'] * 100;
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        $prizeNum = $redis->get('wx:' . $this->itemName . ':prize_num');
        return Helper::json(1, '抽奖成功', ['user' => $user, 'resultPrize' => $resultPrize, 'prize_num' => $prizeNum]);
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
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['1' => 0, '2' => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, '20' => 0, '21' => 0, '22' => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
