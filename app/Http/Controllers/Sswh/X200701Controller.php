<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200701\Images;
use App\Models\Sswh\X200701\User;
use App\Models\Sswh\X200701\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class X200701Controller extends Common
{
    //红牛抽奖
    protected $itemName = 'x200701';
    protected $prod = 'cdnn';   // (cdnn / wx)

    const END_TIME = '2020-08-03 23:59:59';

    //test:测试 gold:正式
    const TYPE = 'test';

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
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /**
     * 上传小票
     * @param Request $request
     * @return
     */
    public function images(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (Images::where('user_id', $user->id)->whereBetween('created_at', $this->getToday())->first()) {
            return response()->json(['error' => '每天只能上传一张小票'], 422);
        }
        $validator = \Validator::make($request->all(), [
            'image' => 'required|image|max:' . (1024 * 6),
        ], [
            'image.required' => '上传图片不能为空',
            'image.image' => '上传类型只能是图片',
            'image.max' => '图片大小不能超过6M',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/' . $this->itemName, $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $image = [
            'user_id' => $user->id,
            'path' => $path,
            'url' => 'https://cdnn.sanshanwenhua.com/statics/' . $path,
        ];
        $images = Images::create($image);
        $user->img_upload_num++;
        $user->prize_num++;
        $user->game_num++;
        $result = $user->save();
        $log = Log::create([
            'user_id' => $user->id,
            'origin' => 1    //来源上传
        ]);
        //开启事务
        if ($image && $result && $log) {
            DB::commit();
            return $this->returnJson(1, '上传小票成功', ['images' => $images]);
        } else {
            DB::rollback();
            return $this->returnJson(-1, '上传小票失败');
        }

    }

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'truename' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ], [
            'phone.required' => '电话不能为空',
            'truename.required' => '名字不能为空',
            'address.required' => '地址不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->truename = $request->truename;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 抽奖
     */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //项目是否上线
        if (self::TYPE == 'gold') {
            if (time() > strtotime(self::END_TIME)) {
                return response()->json(['error' => '活动已结束'], 422);
            }
            //阻止重复提交
            if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
                return response()->json(['error' => '不要重复提交'], 422);
            }
            if ($user->game_num <= 0) {
                return response()->json(['error' => '没有抽奖次数'], 422);
            }
        }
        // ***********处理当天中奖和上传小票抽奖
        if (!$log = Log::where('user_id', $user->id)->where('status', 0)->orderBy('origin', 'asc')->first()) {
            return response()->json(['error' => '没有抽奖次数'], 422);
        }
        $prize = Log::where('user_id', $user->id)->where('status', 1)->whereBetween('created_at',$this->getToday())->first();
        if ($log->origin == 1 || $prize) {
            $user->game_num--;//抽奖次数-1
            //存入中奖记录表
            $log->result_id = 0;
            $log->status = 2;
            $log->result_name = '未中奖';
            $log->prized_at = now()->toDateTimeString();
            //开启事务
            DB::beginTransaction();
            try {
                $user->save();
                $log->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return Helper::json(-1, '抽奖失败');
            }
            return Helper::json(1, '抽奖成功', ['user' => $user, 'log' => $log, 'result_name' => '未中奖', 'result_id' => 0]);
        }
        //*******************结束
        //处理正常抽奖
        $redis = app('redis');
        $redis->select(12);
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, self::TYPE); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('红牛_抽奖', ['message' => $e->getMessage()]);
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
        $user->game_num--;//抽奖次数-1
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $log->status = 1;
            $user->bingo_num++; //中奖次数+1
        } else {
            $log->status = 2;
        }
        $log->result_id = $resultPrize['resultPrize']['prize_id'];
        $log->result_name = $resultPrize['resultPrize']['prize_name'];
        $log->prized_at = now()->toDateTimeString();

        try {
            $user->save();
            $log->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::Json(-1, '抽奖失败');
        }
        return Helper::Json(1, '抽奖成功', [
            'user' => $user,
            'log' => $log,
            'result_name' => $resultPrize['resultPrize']['prize_name'],
            'result_id' => $resultPrize['resultPrize']['prize_id'],
            'resultPrize' => $resultPrize
        ]);
    }

    /*
     * 上传记录
     */
    public function uploadLog(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $images = Images::where('user_id',$user->id)->get();
        foreach ($images as $image) $image->prize;
        return Helper::Json(1,'上传记录查询成功',['images'=>$images]);
    }

    /*
     * 中奖记录
     */
    public function prizeLog(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $prizeLog = Log::where('user_id',$user->id)->where('status','!=',0)->get();
        return Helper::Json(1,'上传记录查询成功',['prize_log'=>$prizeLog]);
    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dateArr = [
            'test',
            'gold'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dateArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['1' => 0, '2' => 0, '3' => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, '0' => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
