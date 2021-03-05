<?php


namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\Common;
use App\Models\Sswh\Bl190920\User;
use App\Models\Sswh\Bl190920\Draw;
use App\Models\Sswh\Bl190920\DrawLog;
use App\Models\Sswh\Bl190920\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bl190920Controller extends Common
{

    const END_TIME = '2019-09-27 18:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, ['draw_num' => 3]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        //获取助力目标ID
        $targetUserId = $this->getTargetUserId($request);
        if (!$targetUserId) {
            return $this->returnJson(1, "查询成功", [
                'user' => $user,
                'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1
            ]);
        }
        //判断target与help的id是否相同
        $targetUser = User::find($targetUserId);
        if ($targetUser->truename == '') {
            return response()->json(['error' => '目标ID未参与活动'], 422);
        }
        $helpUserId = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $helpUserId) {
            return response()->json(['error' => '自己不能给自己加油'], 422);
        }
        $today = $this->getToday();
        //检查此用户今天是否助力过目标用户
        $res = Help::where('target_user_id', $targetUserId)
            ->where('help_user_id', $user->id)
            ->WhereBetween('created_at', $today)->get()->toArray();
        if (!$res) { //助力过就不加次数
            if ($targetUser->help_num < 5) {
                $targetUser->help_num++;
                $res = $targetUser->save();
            }
        }
        //写入数据库
        if ($res) {
            Help::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $helpUserId,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            $user->save();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'targetUser' => $targetUser
        ]);

    }

    /*
     * 报名
     * */
    public function post(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
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
            'phone' => $request->phone
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 抽奖
     */
    public function draw(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->draw_num <= 0) {
            return response()->json(['error' => '您今天的抽奖次数已用尽'], 422);
        }
        if ($user->help_num == 5) {
            $user->draw_num++;
            $user->help_num++;
        }
        if (Draw::where('user_id',$user->id)->first()) {
            DrawLog::create([
                'user_id' => $user->id,
                'draw' => 0,
            ]);
            return $this->returnJson(1, "很遗憾没有中奖");
        }
        $user->draw_num--;
        $user->save();
        $drawLog = new DrawLog();
        $drawLog = $drawLog->fixRandomPrize();
        if ($drawLog['resultPrize']['prize_level_name'] == '中奖') {
            Draw::create([
                'user_id' => $user->id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
                'truename' => $user->truename,
                'phone' => $user->phone,
            ]);
            DrawLog::create([
                'user_id' => $user->id,
                'draw' => 1,
            ]);
            return $this->returnJson(1, "恭喜中奖啦", ['drawLog' => $drawLog]);
        } else {
            DrawLog::create([
                'user_id' => $user->id,
                'draw' => 0,
            ]);
            return $this->returnJson(1, "很遗憾没有中奖", ['drawLog' => $drawLog]);
        }
    }

    /**
     * 中奖名单
     */
    public function list()
    {
        $lists = $this->hideTel(Draw::get(),'hide_phone');
        return $this->returnJson(1, "查询成功", ['lists' => $lists]);
    }

    /*
     * 分享助力
     */
    public function share(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
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
                if (!$targetUser) {
                    return response()->json(['error' => '未查询到目标ID'], 422);
                }
            } else {
                return response()->json(['error' => '未查询到目标ID'], 422);
            }
        }
        //判断target与help的id是否相同
        $help_user_id = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $help_user_id) {
            return response()->json(['error' => '自己不能给自己加油'], 422);
        }

        if ($targetUser->truename == '') {
            return response()->json(['error' => '目标ID未参与活动'], 422);
        }
        $todayStart = date('Y-m-d') . ' 00:00:00';
        $todayend = date('Y-m-d') . ' 23:59:59';
        //检查此用户今天是否助力过目标用户
        $res = Help::where('target_user_id', $targetUserId)
            ->where('help_user_id', $user->id)
            ->WhereBetween('created_at', [$todayStart, $todayend])->get()->toArray();
        if (!$res) { //助力过就不加次数
            if ($targetUser->help_num < 5) {
                $targetUser->help_num++;
                $res = $targetUser->save();
            }
        }
        //写入数据库
        if ($res) {
            Help::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $help_user_id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['target_user' => $targetUser]);
    }
}
