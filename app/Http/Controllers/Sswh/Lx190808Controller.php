<?php

namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\Lx190808\User;
use App\Models\Sswh\Lx190808\ScoreLog;
use App\Models\Sswh\SswhUsers as Sswh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Lx190808Controller extends Controller
{
    const END_TIME = '2019-08-15 23:59:59';
    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$score = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到表中
            User::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'game_num' => -1,
                'share_num' =>-1
            ]);
            //查询
            $score = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1,"查询成功",[
            'score'=>$score,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /*
     * 成绩提交
     */
    public function score(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['game_num'] = 0) {
            return response()->json(['error' => '今日游戏次数已用尽'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required','regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $total = $user['total'] + $request->score;
        $user->game_num--;
        $user->fill([
            'score' => $request->score,
            'total' => $total,
            ]);
        $user->save();
        ScoreLog::create([
            'score' =>$request->score,
            'user_id' => $user['id']
        ]);
        return $this->returnJson(1, "成绩更新成功", ['user'=>$user]);

    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        $listAll = User::orderBy('total', 'desc')->orderBy('updated_at', 'asc')->take(50)->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['total'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['list' => $list, 'ranking' =>$ranking]);
    }
}
