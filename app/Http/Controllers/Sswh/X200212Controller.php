<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200212\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200212Controller extends Common
{
    //美的情人节
    protected $itemName = 'x200212';

    const END_TIME = '2020-02-20 18:00:00';

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
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交成绩'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => 'required',
        ], [
            'score.required' => '成绩不能为空'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $score = $request->score * 100;
        if ($score == 0) {
            return response()->json(['error' => '成绩异常'], 422);
        }
        if ($user['score'] > $score || $user['score'] == 0) {
            $user->fill([
                'score' => $score,
            ]);
            $user->save();
        }
        return $this->returnJson(1, "成绩更新成功", ['user' => $user]);
    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $list = User::where('score', '!=', 0)->orderBy('score', 'asc')->orderBy('updated_at', 'asc')->get()->toArray();
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['user' => $user, 'list' => $list, 'ranking' => $ranking]);
    }
}
