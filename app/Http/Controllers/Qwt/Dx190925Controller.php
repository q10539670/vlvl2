<?php

namespace App\Http\Controllers\Qwt;

use App\Models\Qwt\Dx190925\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use App\Models\Qwt\Dx190925\User;
use App\Models\China;
use App\Http\Controllers\Common\Common;
use App\Http\Controllers\Common\PrizeController;


class Dx190925Controller extends Common
{

    protected $itemName = 'dx190925';
    protected $title = '全网通（9月）';

    const END_TIME = '2019-09-29 23:59:59';  //活动截止时间

    /**
     * 获取/记录 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '活动已结束，敬请期待下月福利活动'], 422);
        }
        $openid = $request->openid;
        $user = User::where(['openid' => $openid])->first();
        if (!$user) {
            $info = $this->searchQwtUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'share_code' => $this->getUniqueCode(6),
                'prize_num' => 2
            ]);
            //新增数据到表中
            User::create($userInfo);
            $user = User::where(['openid' => $openid])->first();
        }
        //检测关注信息
        if ($this->isSubscribe($user, 'Qwt')) {
            $user->subscribe = 1;
            if ($user->subscribe_num > 0) {
                $user->subscribe_num--;
                $user->prize_num++;
            }
            $user->save();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /*提交用户选择的区域信息*/
    public function setArea(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->virtual_address_code != '') {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $validator = Validator::make($request->all(), [
            'virtual_address_code' => [
                'required',
                'regex:/^[\d]{6}$/'
            ]
        ], [
            'virtual_address_code.required' => '区域编号不能为空',
            'virtual_address_code.regex' => '区域编号格式不正确',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        try {
            $user->fill([
                'virtual_address_code' => $request->virtual_address_code,
                'virtual_address_str' => str_replace('/市辖区', '', implode('/', China::getAllName($request->virtual_address_code))),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => '地区不存在'], 422);
        }
        $user->save();
        return Helper::json(1, '记录用户所选归属地成功', ['user' => $user]);
    }

    /*
     * 获取经纬度
     * 纬度 30.595222  经度 114.26432
     * 30.593407,114.27279 [gcj02] 准确     30.595222,114.26432   [wgs84] 不准确
     * */
    public function setLocation(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422); //
        }
        if ($user->location) {
            return response()->json(['error' => '不要重复提交位置'], 422);
        }
        $validator = Validator::make($request->all(),
            [
                'latitude' => 'required|min:-90|max:90',   //纬度
                'longitude' => 'required|min:-180|max:180',  //经度
            ],
            [
                'latitude.required' => '纬度不能为空',
                'latitude.min' => '纬度不能小于-90',
                'latitude.max' => '纬度不能超过90',
                'longitude.required' => '经度不能为空',
                'longitude.min' => '经度不能小于-180',
                'longitude.max' => '经度不能超过180',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->location = $request->latitude . ',' . $request->longitude;
        $user->address_code = '-2';
        $user->save();
        return Helper::json(1, '提交用户位置成功', ['user' => $user]);
    }

    /*
     * 抽卡
     */
    public function flop(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':flop', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束，敬请期待下月福利活动'], 422);
        }
        if (!$user->virtual_address_code) {
            return response()->json(['error' => '请先选择地区'], 422);
        }
        if ($user->prize_num <= 0) {
            return response()->json(['error' => '抽卡次数已用完'], 422);
        }
        $cards = User::getCardsArr($user);
        $noCards = $cards['noCards'];
        $hasCards = $cards['hasCards'];
        $targetUserId = $request->target_user_id;
        if ($targetUserId != 0 && $user->cards_num == 0) {
            $targetUser = User::find($targetUserId);
            if ($targetUser && $targetUser->id != $user->id && $targetUser->cards_num != 0 && $targetUser->real_share < 3) {
                $targetUser->real_share++;
                $targetUser->prize_num++;
                $targetUser->save();
                Help::create([
                    'target_user_id' => $targetUserId,
                    'help_user_id' => $user->id,
                    'nickname' => $user->nickname,
                    'avatar' => $user->avatar,

                ]);
            }
        }
        if ($user->cards_num == 6) {  //如果卡大于3张,会有几率出现相同的卡
            $card = $hasCards[array_rand($hasCards, 1)];
            $noCards[] = $card;
        }

        $card = $noCards[array_rand($noCards, 1)];
        $user->$card++;
        if (!in_array($card, $hasCards)) {
            $user->cards_num++;
        }
        $user->prize_num--;
        $user->save();
        return Helper::json(1, '抽卡成功', ['user' => $user, 'card' => $card]);
    }

    /*
     * 随机抽奖
     *
     * */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
//        //阻止重复提交
//        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
//            return response()->json(['error' => '不要重复提交'], 422);
//        }
//        if (time() > strtotime(self::END_TIME)) {
//            return response()->json(['error' => '活动已结束，敬请期待下月福利活动'], 422);
//        }
//        if ($user->cards_num != 6) {
//            return response()->json(['error' => '请先集齐六张卡牌'], 422);
//        }
//        if ($user->status != 0) {
//            return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
//        }
//        if (!$user->virtual_address_code) {
//            return response()->json(['error' => '请先选择地区'], 422);
//        }

        $dateStr = date('Ymd');
        $redisCountBaseKey = 'qwt:' . $this->itemName . ':prizeCount';
        $prize = new PrizeController($this->itemName,$redisCountBaseKey, $dateStr , 410103);
//        try {
            $resultPrize = $prize->fixRandomPrize(); // 固定概率抽奖
//            dd($resultPrize);
            $redisCountKey = $resultPrize['prizeCountKey'];
//        } catch (\Exception $e) {
//            \Log::channel('sswh')->info('电信2019-9月话费_抽奖', ['message' => $e->getMessage()]);
//            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
//        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['cost'], 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['cost'], -1);  //超发 中奖数回退
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['cost'] != 0) {
            $user->prize = $resultPrize['resultPrize']['cost'];
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['cost'];
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', ['user' => $user, 'prize' => $resultPrize['resultPrize']['prize_name'],'zzz'=>$resultPrize]);
    }

