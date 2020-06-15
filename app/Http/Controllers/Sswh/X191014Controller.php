<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191014\User;
use App\Models\Sswh\X191014\Comment;
use App\Models\Sswh\X191014\Shop;
use App\Models\Sswh\X191014\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191014Controller extends Common
{

    protected $itemName = 'x191014'; /*项目名称*/
    protected $prod = 'wx';          //图片读取方式  wx/cdnn

    const END_TIME = '2019-10-20 23:59:59';

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
                'vote_num' => 5,
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

    /**
     * 获取所有商家信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shops()
    {
        //搜索框
//        $where = function ($query) use ($request) {
//            if ($request->has('search') && ($request->search != '')) {
//                if (is_numeric($request->search)) {
//                    $query->where('number',$request->search);
//                } else {
//                    $query->where('shop_name', 'like', '%' . $request->search . '%');
//                }
//            }
//        };
//        $shops = Shop::where($where)->orderBy('number', 'asc')->get();
        //获取所有参赛队伍
        $shopsList = Shop::orderBy('poll', 'desc')->orderBy('updated_at', 'asc')->get();
        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'shops' => $shopsList,
        ]);
    }

    public function shop(Request $request)
    {
        $shopInfo = Shop::find($request->input('shop_id'));
        return $this->returnJson(1, "查询成功", [
            'shop_info' => $shopInfo,
        ]);
    }

    /**
     * 投票
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['vote_num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用尽'], 422);
        }
        $shop = Shop::find($request->shop_id);
        if (!$shop) {
            return response()->json(['error' => '您投票的商家不存在'], 422);
        }
        $res = VoteLog::where('user_id', $user->id)
            ->where('shop_id', $request->shop_id)
            ->WhereBetween('created_at', $this->getToday())->get()->toArray();
        if ($res) {
            return response()->json(['error' => '您已经给该商家投过票了喔,去别的商家看看吧'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':vote', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //写入数据库,投票次数+1
        $shop->poll++;
        $shop = $shop->save();
        if ($shop) {
            $user->vote_num--;
            $user->save();
            $log = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'shop_id' => $request->shop_id
            ];
            VoteLog::create($log);
        }
        $shop = Shop::find($request->shop_id);
        $poll = $shop['poll'];
        return $this->returnJson(1, "投票成功", [
            'shop' => $shop,
            'poll' => $poll
        ]);
    }

    /**
     * 上传商家信息接口(postman)
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function uploadShop(Request $request)
    {
        $shop = $request->all();
        $validator = Validator::make($shop, [
            'number' => ['required', 'regex:/^[\d]*$/'],
            'shop_name' => 'required|max:30',
            'shop_img' => 'required|image|max:' . (1024 * 6),
        ], [
            'number.required' => '队伍编号不能为空',
            'number.regex' => '队伍编号只能是数字',
            'shop_name.required' => '队伍名字不能为空',
            'shop_name.max' => '队伍名字太长(最长30个字)',
            'shop_img.required' => '队伍图片不能为空',
            'shop_img.image' => '只能上传图片类型',
            'shop_img.max' => '图片大小不能超过6M'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('shop_img')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->shop_img->store('/wx_items/' . $this->itemName, $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $shop['shop_img'] = $path;
        $shop = Shop::create($shop);
        $newShop = Shop::find($shop->id);
        return $this->returnJson(1, "添加成功", [
            'newShop' => $newShop,
        ]);
    }

    /**
     * 抽奖
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
        if ($user->status != 0) {
            return response()->json(['error' => '你已经抽过奖了'], 422);
        }
        if ($user->vote_num != 0) {
            return response()->json(['error' => '投完票才能抽奖'], 422);
        }
        $dateStr = date('Ymd');
        $redisCountBaseKey = 'wx:' . $this->itemName . ':prizeCount';
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisCountBaseKey, $dateStr); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('金地美食嘉年华', ['message' => $e->getMessage()]);
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
            // $user->status = $resultPrize['resultPrize']['money'];
            $user->status = 1;
            $user->prize_code = User::getUniqueCode(6);
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
//            'resultPrize' => $resultPrize
        ]);
    }

    /**
     * 查询商家所有评论
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $shop_id = $request->input('shop_id');
        $comments = Comment::where('shop_id', $shop_id)->where('status', 1)->get()->toArray();
        for ($i = 0; $i < count($comments); $i++) {
            $comments[$i]['images'] = explode('|', $comments[$i]['images']);
            $user = User::find($comments[$i]['user_id']);
            if($user){
                $comments[$i]['nickname'] = $user->nickname;
                $comments[$i]['avatar'] = $user->avatar;
            }
        }
        $resComments = [];
        for ($ii = 0; $ii < count($comments); $ii++) {
            if(isset($comments[$ii]['nickname'])){
                $resComments[] = $comments[$ii];
            }
        }
        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return Helper::json(1, '查询成功', [
            'comments' => $resComments,
            'prefix' => $prefix
        ]);
    }

    /**
     * 评论
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function submitComm(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':submitComm', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        $shop = Shop::find($request->shop_id);
        if (!$shop) {
            return response()->json(['error' => '您评论的商家不存在'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required',
            'comment' => 'required',
            'rated' => 'required'
        ], [
            'shop_id.required' => '商家ID不能为空',
            'comment.required' => '评论不能为空',
            'rated.required' => '打分不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('images')) {
            $comment = Comment::create([
                'shop_id' => $request->input('shop_id'),
                'user_id' => $user->id,
                'comment' => $request->input('comment'),
                'images' => '',
                'status' => 1,
                'rated' => $request->input('rated'),
            ]);
            return $this->returnJson(1, "评论成功", ['comment' => $comment]);
        }
        $file = $request->file('images');
        if (count($file) > 9) {
            return response()->json(['error' => '最多上传9张图片'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        foreach ($request->file('images') as $value) {
            if (in_array(strtolower($value->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                if (!$images[] = $value->store('/wx_items/' . $this->itemName, $storeDriver)) {
                    return response()->json(['error' => '上传错误,请重新上传'], 422);
                }
            } else {
                return response()->json(['error' => '上传错误,只能上传图片类型'], 422);
            }
        }
        $images = implode('|', $images);
        $comment = Comment::create([
            'shop_id' => $request->input('shop_id'),
            'user_id' => $user->id,
            'comment' => $request->input('comment'),
            'images' => $images,
            'status' => 1,
            'rated' => $request->input('rated'),
        ]);
        return $this->returnJson(1, "评论成功", ['comment' => $comment]);
    }

    /**
     * 应用初始化
     */
    public
    function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dataArr = [
            'test',
            '20191019',
            '20191020'
        ];
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount:*') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
//        foreach ($dataArr as $k => $v) {
//            $redis->hmset('wx:' . $this->itemName . ':prizeCount:yuelu:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0]);
//            $redis->hmset('wx:' . $this->itemName . ':prizeCount:other:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0]);
//        }
        foreach ($dataArr as $k => $v) {
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0]);
            $redis->hmset('wx:' . $this->itemName . ':prizeCount:' . $v, ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0]);
        }
        $redis->set('wx:' . $this->itemName . ':rankList', '[]');
        echo '应用初始化成功';
        exit();
    }
}
