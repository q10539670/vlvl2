<?php


namespace App\Http\Controllers\Sswh;
use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191220\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191220Controller extends Common
{
    //龙湖抽奖
    protected $itemName = 'x191220';

    const END_TIME = '2020-01-01 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info,[
                'ip'=>$request->ip(),
                'prize_num'=>3,
                'address_code'=>0
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1
        ]);

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
     * 提交信息
     * */
    public function post(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
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
        ], [
            'truename.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'truename' => $request->truename,
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
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交成绩'], 422);
        }
        if ($user->prize_num <= 0 ) {
            return response()->json(['error' => '今日挑战次数已用完'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required','regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($user['score'] < $request->score) {
            $user->fill([
                'score' => $request->score,
            ]);
        }
        $user->prize_num--;
        $user->save();
        return $this->returnJson(1, "成绩更新成功", ['user'=>$user]);
    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $listAll = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $listAll = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($listAll as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        $listAll = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->take(10)->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
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
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, $dateStr, $user->address_code); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('长沙洋湖天街_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
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
            $user->status = 1;
            $user->verification_code=User::getUniqueCode(6);
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
            'resultPrize'=>$resultPrize
        ]);
    }
    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dataArr = [
            'test','20191224', '20191225', '20191226', '20191227', '20191228','20191229', '20191230', '20191231', '20200101'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0,'5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0,'10'=>0,'11'=>0]);
        }
        $redis->set('wx:' . $this->itemName . ':rankList', '[]');
        echo '应用初始化成功';
        exit();
    }
}
