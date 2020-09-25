<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200901\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200901Controller extends Common
{

    //宜昌中心·臻享福利
    protected $itemName = 'x200901';

    protected $prizeDate = ['2020-09-04', '2020-09-11', '2020-09-18', '2020-09-25'];

    protected $prize = [
        0 => ['prize_id' => 1, 'prize' => '摩飞早餐机'],
        1 => ['prize_id' => 5, 'prize' => '榨汁机'],
        2 => ['prize_id' => 4, 'prize' => '吸尘器'],
    ];

    const END_TIME = '2020-10-01 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJyycUser($request);
            $userInfo = $this->userInfo($request, $info, ['prize_num' => 1, 'share_num' => 2]);
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

        if (!Helper::stopResubmit($this->itemName.':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $dateStr = date('Y-m-d');
        if (!in_array($dateStr, $this->prizeDate)) {  //判断是否为周五
            return response()->json(['error' => '抽奖还未开始,请周五再来'], 422);
        }
        if (date('H') < 20) {  //判断当前时间小于20点
            return response()->json(['error' => '抽奖还未开始,请20:00再来'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:x200901_user,phone'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话不能为空',
            'phone.unique' => '电话号码已被注册',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($request->all());
        $user->save();
        return Helper::Json(1, "提交成功", ['user' => $user]);
    }

    public function prize(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName.':prize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->prize_num <= 0) {
            return response()->json(['error' => '您本周已抽过奖了'], 422);
        }
        if ($user->status == 1) {
            return response()->json(['error' => '您已经中奖了'], 422);
        }
        $dateStr = date('Y-m-d');

        if (!in_array($dateStr, $this->prizeDate)) {  //判断是否为周五
            return response()->json(['error' => '抽奖还未开始,请周五再来'], 422);
        }
        if (date('H') < 20) {  //判断当前时间小于20点
            return response()->json(['error' => '抽奖还未开始,请20:00再来'], 422);
        }
        $phones = [
            '13310569059' => 0,
            '15572771222' => 1,
            '13207206666' => 2,
        ];
        if (!in_array($user->phone, array_keys($phones))) {
            $redisBaseKey = 'wx:'.$this->itemName.':prizeCount';
            $prize = new User();
            try {
                $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
                $redisCountKey = $resultPrize['prizeCountKey'];
            } catch (\Exception $e) {
                \Log::channel('wx')->info('宜昌中心生活服务启示录_抽奖', ['message' => $e->getMessage()]);
                return response()->json(['error' => '抽奖失败,系统错误 '.$e->getCode(), 'message' => $e->getMessage()], 422);
            }
            $redis = app('redis');
            $redis->select(12);
            $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
            //超发 中奖数回退 此次抽奖失效
            if ($redisCount > $resultPrize['resultPrize']['limit']) {
                $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
                $redis->incrBy('wx:'.$this->itemName.':prizeNum', -1);
                return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
            }
            if ($resultPrize['resultPrize']['prize_id'] != 0) {
                $redis->incrBy('wx:'.$this->itemName.':prizeNum', 1);
                $user->status = 1;
                $user->prized_at = now()->toDateTimeString();
            } else {
                $user->status = 2;
            }
            $user->prize = $resultPrize['resultPrize']['prize_name'];
            $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        } else {
            $user->status = 1;
            $user->prize = $this->prize[$phones[$user->phone]]['prize'];
            $user->prize_id = $this->prize[$phones[$user->phone]]['prize_id'];
            $user->prized_at = now()->toDateTimeString();
        }
        $user->prize_num--;
        $user->save();
        return Helper::Json(1, '抽奖成功', ['user' => $user]);
    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dateArr = ['test', '1', '2', '3', '4'];
        foreach ($redis->keys('wx:'.$this->itemName.':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dateArr as $k => $v) {
            $redis->hmset('wx:'.$this->itemName.':prizeCount:'.$v,
                [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 97 => 0, 98 => 0, 99 => 0]);
        }
        echo '应用初始化成功';
        exit();
    }

    public function share(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->share_num > 0) {
            $user->share_num--;
            $user->prize_num++;
            $user->save();
        }
        return Helper::Json(1, '分享成功', ['user' => $user]);
    }
}