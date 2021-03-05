<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Helpers\SendSms;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200928\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200928Controller extends Common
{

    //宜昌中心
    protected $itemName = 'x200928';

    const OPEN_SEND_REDPACK = true;  //红包开关
    const END_TIME = '2020-10-05 23:59:59';  //结束时间
    const START_TIME = '2020-09-30 08:00:00';  //开始时间

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJyycUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /*
     * 提交信息
     * */
    public function post(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return $this->returnJson(-1, "活动未开始");
        }
        if (!Helper::stopResubmit($this->itemName.':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }


    /*
     * 随机抽奖
     *  status     10:中奖  2:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if (time() < strtotime(self::START_TIME)) {
            return $this->returnJson(-1, "活动未开始");
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->status != 0) {
            return response()->json(['error' => '您已参加过活动了'], 422);
//            return $this->returnJson(1, ['error' => '您已中奖,无法再次抽奖']);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':randomPrize', $user->id, 3)) {
            return $this->returnJson(1, ['error' => '不要重复提交']);
        }
        $dateStr = date('Ymd');
//        $dateStr = 'test';
        $redisCountBaseKey = 'wx:'.$this->itemName.':prizeCount:'.$dateStr;
        try {
            $resultPrize = $user->fixRandomPrize($redisCountBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('sswh')->info('宜昌中心红包_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 '.$e->getMessage()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['money'] != 0) {
            $user->money = $resultPrize['resultPrize']['money'] * 100;
            $user->status = 10;
            $user->prized_at = now()->toDateTimeString();
        } else {
            $user->status = 2;
        }
        $user->save();
        return Helper::json(1, '抽奖成功',
            ['user' => $user, 'resultPrize' => $resultPrize['resultPrize'], 'allPrize' => $resultPrize]);
    }

    /**
     * 发送短信
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return $this->returnJson(-1, "活动未开始");
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
//            return $this->returnJson(1, ['error' => '活动已结束']);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':sendSms', $user->id, 30)) {
            return $this->returnJson(1, ['error' => '不要重复发送']);
        }
        $vCode = mt_rand(100, 999);
        $phone = $user->phone;
        $content = '【宜昌中心·天宸府】您好，您的兑奖码为：'.$vCode.'，兑奖码当日有效，请您尽快兑换，感谢您对宜昌中心·天宸府的关注。';
        $sms = new SendSms();
        $result = $sms->send($phone, $content);
        $user->v_code = $vCode;
        $result = json_decode($result, true);
        if (!$result['success']) {
            return response()->json(['error' => '验证码发送失败:'.$result['r']], 422);
        }
        $user->save();
        return Helper::Json(1, '验证码发送成功', ['user' => $user]);
    }

    /**
     * 发送红包
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRedPack(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return $this->returnJson(-1, "活动未开始");
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }

        if ($user->status != 10) {
            return response()->json(['error' => '兑换失败,您未中奖或者奖品已失效'], 422);
        }
        $prizeDate = substr($user->prized_at, 0, 10);
        if ($prizeDate != date('Y-m-d')) {
            $user->status = 12;
            $user->save();
            return response()->json(['error' => '兑换失败,您的奖品已过期'], 422);
        }
        if ($user->v_code != $request->v_code || $user->v_code == 0) {
            return response()->json(['error' => '验证码错误'], 422);
        }
        $resultRedpack = $user->sendRedpack($user->money, $user->openid, $user->id, self::OPEN_SEND_REDPACK);
        $user->redpack_return_msg = $resultRedpack['return_msg'];
        $user->status = $resultRedpack['result_code'] == 'SUCCESS' ? 11 : 3;
        $user->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
        $user->save();
        if ($user->status == 3) {
            return response()->json(['error' => '红包发送失败'], 422);
        }
        return Helper::Json(1, '红包发送成功', ['user' => $user]);

    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dateArr = ['test', '20200930', '20201001', '20201002', '20201003', '20201004', '20201005'];
        foreach ($redis->keys('wx:'.$this->itemName.':prizeCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dateArr as $k => $v) {
            $redis->hmset('wx:'.$this->itemName.':prizeCount:'.$v,
                ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '20' => 0, '21' => 0, '22' => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
