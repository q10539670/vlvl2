<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201229\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class X201229Controller extends Common
{
    //武汉院子 游戏抽奖
    protected $itemName = 'x201229';

    const END_TIME = '2021-01-03 23:59:59';
    const START_TIME = '2021-01-01 00:00:00';
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
            $userInfo = $this->userInfo($request, $info, ['game_num' => 1, 'share_num' => 2]);
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
     * 确认奖品
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function confirmPrize(Request $request)
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
        $redisCountKey = 'wx:' . $this->itemName . ':prizeCount:' . self::VERSION;
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $user->prize_id, 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        $prize = new User();
        if ($redisCount > $prize->getPrizeNum($user->prize_id, self::VERSION)) {
            $redis->hIncrBy($redisCountKey, $user->prize_id, -1);  //超发 中奖数回退
            $user->prize_num++;
            $user->save();
            return response()->json(['error' => '确认失败,该奖品已发完,请重新抽奖'], 422);
        }
        $user->status = 3;
        $user->code = $user->getUniqueCode(6);
        $user->code_at = now()->toDateTimeString();
        $result = $user->save();
        if (!$result) {
            $redis->hIncrBy($redisCountKey, $user->prize_id, -1);  //数据库异常 中奖数回退
            return response()->json(['error' => '数据异常,请重新提交'], 422);
        }
        return $this->returnJson(1, "确认奖品成功", ['user' => $user]);
    }

    public function game(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->game_num <= 0) {
            return response()->json(['error' => '游戏次数已用尽'],422);
        }
        $user->game_num--;
        $user->prize_num++;
        $user->save();
        return $this->returnJson(1, "成功", ['user' => $user]);
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
     * 随机抽奖
     *  status     1中奖  2：未中奖【红包发送失败】     3:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
//        if (time() < strtotime(self::START_TIME)) {
//            return response()->json(['error' => '活动未开始'], 422);
//        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->status == 3) {
            return Helper::Json(1, '您已中奖', []);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->prize_num <= 0) {
            return Helper::Json(1, '抽奖次数已用完', ['user' => $user]);
        }
//        if ($request->score < 150) {
//            $user->prize_num--;
//            $user->save();
//            return response()->json(-1, '成绩不足300分', ['user'=>$user],422);
//        }
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, self::VERSION, $request->score); // 固定概率抽奖
        } catch (Exception $e) {
            Log::channel('wx')->info('武汉院子_游戏抽奖' . $this->itemName, ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode() . $e->getMessage()], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize_num--;
//        $user->score = $request->input('score');
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
                ['0' => 0, '1' => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
