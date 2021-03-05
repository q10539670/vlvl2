<?php


namespace App\Http\Controllers\Jyyc;
//opcache_reset();

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X191216a\LikesLog;
use App\Models\Jyyc\X191216a\User;
use App\Models\Jyyc\X191216a\Code;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Common\PrizeController;

class X191216aController extends Common
{

    protected $itemName = 'x191216a';

    const START_TIME = '2019-12-18 09:00:00';
    const END_TIME = '2020-01-30 22:00:00';

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
                'prize_num' => 1
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
        ]);
    }

    public function post(Request $request)
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

    /**
     * 分享
     */
    public function share(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        $targetUserId = $request->target_user_id;
        $targetUser = User::where('id',$targetUserId)->first();
//        dd($targetUser->likes_num);
        if (!$targetUser) {
            return response()->json(['error' => '未查询到目标ID'], 422);
        }
        if (!$targetUserId) {
            return response()->json(['error' => '目标ID参数错误'], 422);
        }
        if ($targetUser->likes_num > 5) {
            $likesNum = 5;
        } else {
            $likesNum = $targetUser->likes_num;
        }
//        dd($targetUser);
        $likesUsers = LikesLog::where('target_user_id', $targetUserId)->orderBy('created_at', 'desc')->take($likesNum)->get()->toArray();
        return $this->returnJson(1, "分享成功", [
            'target_user' => $targetUser,
            'likes_users' => $likesUsers
        ]);
    }

    /**
     * 点赞
     */
    public function likes(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!Helper::stopResubmit($this->itemName . ':likes', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $targetUserId = $request->input('target_user_id');
        //判断target与help的id是否相同
        $helpUserId = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $helpUserId) {
            return response()->json(['error' => '自己不能给自己助力'], 422);
        }
        $targetUser = User::find($targetUserId);
        if (LikesLog::where(['target_user_id' => $targetUserId, 'user_id' => $user->id])
            ->WhereBetween('created_at', $this->getToday())->first()) {
            return response()->json(['error' => '你今天已经给TA助力了'], 422);
        }
        //开启事务
        DB::beginTransaction();
        try {
            $targetUser = User::find($targetUserId); //目标用户
            $targetUser->likes_num++;
            $res = $targetUser->save();
            if (!$res) {
                throw new \Exception("目标数据库错误");
            }
            $likes = LikesLog::create([
                'target_user_id' => $targetUserId,
                'user_id' => $helpUserId,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            if (!$likes) {
                throw new \Exception("点赞数据库错误");
            }
            DB::commit();
            if ($targetUser->likes_num > 5) {
                $likesNum = 5;
            } else {
                $likesNum = $targetUser->likes_num;
            }
            $likesUsers = LikesLog::where('target_user_id', $targetUserId)->orderBy('created_at', 'desc')->take($likesNum)->get()->toArray();
            return $this->returnJson(1, "点赞成功", ['target_user' => $targetUser, 'likes_users' => $likesUsers]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '点赞失败'], 422);
        }

    }

    /**
     * 抽奖
     */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '活动未开始'], 422);
        }

        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        if ($user->status == 1) {
            return response()->json(['error' => '你已中奖，无法再次获得奖品'], 422);
        }
        if ($user->num >= 3) {
            return response()->json(['error' => '今日抽奖次数已用完,明天再来吧'], 422);
        }
        if ($user->likes_num < 5 && $user->prize_num <= 0) {
            return response()->json(['error' => '没有抽奖次数'], 422);
        } elseif ($user->likes_num >= 5 && $user->prize_num <= 0) {
            $likes_num = $user->likes_num - 5;  //取余
            $user->likes_num = $likes_num;
            $user->prize_num = 1;
            $user->num++;
            $user->save();
        }
        $redisBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('宜昌中心_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
//            dd($redisCount);
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
//            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', -1);
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
//        dd($resultPrize);
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $code = Code::where(['status' => 0, 'prize_id' => $resultPrize['resultPrize']['prize_id']])->first();
            if (!$code) {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
//            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', -1);
                return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
            }
            $code->status = 1;
            $user->prize_code = $code->code;
            $user->status = 1;
            $code->save();
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prized_at = now()->toDateTimeString();
        $user->prize_num--;
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
//            'resultPrize' => $resultPrize
        ]);
    }

    public function getPrize(Request $request)
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
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0]);
        echo '应用初始化成功';
        exit();
    }
}
