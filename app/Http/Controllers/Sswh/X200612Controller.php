<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200612\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200612Controller extends Common
{
    //世纪山水
    protected $itemName = 'x200612';

    const END_TIME = '2020-06-30 23:59:59';

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
                'game_num' => 3,
                'share_num' => 1
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' =>self::END_TIME
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
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->game_num <= 0) {
            return response()->json(['error' => '游戏次数已用尽'], 422);
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
        $user->game_num--;
        if ($user->score < $request->score) {
            $user->fill([
                'score' => $request->score,
            ]);
        }
        $user->save();
        return $this->returnJson(1, "成绩提交成功", ['user'=>$user]);

    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        $listAll = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->take(60)->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['ranking' =>$ranking,'list' => $list]);
    }

    /*
     * 分享
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->share_num > 0) {
            $user->share_num--;
            $user->game_num++;
            $user->save();
        }
        return Helper::json(1, '分享成功', ['user' => $user]);
    }

}
