<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200512\User;
use App\Models\Sswh\X200512\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200512Controller extends Common
{
    //投票
    protected $itemName = 'x200512';

    const START_TIME = '2020-01-17 09:00:00';
    const END_TIME = '2020-06-02 20:00:00';

    protected $prod = 'cdnn';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'poll_num' => 1,
                'share_num' =>3
            ]);
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
     * 获取所有参赛图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function images()
    {
        $images = Poll::orderBy('number', 'asc')->get()->toArray();
        foreach ($images as $k => $image) {
            $images[$k]['img'] = explode('|', $image['images']);
            unset($images[$k]['images']);
        }
        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'images' => $images,
        ]);
    }

    /**
     * 投票
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function poll(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['poll_num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用尽'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '每3秒才能投一次票哦'], 422);
        }
        $image = Poll::find($request->id);
        if (!$image) {
            return response()->json(['error' => '您投票的照片不存在'], 422);
        }
        $image->polls++;
        $image->save();
        $user->poll_num--;
        $user->save();
        $poll = $image->polls;
        return $this->returnJson(1, "投票成功", [
            'poll' => $poll,
            'user' => $user
        ]);

    }

    /**
     * 分享
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function share(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->poll_num++;
            $user->share_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }

    /**
     * 上传照片接口(postman)
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function uploadImages(Request $request)
    {
        $polls = $request->all();
        $validator = Validator::make($polls, [
            'number' => ['required', 'regex:/^[\d]*$/'],
            'name' => 'required|max:30',
        ], [
            'number.required' => '编号不能为空',
            'number.regex' => '编号只能是数字',
            'name.required' => '名字不能为空',
            'name.max' => '名字太长(最长30个字)',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $file = $request->file("images");
        if (empty($file)) {
            return response()->json(['error' => '请先选择文件', 422]);
        }
        if (count($file) > 5) {
            return response()->json(['error' => '最多可以上传5张图片', 422]);
        }
        $images ='';
        for ($i = 0; $i < count($file); $i++) {
            if (!$file[$i]->isValid()) {
                return response()->json(['error' => '上传错误'], 422);
            }
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
            if (!$path = $file[$i]->store('/wx_items/'. $this->itemName, $storeDriver)) {
                return response()->json(['error' => '上传错误,请重新上传'], 422);
            }
            if ($images == '') {
                $images .= $path;
            } else {
                $images .= '|'.$path;
            }
        }
        $polls['images'] =  $images;
        $poll = Poll::create($polls);
        $poll = Poll::find($poll->id);
        return $this->returnJson(1, "添加成功", [
            'poll' => $poll,
        ]);
    }

    /**
     * 提交信息
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'phone.required' => '电话不能为空',
            'name.required' => '名字不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 随机抽奖
     *  status     1中奖  2：未中奖【红包发送失败】     3:未中奖【未抽中奖】
     * */
    public function randomPrize(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }

        $redis = app('redis');
        $redis->select(12);
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '答题活动还未开始'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->status != 0) {
            return response()->json(['error' => '你已抽奖，无法再次抽奖'], 422);
        }
        $dateStr = date('Ymd');
        //测试
//        $dateStr = 'test';
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, $dateStr); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('华为_抽奖', ['message' => $e->getMessage()]);
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
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize = $resultPrize['resultPrize']['prize_name'];
//        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prized_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
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
        $dataArr = [
            'test', '20200525', '20200526', '20200527', '20200528', '20200529', '20200530', '20200531', '20200601', '20200602'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['0' => 0, '1' => 0]);
        }
        echo '应用初始化成功';
        exit();
    }
}
