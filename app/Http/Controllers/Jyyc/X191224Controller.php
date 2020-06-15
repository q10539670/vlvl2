<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X191224\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191224Controller extends Common
{

        //温州商会
    protected $itemName = 'x191224';

    const OPEN_SEND_REDPACK = false;  //红包开关
    const START_TIME = '2019-12-26 17:30:00'; //开始时间
    const END_TIME = '2019-12-26 18:59:59';  //结束时间

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
            ]);
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
     *  status     1中奖  2：未中奖【红包发送失败】     3:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '活动未开始'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return $this->returnJson(1, ['error' => '活动已结束']);
        }
        if ($user->status == 1) {
            return $this->returnJson(1, ['error' => '您已中奖,无法再次抽奖']);
        }
        if ($user->prize_num <= 0) {
            return $this->returnJson(1, ['error' => '抽奖次数已用尽']);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return $this->returnJson(1, ['error' => '不要重复提交']);
        }
        $redisCountBaseKey = 'wx:' . $this->itemName;
        try {
            $resultPrize = $user->fixRandomPrize($redisCountBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('sswh')->info('天宸府红包_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        if (self::OPEN_SEND_REDPACK == true) {
            $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
            //超发 中奖数回退 此次抽奖失效
            if ($redisCount > $resultPrize['resultPrize']['limit']) {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
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
                    $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  // 红包发送失败  中奖数回退
                    $redis->hIncrBy($redisCountKey, $resultPrize['prizeConf']['failSendpack']['prize_id'], 1);  // 不中奖加1
                }
                $resultPrize['resultPrize'] = $resultPrize['prizeConf']['failSendpack'];
            }
        } else {
            $user->status = 3;
        }
        $user->prize_num--;
        $user->money = $resultPrize['resultPrize']['money']*100;
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', ['user' => $user,'resultPrize'=>$resultPrize['resultPrize'],'money'=>$resultPrize['resultPrize']['money']]);
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
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', [ '1' => 0, '2' => 0, '3' => 0, '4' => 0, '20' => 0, '21' => 0, '22' => 0]);
        echo '应用初始化成功';
        exit();
    }
}
