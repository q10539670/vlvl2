<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200914\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200914Controller extends Common
{
    //金茂报名
    protected $itemName = 'x200914';

    protected $itemCount = [1 => 75, 2 => 25, 3 => 50, 4 => 25];

    const END_TIME = '2020-09-24 18:00:00';

    const RELEASE = 'test'; //test:测试 formal:正式

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);

    }

    /**
     * 报名
     * @param  Request  $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function signUp(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->item_id != 0) {
            return response()->json(['error' => '您已经报过名了'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'item_id' => 'required',
            'item' => 'required',
            'room_no' => 'required'
        ], [
            'phone.required' => '电话不能为空',
            'name.required' => '名字不能为空',
            'item_id.required' => '项目ID不能为空',
            'item.required' => '项目名不能为空',
            'room_no.required' => '房号不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $itemId = $request->input('item_id');
        $redisCountBaseKey = 'wx:'.$this->itemName.':signUpCount';
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hget($redisCountBaseKey, $itemId);
        if ($redisCount >= $this->itemCount[$itemId]) {
            return response()->json(['error' => '该项目报名人数已满'], 422);
        }
        $request['sign_up_at'] = now()->toDateTimeString();
        $user->fill($request->all());
        $user->save();
        $redisCount = $redis->hIncrBy($redisCountBaseKey, $itemId, 1); //报名数累计+1
        return $this->returnJson(1, "提交成功", ['user' => $user, '剩余报名数' => $this->itemCount[$itemId] - $redisCount]);
    }

    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        foreach ($redis->keys('wx:'.$this->itemName.':signUpCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('wx:'.$this->itemName.':signUpCount', [1 => 0, 2 => 0, 3 => 0, 4 => 0]);
        echo '应用初始化成功';
        exit();
    }
}