<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Http\Controllers\Common\Common;
use App\Models\Jctj\JctjUsers;
use App\Models\Jctj\X191028\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191028Controller extends Controller
{

    protected $itemName = 'x191028';

    const END_TIME = '2019-11-10 22:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $user = User::where(['openid' => $request->openid])->first();
        if (!$user) {
            $userDetail = JctjUsers::select('nickname', 'headimgurl')
                ->where('openid', $request->openid)
                ->first();

            $lastUser = User::create([
                'openid' => $request->openid,
                'nickname' => $userDetail['nickname'],
                'avatar' => $userDetail['headimgurl'],
                'share_f_num' => 1,
                'share_tl_num' => 1,
                'game_num' => 1,
                'subscribe_num' => 1
            ]);
            $user = User::find($lastUser->id);
        }
        //检测关注信息
        if ($user->subscribe != 1 && Helper::stopResubmit($this->itemName . ':user', $user->id, 60)) {
            $token = Helper::getJctjAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $request->openid . '&lang=zh_CN';
            $client = new \GuzzleHttp\Client();
            $resClient = $client->request('GET', $url);
            $result = json_decode($resClient->getBody()->getContents(), true);
            if (isset($result['subscribe']) && $result['subscribe'] == 1) {
                $user->subscribe = 1;
                if ($user->subscribe_num > 0) {
                    $user->subscribe_num--;
                    $user->game_num++;
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

    /*
     * 提交信息
     * */
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
            'truename' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ], [
            'truename.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'address.required' => '邮寄地址不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'truename' => $request->truename,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 成绩提交
     */
    public function score(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交成绩'], 422);
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
        if ($user['game_score'] < $request->score) {
            $user->fill([
                'game_score' => $request->score,
            ]);
        }
        $user->game_num--;
        $user->save();
        return $this->returnJson(1, "成绩更新成功", ['user' => $user]);
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
        if ($user->status == 1) {
            return response()->json(['error' => '你已中奖，无法再次获得奖品'], 422);
        }
        $redisBaseKey = 'wx:' . $this->itemName;
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('武汉天宸天街_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1

        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum',-1);
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum',1);
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
            'resultPrize' => $resultPrize
        ]);
    }


    /*
     * 分享到朋友圈
     */
    public function shareTl(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_tl_num'] > 0) {
            $user->game_num++;
            $user->share_tl_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }

    /*
     * 分享给好友
     */
    public function shareFriend(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_f_num'] > 0) {
            $user->game_num++;
            $user->share_f_num--;
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
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0, '2' => 0, '3' => 0]);
        $redis->set('wx:' . $this->itemName . ':prizeNum', 0);
        echo '应用初始化成功';
        exit();
    }
}
