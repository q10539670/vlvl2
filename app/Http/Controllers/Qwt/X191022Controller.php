<?php

namespace App\Http\Controllers\Qwt;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use App\Models\Qwt\QwtUsers as Qwt;
use App\Models\China;
use App\Models\Qwt\X191022\User;

class X191022Controller extends Controller
{

    protected $itemName = 'x191022';
    protected $title = '全网通（10月）';

    const OPEN_SEND_REDPACK = true;
    const END_TIME = '2019-10-27 23:59:59';  //活动截止时间

    /**
     * 获取/记录 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request)
    {
        $user = User::where(['openid' => $request->openid])->first();
        if (!$user) {
            $userDetail = Qwt::select('nickname', 'headimgurl')
                ->where('openid', $request->openid)
                ->first();

            $lastUser = User::create([
                'openid' => $request->openid,
                'nickname' => $userDetail['nickname'],
                'avatar' => $userDetail['headimgurl'],
                'share_code' => User::getUniqueCode(6),
                'share_num' => 2,
                'game_num' => 2,
                'subscribe_num' => 1
            ]);
            $user = User::find($lastUser->id);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束，敬请期待下月福利活动'], 422);
        }
        //检测关注信息
        if ($user->subscribe != 1 && Helper::stopResubmit($this->itemName . ':userInfo', $user->id, 60)) {
            $token = Helper::getQwtAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $request->openid . '&lang=zh_CN';
            $client = new \GuzzleHttp\Client();
            $resClient = $client->request('GET', $url);
            $result = json_decode($resClient->getBody()->getContents(), true);
            if (isset($result['subscribe']) && $result['subscribe'] == 1) {
                $user->subscribe = 1;
                if ($user->subscribe_num > 0) {
                    $user->subscribe_num--;
                    $user->game_num++;
                }
                $user->save();
            }

        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time'=>self::END_TIME
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
     * 游戏成绩
     */
    public function score(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束，敬请期待下月福利活动'], 422);
        }
        if (!$user->virtual_address_code) {
            return response()->json(['error' => '请先选择地区'], 422);
        }
        if ($user->game_num <= 0) {
            return response()->json(['error' => '今日游戏次数已用完'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required', 'regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        //如果是最好游戏成绩,则写入数据库并计算排名
        $ranking = -1;
        $user->game_score = $request->score;
        $user->game_num--;
        if ($user->game_score > $request->score || $user->best_score == 0) {
            $user->best_score = $request->score;
        }
        $user->save();
        //最好成绩排名
        $bestUsers = User::orderBy('best_score', 'asc')->orderBy('created_at', 'desc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($bestUsers, function ($values) {
            return $values['game_score'];
        });
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        $scale = -1;
        if ($request->score <= 30) {
            $count = User::all()->count();
            $nowUsers = User::orderBy('game_score', 'asc')->orderBy('created_at', 'desc')->get()->toArray();
            //去掉成绩为0的结果
            $list = array_filter($nowUsers, function ($values) {
                return $values['game_score'];
            });
            foreach ($list as $key => $value) {
                if ($value['openid'] == $request->openid) {
                    $rankingNow = $key + 1;
                }
            }
            $scale = ceil(($count - ($rankingNow - 1)) / $count * 100) . '%';
        }
        return Helper::json(1, '游戏成绩提交成功', ['user' => $user, 'ranking' => $ranking, 'scale' => $scale]);
    }

    /*
     * 随机抽奖
     *  status     1中奖  2：未中奖【红包发送失败】     3:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '红包答题活动已结束，敬请期待下月福利活动'], 422);
        }
        if ($user->status != 0) {
            return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
        }
        if (!$user->virtual_address_code) {
            return response()->json(['error' => '请先选择地区'], 422);
        }
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'qwt:' . $this->itemName . ':prizeCount';
        try {
            $resultPrize = $user->fixRandomPrize($redisCountBaseKey, $dateStr, $user->virtual_address_code); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('sswh')->info('电信2019-10月红包_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        if (self::OPEN_SEND_REDPACK == true) {
            $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['money'], 1); //中奖数累计+1
            //超发 中奖数回退 此次抽奖失效
            if ($redisCount > $resultPrize['resultPrize']['limit']) {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['money'], -1);  //超发 中奖数回退
                return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
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
                    $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['money'], -1);  // 红包发送失败  中奖数回退
                    $redis->hIncrBy($redisCountKey, $resultPrize['prizeConf']['failSendpack']['money'], 1);  // 不中奖加1
                }
                $resultPrize['resultPrize'] = $resultPrize['prizeConf']['failSendpack'];
            }
        } else {
            $user->status = 3;
        }
        $user->money = $resultPrize['resultPrize']['money'];
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', ['user' => $user/*,'resultPrize'=>$resultPrize*/]);
    }

    /*
     * 分享
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->game_num++;
            $user->share_num--;
            $user->last_share_at = now()->toDateTimeString();
            $user->save();
        }
        return $this->returnJson(1, "分享成功");
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
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dateArr = [
            'test',
            '20191025',
            '20191026',
            '20191027',
        ];
        foreach ($redis->keys('qwt:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dateArr as $k => $v) {
            $redis->hmset('qwt:' . $this->itemName . ':prizeCount:hubei:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0]);
            $redis->hmset('qwt:' . $this->itemName . ':prizeCount:other:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0]);
        }
        $redis->set('qwt:' . $this->itemName . ':rankList', '[]');
        echo '应用初始化成功';
        exit();
    }
}
