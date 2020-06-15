<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200121\User;
use App\Models\Sswh\X200121\Help;
use App\Models\Sswh\X200121\Love;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200121Controller extends Common
{
    //百事新年
    protected $itemName = 'x200121';

    const END_TIME = '2020-01-31 23:59:59';

    /**
     * 获取/记录用户授权信息
     * @param Request $request
     * @return false|JsonResponse|string
     */
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
     * 留言
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->words != '') {
            return response()->json(['error' => '您已留言'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'words' => 'required|max:50',
        ], [
            'words.required' => '留言不能为空',
            'words.max' => '留言字数太长',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->words = $request->words;
        $user->save();
        return $this->returnJson(1, "留言成功", ['user' => $user]);
    }

    /**
     * 热爱值
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function click(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':click', $user->id, 3)) {
            return response()->json(['error' => '请慢慢参观'], 422);
        }
        $loveUser = User::where('words', '!=','')->where('id', '!=', $user->id)->inRandomOrder()->first();
        if (!$loveUser) {
            return response()->json(['error' => '暂时没有留言,快去邀请小伙伴来参加吧'], 422);
        }
        if (Love::where(['target_user_id' => $loveUser->id, 'help_user_id' => $user->id])->first()) {
            return $this->returnJson(1, "查询成功", ['loveUser' => $loveUser]);
        } else {
            $loveUser->love++;
            $loveUser->save();
            Love::create([
                'target_user_id' => $loveUser->id,
                'help_user_id' => $user->id,
            ]);
        }
        $loveUser = User::where('id', $loveUser->id)->first();
        return $this->returnJson(1, "查询成功", ['loveUser' => $loveUser]);
    }

    /**
     * 助力
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function help(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':help', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $targetUserId = $request->input('target_user_id');
        //判断target与help的id是否相同
        $helpUserId = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $helpUserId) {
            return response()->json(['error' => '自己不能给自己点赞'], 422);
        }
        $targetUser = User::find($targetUserId);
        if (Help::where(['target_user_id' => $targetUserId, 'help_user_id' => $user->id])->whereBetween('created_at', $this->getToday())->first()) {
            return response()->json(['error' => '你今天已经给TA助力了'], 422);
        }
        //开启事务
        \DB::beginTransaction();
        try {
            $targetUser = User::find($targetUserId); //目标用户
            $targetUser->love++;
            $res = $targetUser->save();
            if (!$res) {
                throw new \Exception("目标数据库错误");
            }
            $help = Help::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $helpUserId,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            if (!$help) {
                throw new \Exception("助力数据库错误");
            }
            $resUser = $user->save();
            if (!$resUser) {
                throw new \Exception("用户数据库错误");
            }
            \DB::commit();
            return $this->returnJson(1, "助力成功", ['target_user' => $targetUser]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => '助力失败'], 422);
        }
    }

    /**
     * 分享
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function share(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':share', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $targetUser = User::where('id', $request->target_user_id)->first();
        return $this->returnJson(1, "查询成功", ['targetUser' => $targetUser]);
    }
    /**
     * 排行榜
     * @param Request $request
     * @return false|JsonResponse|string
     */
    public function list(Request $request)
    {
        $listAll = User::where('avatar','!=','1')->orderBy('love', 'desc')->orderBy('created_at', 'desc')->take(50)->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function ($values) {
            return $values['love'];
        });
        foreach ($list as $key => $value) {
            $list[$key]['ranking'] = $key + 1;
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['list' => $list]);
    }

}
