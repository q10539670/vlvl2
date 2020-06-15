<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Jctj\X191206\User;
use App\Models\Jctj\X191206\Share;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class X191206Controller extends Common
{
    //存储路径
    const STORE_PATH = '/upload/items/x191206/';
    //前缀
    const URL_PREFIX = 'https://wx.sanshanwenhua.com/vlvl/storage2';
    protected $itemName = 'x191206';

    const END_TIME = '2019-12-16 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJctjUser($request);
//            dd($info);
            $client = new Client();
            $res = $client->request('GET', $info->headimgurl);
            $avatarPath = 'upload/items/' . $this->itemName . '/avatar/' . md5(date('YmdHis') . Str:: random(8)) . '.png';
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'storage2';
            $storage = Storage::disk($storeDriver);
            $storage->put($avatarPath, $res->getBody());
            $userInfo = $this->userInfo($request, $info, [
                'help_num' => 5,
                'avatar_url' => $avatarPath
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
            'img_prefix' => self::URL_PREFIX
        ]);
    }

    /*
     * 资料填写
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
//        $url = $request->header('referer');
//        $paramStr = substr(strstr($url, '?'), 1);
//        $paramArr = explode('&', $paramStr);
        $targetUserId = $request->target_user_id;
        //从$referer中提取target_user_id
//        foreach ($paramArr as $param) {
//            $params = explode('=', $param);
//            if ($params['0'] == 'target_user_id' && $params['1']) {
//                $targetUserId = $params['1'];
        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            return response()->json(['error' => '未查询到目标ID'], 422);
        }
        if (!$targetUserId){
            return response()->json(['error' => '目标ID参数错误'], 422);
        }
        return $this->returnJson(1, "分享成功", [
            'target_user' => $targetUser,
            'img_prefix' => self::URL_PREFIX
        ]);
    }

    /**
     * 点赞
     */
    public
    function likes(Request $request)
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
            return response()->json(['error' => '自己不能给自己点赞'], 422);
        }
        $targetUser = User::find($targetUserId);
        if (Share::where(['target_user_id' => $targetUserId, 'help_user_id' => $user->id])->first()) {
            return response()->json(['error' => '你已经给TA点过赞了'], 422);
        }
        if ($user->help_num <= 0) {
            return response()->json(['error' => '点赞次数已用完'], 422);
        }
        //开启事务
        DB::beginTransaction();
        try {
            $targetUser = User::find($targetUserId); //目标用户
            $targetUser->share_num++;
            $res = $targetUser->save();
            if (!$res) {
                throw new \Exception("目标数据库错误");
            }
            $share = Share::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $helpUserId,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            if (!$share) {
                throw new \Exception("分享数据库错误");
            }
            $user->help_num--;
            $resUser = $user->save();
            if (!$resUser) {
                throw new \Exception("用户数据库错误");
            }
            DB::commit();
            return $this->returnJson(1, "点赞成功", ['target_user' => $targetUser]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '点赞失败'], 422);
        }

    }

    /*
     * 抽奖
     */
    public
    function randomPrize(Request $request)
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
            return response()->json(['error' => '你已中奖，无法再次获得奖品'], 422);
        }
        if ($user->share_num < 10 && $user->prize_num <= 0) {
            return response()->json(['error' => '没有抽奖次数了'], 422);
        } elseif ($user->share_num >= 10) {
            $share_num = $user->share_num - 10;  //取余
            $user->share_num = $share_num;
            $user->prize_num = 1;
            $user->save();
        }
        $redisBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('武汉江宸天街_抽奖', ['message' => $e->getMessage()]);
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
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', 1);
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prize_at = now()->toDateTimeString();
        $user->prize_num--;
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
//            'resultPrize' => $resultPrize
        ]);
    }

    /*
     * 上传海报
     */
    public
    function uploadImg(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':uploadImg', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $validator = Validator::make($request->all(), [
            'img' => 'required|image|max:' . (1024 * 6)
        ], [
            'img.required' => '上传图片不能未空',
            'img.image' => '上传类型只能是图片',
            'img.max' => '图片大小不能超过6M',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        if (!$request->file('img')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'storage2';
        if (!$path = $request->img->store(self::STORE_PATH . date('Ymd'), $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }

        //更新用户表
        $user->fill([
            'img_url' => $path,
        ]);

        $user->save();
        return Helper::json(1, '上传海报成功', ['user' => $user, 'img_prefix' => self::URL_PREFIX]);
    }

    /*
     * 应用初始化
     */
    public
    function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0]);
        $redis->set('wx:' . $this->itemName . ':prizeNum', 0);
        echo '应用初始化成功';
        exit();
    }
}
