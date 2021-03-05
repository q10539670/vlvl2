<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200331\User;
use App\Models\Jyyc\X200331\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200331Controller extends Common
{

    //老带新--新用户
    protected $itemName = 'x200331';

    const OPEN_SEND_REDPACK = true;  //红包开关
//    const START_TIME = '2020-01-11 10:25:00'; //开始时间
    const END_TIME = '2020-05-12 17:00:00';  //结束时间

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
    public function randomPrize(Request $request)
    {
//        if (time() < strtotime(self::START_TIME)) {
//            return response()->json(['error' => '活动未开始'], 422);
//        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'code' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'code.required' => '验证码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $code = Code::where('code',$request->code)->first();
        if (!$code) {
            return $this->returnJson(1, "验证码错误");
        }
        if ($code->status == 1) {
            return $this->returnJson(1, "验证码已使用");
        }
        $redisCountBaseKey = 'wx:' . $this->itemName;
        try {
            $resultPrize = $code->fixRandomPrize($redisCountBaseKey); // 固定概率抽奖
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
            $resultRedpack = $code->sendRedpack($resultPrize['resultPrize']['money'], $user->openid, $user->id, self::OPEN_SEND_REDPACK);
            $code->redpack_return_msg = $resultRedpack['return_msg'];
            $code->status = $resultRedpack['result_code'] == 'SUCCESS' ? 1 : 0;
            $user->custom_num++;
            $code->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
            //红包发送失败 中奖数回退 并转未中奖
            if ($resultRedpack['result_code'] != 'SUCCESS') {
                if (self::OPEN_SEND_REDPACK == true) {
                    $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  // 红包发送失败  中奖数回退
                    $redis->hIncrBy($redisCountKey, $resultPrize['prizeConf']['failSendpack']['prize_id'], 1);  // 不中奖加1
                }
                $user->custom_num--;
                $resultPrize['resultPrize'] = $resultPrize['prizeConf']['failSendpack'];
            }
        } else {
            $code->status = 0;
        }
        $code->name = $request->name;
        $code->phone = $request->phone;
        $code->user_id = $user->id;
        $code->money = $resultPrize['resultPrize']['money'] * 100;
        $code->prize_at = now()->toDateTimeString();
        $code->save();
        $user->money += $code->money;
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'code' =>$code,
            'resultPrize' => $resultPrize['resultPrize'],
            'money' => $resultPrize['resultPrize']['money']]);
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
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '20' => 0, '21' => 0, '22' => 0]);
        echo '应用初始化成功';
        exit();
    }

    public function getUniqueCode(Request $request)
    {

        $code = new Code();
        for ($i=0;$i<$request->num;$i++) {
            $code->getUniqueCode(8);
        }
        return Helper::json(1, '验证码生成成功');
    }

}
