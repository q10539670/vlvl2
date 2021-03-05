<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200617\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200617Controller extends Common
{

    //宜昌中心父亲节
    protected $itemName = 'x200617';

    const END_TIME = '2020-06-21 23:59:59';

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
            'name' => 'required|max:16',
            'phone' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'name.max' =>'姓名太长',
            'phone.required' => '电话号码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 成绩提交
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
            return response()->json(['error' => '活动已结束'], 422);
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
//        $ranking = -1;
//        $user->game_num--;
        if ($user->score < $request->score) {
            $user->score = $request->score;
        }
        $user->save();
//        //最好成绩排名
//        $bestUsers = User::orderBy('score', 'desc')->orderBy('created_at', 'desc')->take(60)->get()->toArray();
//        //去掉成绩为0的结果
//        $list = array_filter($bestUsers, function ($values) {
//            return $values['score'];
//        });
//        foreach ($list as $key => $value) {
//            if ($value['openid'] == $request->openid) {
//                $ranking = $key + 1;
//            }
//        }
        return Helper::json(1, '游戏成绩提交成功', ['user' => $user/*, 'ranking' => $ranking*/]);
    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $listAll = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->take(60)->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
            $list[$key]['ranking'] = $key+1;
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['user' => $user,'list' => $list, 'ranking' =>$ranking]);
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
            return Helper::json(1, '抽奖成功', [
                'user' => $user,
                'prize' => '未中奖',
                'prize_id' => 0,
            ]);
        }
        $redisBaseKey = 'wx:' . $this->itemName;
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('宜昌中心父亲节_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1

        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', -1);
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', 1);
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
            'resultPrize' => $resultPrize
        ]);
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
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' =>0]);
        $redis->set('wx:' . $this->itemName . ':prizeNum', 0);
        echo '应用初始化成功';
        exit();
    }
}
