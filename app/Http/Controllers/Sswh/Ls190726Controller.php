<?php

namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\Ls190726\Score;
use App\Models\Sswh\Ls190726\ScoreLog;
use App\Models\Sswh\SswhUsers as Sswh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Ls190726Controller extends Controller
{
    const END_TIME = '2019-08-07 23:59:59';
    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$score = Score::where(['openid' => $openid])->first()) {
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到表中
            Score::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'game_num' => 2,
                'share_num' =>3
            ]);
            //查询
            $score = Score::where(['openid' => $openid])->first();
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
        if (!$score = Score::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if ($score['game_num'] <= 0) {
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
        $total = $score['total'] + $request->score;
        $score->game_num--;
        $score->fill([
            'score' => $request->score,
            'total' => $total,
            ]);
        $score->save();
        ScoreLog::create([
            'score' =>$request->score,
            'user_id' => $score['id']
        ]);
        return $this->returnJson(1, "成绩更新成功", ['score'=>$score]);

    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        $listAll = Score::orderBy('total', 'desc')->orderBy('created_at', 'desc')->take(50)->get()->toArray();
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

    /*
     * 分享
     */
    public function share(Request $request)
    {
        if (!$score = Score::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($score['share_num'] > 0) {
            $score->game_num++;
            $score->share_num--;
            $score->save();
        }

        return $this->returnJson(1, "分享成功");
    }
}
