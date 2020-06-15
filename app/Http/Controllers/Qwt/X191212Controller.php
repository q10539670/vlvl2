<?php

namespace App\Http\Controllers\Qwt;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
use App\Models\Qwt\QwtUsers as Qwt;
use App\Models\China;
use App\Models\Qwt\X191212\User;

class X191212Controller extends Controller
{

    protected $itemName = 'x191212';
    protected $title = '全网通（12月）';

    const END_TIME = '2019-12-15 23:59:59';  //活动截止时间

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
            ]);
            $user = User::find($lastUser->id);
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
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
            return response()->json(['error' => '答题活动已结束，敬请期待下月福利活动'], 422);
        }
        if ($user->status != 0) {
            return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
        }
        $user->prize_id = 0;
        $user->prize = '未中奖';
        $user->status = 2;
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', ['user' => $user,/*'resultPrize'=>$resultPrize*/]);

//        $redisCountBaseKey = 'qwt:' . $this->itemName . ':prizeCount';
//        try {
//            $resultPrize = $user->fixRandomPrize($redisCountBaseKey); // 固定概率抽奖
//            $redisCountKey = $resultPrize['prizeCountKey'];
//        } catch (\Exception $e) {
//            \Log::channel('sswh')->info('电信2019-12月答题_抽奖', ['message' => $e->getMessage()]);
//            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
//        }
////        dd($resultPrize);
//        $redis = app('redis');
//        $redis->select(12);
//        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1
//        //超发 中奖数回退 此次抽奖失效
//        if ($redisCount > $resultPrize['resultPrize']['limit']) {
//            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
//            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
//        }
//        if ($resultPrize['resultPrize']['prize_id'] != 0) {
//            $user->status = 1;
//        } else {
//            $user->status = 2;
//        }
//        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
//        $user->prize = $resultPrize['resultPrize']['prize_name'];
//        $user->prize_at = now()->toDateTimeString();
//        $user->save();
//        return Helper::json(1, '抽奖成功', ['user' => $user,/*'resultPrize'=>$resultPrize*/]);
    }

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'address.required' => '地址不能为空',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
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
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '答题活动已结束，敬请期待下月福利活动'], 422);
        }
        $redis = app('redis');
        $redis->select(12);

        foreach ($redis->keys('qwt:' . $this->itemName . ':prizeCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('qwt:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' =>0]);
        $redis->set('qwt:' . $this->itemName . ':rankList', '[]');
        echo '应用初始化成功';
        exit();
    }
}
