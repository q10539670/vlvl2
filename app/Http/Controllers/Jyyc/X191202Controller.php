<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X191202\Info;
use App\Models\Jyyc\X191202\User;
use App\Models\Jyyc\X191202\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Common\PrizeController;

class X191202Controller extends Common
{

    protected $itemName = 'x191202';

    const START_TIME = '2019-12-15 10:00:00';
    const END_TIME = '2020-01-30 22:00:00';
    const ACT = 1;  //第一轮

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJyycUser($request);

            $userInfo = $this->userInfo($request, $info, [
                'prize_num' => 1,
                'share_num' => 100000
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'start' => (time() < strtotime(self::START_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
            'act' => self::ACT
        ]);
    }

    /*
     * 验证是否为业主
     * */
    public function auth(Request $request)
    {
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '活动未开始'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':auth', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->auth != 0) {
            return response()->json(['error' => '您已经验证过身份了'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'card_id' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'card_id.required' => '身份证号不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($info = Info::where('card_id', $request->card_id)->where('name', $request->name)->where('status', 0)->first()) {
            $info->status = 1;
            $info->save();
            $user->name = $request->name;
            $user->card_id = $request->card_id;
            $user->auth = 1;
            $user->save();
            return $this->returnJson(1, "验证成功", ['user' => $user]);
        } else {
            return $this->returnJson(1, 422);//验证失败
        }
    }

    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
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
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '手机号码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone
        ]);
        $user->save();
        return $this->returnJson(1, "提交信息成功", ['user' => $user]);
    }

    /*
     * 查询奖品
     */
    public function finalPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        $prize = new User();
        $redisBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        try {
            $finalPrize = $prize->getPrize($redisBaseKey);
        } catch (\Exception $e) {
            \Log::channel('wx')->info('宜昌中心天宸府_领奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '查询失败,系统错误 ' . $e->getCode()], 422);
        }
        //dd($finalPrize);
        foreach ($finalPrize as $k => $arr) {
            $arr['surplus'] = $arr['limit'] - $arr['count'];
        }
        return Helper::json(1, '奖品查询成功', ['$finalPrize' => $finalPrize]);
    }

    /*
     * 奖品领取
     */
    public function prize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':prize', $user->id, 3)) {
            return response()->json(['error' => '不要重复领取提交'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '活动未开始'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->auth == 0 && $user->status != 1) {
            return response()->json(['error' => '你暂时还不能领取奖品,先去抽奖吧'], 422);
        }
        if ($user->prize_id != 0) {
            return Helper::json(1, 2222); //已经领取过奖品
        }
        if ($request->prize_id <= 0 || $request->prize_id > 5) {
            return response()->json(['error' => '奖品ID参数错误'], 422);
        }
        $code = Code::where(['status' => 0, 'prize_id' => $request->prize_id])->first();
        if (!$code) {
            return response()->json(['error' => '此奖品优惠券已被领完'], 422);
        }
        $prize = new User();
        $redisBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $redis = app('redis');
        $redis->select(12);
        try {
            $finalPrize = $prize->getPrize($redisBaseKey);
        } catch (\Exception $e) {
            \Log::channel('wx')->info('宜昌中心天宸府_领奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '查询失败,系统错误 ' . $e->getCode()], 422);
        }
        $redisCount = $redis->hIncrBy($redisBaseKey, $request->prize_id, 1); //奖品数+1
        if ($redisCount > $finalPrize[$request->prize_id - 1]['limit']) {
            $redis->hIncrBy($redisBaseKey, $request->prize_id, -1);  //超发 中奖数回退

            return response()->json(['error' => '领取失败,奖品已发完'], 422);
        }
        $user->prize_id = $request->prize_id;
        $user->prize = $finalPrize[$request->prize_id - 1]['prize_name'];
        $code->status = 1;
//        dd($code);
        $user->prize_code = $code->code;
        $user->status = 3;
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        $code->save();
        return Helper::json(1, '领奖成功', ['user' => $user]);
    }

    /*
     * 抽奖
     */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->prize_num <= 0) {
            return response()->json(['error' => '抽奖次数已用完'], 422);
        }
        if ($user->status == 1 || $user->status == 3) {
            return response()->json(['error' => '你已中奖，无法再次抽奖'], 422);
        }

        $redisBaseKey = 'wx:' . $this->itemName . ':prizeNum';
//        $dateStr = date('Ymd');
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('宜昌中心天宸府_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1

        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
//            dd($redisCount);
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
//            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', 1);
//            $user->prize_code = $user->getUniqueCode(6);
            $user->status = 1;
        } else {
            $user->status = 2;
        }
//        $user->prize = $resultPrize['resultPrize']['prize_name'];
//        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
//        $user->prized_at = now()->toDateTimeString();
        $user->prize_num--;
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
//            'prize_id' => $resultPrize['resultPrize']['prize_id'],
//            'resultPrize' => $resultPrize
        ]);
    }

    /**
     * 分享
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->prize_num++;
            $user->share_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeNum') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0]);
        $redis->hmset('wx:' . $this->itemName . ':prizeNum', ['0' => 0, '1' => 0]);
        echo '应用初始化成功';
        exit();
    }
}
