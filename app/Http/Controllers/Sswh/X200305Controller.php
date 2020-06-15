<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200305\User;
use App\Models\Sswh\X200305\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200305Controller extends Common
{
    //美的
    protected $itemName = 'x200305';
    const START_TIME = '2018-09-21 00:00:00';
    const END_TIME = '2020-03-13 18:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, ['like_num' => 3]);
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
            return response()->json(['error' => '不要重复提交成绩'], 422);
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
            'phone' => $request->phone
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    public function share(Request $request)
    {
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '投票时间未开始，投票通道未开启'], 422);
        }
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
     * 分享点赞
     */
    public function like(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':like', $user->id, 1)) {
            return response()->json(['error' => '点太快了,慢一点哦~'], 422);
        }
        $targetUserId = $request->input('target_user_id');
        //判断target与like的id是否相同
        $like_user_id = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $like_user_id) {
            return response()->json(['error' => '自己不能给自己点赞'], 422);
        }
        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            return response()->json(['error' => '目标ID参数错误'], 422);
        }
        if($targetUser->name == '') {
            return response()->json(['error' => '目标ID未参加活动'], 422);
        }
        if ($user->like_num <= 0) {
            return response()->json(['error' => '今日点赞次数已用完哦'], 422);
        }
        //开启事务
        \DB::beginTransaction();
        try {
            $targetUser = User::find($targetUserId); //目标用户
            $targetUser->likes++;
            $res = $targetUser->save();
            if (!$res) {
                throw new \Exception("目标数据库错误");
            }
            $like = Like::create([
                'target_user_id' => $targetUserId,
                'like_user_id' => $like_user_id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            if (!$like) {
                throw new \Exception("点赞数据库错误");
            }
            $user->like_num--;
            $resUser = $user->save();
            if (!$resUser) {
                throw new \Exception("用户数据库错误");
            }
            \DB::commit();
            return $this->returnJson(1, "点赞成功", ['target_user' => $targetUser]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => '点赞失败'], 422);
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
        $listAll = User::orderBy('likes', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function ($values) {
            return $values['likes'];
        });
        return $this->returnJson(1, "排行榜数据查询成功", ['list' => $list, 'user' => $user]);
    }
}
