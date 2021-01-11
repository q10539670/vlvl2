<?php


namespace App\Http\Controllers\Ctdc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Ctdc\X210111\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class X210111Controller extends Common
{
    //
    protected $itemName = 'x210111';

    const END_TIME = '2021-02-10 23:59:59';
    const VERSION = 'test'; //测试   formal:正式

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchCtdcUser($request);
            $userInfo = $this->userInfo($request, $info, ['game_num' => 3, 'share_num' => 1]);
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
     * @param Request $request
     * @return false|JsonResponse|string
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
        $user->name = $request->name;
        $user->mobile = $request->mobile;
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
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':topic', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交答题'], 422);
        }
//        if (time() < strtotime(self::START_TIME)) {
//            return response()->json(['error' => '答题活动还未开始'], 422);
//        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->game_num <= 0) {
            return response()->json(['error' => '今日答题次数已用完'], 422);
        }
        $validator = Validator::make($request->all(), [
            'topic_num' => 'required',
        ], [
            'topic_num.required' => '答题数量不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->topic_num = $request->topic_num;
        if ($request->topic_num == 8) {
            $user->prize_num++;
        }
        $user->game_num--;
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
        if ($user->prize_num <= 0) {
            return response()->json(['error' => '没有抽奖次数'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->status == 1) {
            $user->prize_num--;
            $user->topic_num = 0;
            $user->save();
            return Helper::json(2, '抽奖成功', ['user' => $user]);
        }
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, self::VERSION); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (Exception $e) {
            Log::channel('wx')->info('楚天地产_答题抽奖' . $this->itemName, ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode() . $e->getMessage()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCountKey =
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1

        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', -1);
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->topic_num = 0;
        $user->prize_num--;
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
     * 分享
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->share_num > 0) {
            $user->share_num--;
            $user->game_num++;
            $user->save();
        }
        return Helper::json(1, '分享成功', ['user' => $user]);
    }

    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dataArr = [
            'test', 'formal'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v,
                ['0' => 0, '1' => 0, 2 => 0, 3 => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
