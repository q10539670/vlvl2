<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201106\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201106Controller extends Common
{
    //世纪山水·天麓城 游戏抽奖
    protected $itemName = 'x201106';

    const END_TIME = '2020-11-15 23:59:59';
    const VERSION = 'test'; //测试   formal:正式

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, ['prize_num' => 3]);
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

    /**
     * 提交信息
     * @param  Request  $request
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
        if ($user->status == 3) {
            return response()->json(['error' => '您已确认领奖,不要重复提交哦'], 422);
        }
        if ($user->status == 0 || $user->status == 2) {
            return response()->json(['error' => '您未中奖,不能确认领奖'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'phone.required' => '电话不能为空',
            'name.required' => '名字不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $redisCountKey = 'wx:'.$this->itemName.':prizeCount:'. self::VERSION;
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $user->prize_id, 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->status = 3;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
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
        if ($user->status == 1) {
            return response()->json(['error' => '您已中奖'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($request->score < 60) {
            return response()->json(['error' => '游戏成绩不足300分,不能抽奖'], 422);
        }
        if ($user->prize_num <= 0) {
            return response()->json(['error' => '今日游戏次数已用完'], 422);
        }
        $redisCountBaseKey = 'wx:'.$this->itemName.':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, self::VERSION); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('美的_游戏抽奖'.$this->itemName, ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 '.$e->getCode()], 422);
        }

        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->total_prize_num++;
        $user->prize_num--;
        $user->score = $request->input('score');
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

    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dataArr = [
            'test', 'formal'
        ];
        foreach ($redis->keys('wx:'.$this->itemName.':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:'.$this->itemName.':prizeCount:'.$v, ['0' => 0, '1' => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