    /*
     * 测试接口
     * */
    public function test(Request $request)
    {
        $user = User::where(['openid' => $request->openid])->first();
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'qwt:' . $this->itemName . ':prizeCount';
        return $user->fixRandomPrize($redisCountBaseKey, $dateStr, $user->virtual_address_code);
    }

    /*
     * 我的奖品
     * */
    public function myPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422); //
        }
        $user = User::where(['openid' => $request->openid])->first();
        return Helper::json(1, '查询成功', ['user' => $user, 'prize' => $user->prize]);
    }

    /*
     * 手机号
     */
    public function phone(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422); //
        }
        $validator = Validator::make($request->all(),
            [
                'phone' => 'required',   //手机号
            ],
            [
                'phone.required' => '手机号不能为空',

            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->phone = $request->phone;
        $user->save();
        return Helper::json(1, '提交手机号成功', ['user' => $user]);
    }

    /*
     * 应用初始化
     */
//    public function appInitHandler()
//    {
//        $redis = app('redis');
//        $redis->select(12);
//        $dataArr = [
//            'test',
//            '20191114',
//            '20191115',
//            '20191116',
//        ];
//        $redis->del('qwt:' . $this->itemName . ':prizeCount');
//        foreach ($dataArr as $k => $v) {
//            $redis->hmset('qwt:' . $this->itemName . ':prizeCount:' . $v, ['0' => 0, '10' => 0, '30' => 0, '50' => 0, '100' => 0]);
//        }
//        $redis->set('qwt:' . $this->itemName . ':rankList', '[]');
//        echo '应用初始化成功';
//        exit();
//    }

    /*
     * 应用初始化
     */
//    public function appInitHandler()
//    {
//        $redis = app('redis');
//        $redis->select(12);
//        $dateArr = [
//            'test',
//            '20191114',
//            '20191115',
//            '20191116',
//        ];
//        foreach ($redis->keys('qwt:' . $this->itemName . ':prizeCount:*') as $v) {
////            if(!in_array($v,$dateArr)){
//            $redis->del($v);
////            }
//        }
//        foreach ($dateArr as $k => $v) {
//            $redis->hmset('qwt:' . $this->itemName . ':prizeCount:target:' . $v, ['0' => 0, '10' => 0, '30' => 0, '50' => 0,'100'=>0]);
//            $redis->hmset('qwt:' . $this->itemName . ':prizeCount:other:' . $v, ['0' => 0, '10' => 0, '30' => 0, '50' => 0,'100'=>0]);
//        }
//        $redis->set('qwt:' . $this->itemName . ':rankList', '[]');
//        echo '应用初始化成功';
//        exit();
//    }

    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $redis->del('qwt:' . $this->itemName . ':prizeCount');
        $redis->hmset('qwt:' . $this->itemName . ':prizeCount', ['0' => 0, '10' => 0, '30' => 0, '50' => 0,'100'=>0]);
        echo '应用初始化成功';
        exit();
    }
}
