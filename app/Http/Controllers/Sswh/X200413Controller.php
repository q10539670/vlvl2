<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200413\User;
use App\Models\Sswh\X200413\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200413Controller extends Common
{
    //中国中铁
    protected $itemName = 'x200413';
    const START_TIME = '2020-04-16 15:00:00';
    const END_TIME = '2020-04-18 15:00:00';
    const GOODS_ID = '2';   //活动商品

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

    /*
     * 给自己砍价
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
        $redis = app('redis');
        $redis->select(12);
        if ($redis->get('wx:' . $this->itemName . ':done1') == self::GOODS_ID) {
            return response()->json(['error' => '商品已被砍走了哦,活动已结束'], 422);
        }

        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交砍价'], 422);
        }
        if ($user->status >0 && $user->status < 20) {
            return response()->json(['error' => '您已经参加活动'], 422);
        }
        if ($user->status >20) {
            return response()->json(['error' => '您已经完成砍价'], 422);
        }
        if (self::GOODS_ID == 1) {
            if ($user->status == 11) {
                return response()->json(['error' => '您已经给自己砍过价了'], 422);
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
                'phone' => $request->phone,
                'status' => 11,
                'price_1' => 30000
            ]);
            $user->save();
        }
        if (self::GOODS_ID == 2) {
            if ($user->status == 12) {
                return response()->json(['error' => '您已经给自己砍过价了'], 422);
            }
            if ($user->name == '') {
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
                    'phone' => $request->phone,
                ]);
            }
            $user->status = 12;
            $user->price_2 = 30000;
            $user->save();
        }
        $redisKey = 'wx:' . $this->itemName . ':user_' . $user->id;
        //安排每次砍价的金额,存入redis
        if (self::GOODS_ID == 1) {
            $priceArr = $user->getPrice(33538);
        } else {
            $priceArr = $user->getPrice(40404);
        }
        $priceStr = implode($priceArr, ',');
        $redis->hmset($redisKey, self::GOODS_ID, $priceStr);
        return $this->returnJson(1, "砍价成功", ['user' => $user]);
    }

    /*
     * 分享
     */
    public function share(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $url = $request->fullUrl();

        $paramStr = substr(strstr($url, '?'), 1);
        $paramArr = explode('&', $paramStr);

        //从$referer中提取target_user_id
        foreach ($paramArr as $param) {
            $params = explode('=', $param);
            if ($params['0'] == 'target_user_id') {
                $targetUserId = $params['1'];
                $targetUser = User::find($targetUserId);
            } else {
                return response()->json(['error' => '未查询到目标ID'], 422);
            }
        }
        return $this->returnJson(1, "查询成功", ['target_user' => $targetUser]);
    }

    /*
     * 砍价
     */
    public function help(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':help', $user->id, 1)) {
            return response()->json(['error' => '点太快了,慢一点哦~'], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        if ($redis->get('wx:' . $this->itemName . ':done1') == self::GOODS_ID) {
            return response()->json(['error' => '商品已被砍走了哦,活动已结束'], 422);
        }
        $targetUserId = $request->input('target_user_id');
        //判断target与help的id是否相同
        $help_user_id = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $help_user_id) {
            return response()->json(['error' => '您已经给自己砍过价了'], 422);
        }
        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            return response()->json(['error' => '目标ID参数错误'], 422);
        }
        if ($targetUser->name == '') {
            return response()->json(['error' => '目标ID未参加活动'], 422);
        }
        if (Help::where('target_user_id', $targetUserId)->where('help_user_id', $user->id)->first()) {
            return response()->json(['error' => '您已经帮他砍过价了'], 422);
        }

        $redisKey = 'wx:' . $this->itemName . ':user_' . $targetUserId;
        $priceStr = $redis->hmget($redisKey, self::GOODS_ID);
        if ($priceStr['0'] == '' && $targetUser->price_1 == 63538 && $targetUser->help_num_1 == 70) {
            return response()->json(['error' => '用户已砍到最底价'], 422);
        }
        if ($priceStr['0'] == '' && $targetUser->price_2 == 70404  && $targetUser->help_num_2 == 70) {
            return response()->json(['error' => '用户已砍到最底价'], 422);
        }
        $priceArr = explode(',', $priceStr['0']);
        //随机从数组中取出一个值
        $key = array_rand($priceArr, 1);   //key
        $price = $priceArr[$key];    //val

        //开启事务
        \DB::beginTransaction();
        try {
            $targetUser = User::find($targetUserId); //目标用户
            if (self::GOODS_ID == 1) {
                $targetUser->help_num_1++;
                $targetUser->price_1 = $targetUser->price_1 + $price;
                if ($targetUser->price_1 == 63538 && $targetUser->help_num_1++ == 70) {
                    $redis->set('wx:' . $this->itemName . ':done1', '1');
                    $targetUser->help_num_1--;
                    $targetUser->status = 21;
                }

            }
            if (self::GOODS_ID == 2) {
                $targetUser->help_num_2++;
                $targetUser->price_2 = $targetUser->price_2 + $price;
                if ($targetUser->price_2 == 70404 && $targetUser->help_num_2++ == 70) {
                    $redis->set('wx:' . $this->itemName . ':done2', '2');
                    $targetUser->help_num_2--;
                    $targetUser->status = 22;
                }
            }
            $res = $targetUser->save();
            if (!$res) {
                throw new \Exception("目标数据库错误");
            }
            $help = Help::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $help_user_id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
                'price' => $price
            ]);
            if (!$help) {
                throw new \Exception("砍价数据库错误");
            }
            \DB::commit();
            //砍价成功则把该值删掉,把新的数组存入redis
            unset($priceArr[$key]);
            $priceStr = implode($priceArr, ',');
            $redis->hmset($redisKey, self::GOODS_ID, $priceStr);
            return $this->returnJson(1, "砍价成功", ['target_user' => $targetUser,'price' =>$price]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => '砍价失败'], 422);
        }
    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (self::GOODS_ID == 1) {
            $listAll = User::orderBy('price_1', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
            //去掉成绩为0的结果
            $lists = array_filter($listAll, function ($values) {
                return $values['price_1'];
            });
            foreach ($lists as $key => $list) {
                $lists[$key]['rate'] = round($list['price_1'] / 63538 * 100);
            }
        }

        if (self::GOODS_ID == 2) {
            $listAll = User::orderBy('price_2', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
            //去掉成绩为0的结果
            $lists = array_filter($listAll, function ($values) {
                return $values['price_2'];
            });
            foreach ($lists as $key => $list) {
                $lists[$key]['rate'] = round($list['price_2'] / 70404 * 100);
            }
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['lists' => $lists, 'user' => $user]);
    }
}
