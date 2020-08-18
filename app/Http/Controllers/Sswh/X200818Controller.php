<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200818\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class X200818Controller extends Common
{

    //金地华中第六届纳凉音乐节 听歌识曲
    protected $itemName = 'x200818';

    const END_TIME = '2020-08-20 20:00:00';  //结束时间

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, ['game_num' => 3]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    public function score(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName.':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交成绩'], 422);
        }
        if ($user->game_num <= 0) {
            $listAll = User::orderBy('score', 'desc')->orderBy('ranking_at', 'asc')->orderBy('created_at', 'asc')->get()->toArray();
            //去掉成绩为0的结果
            $list = array_filter($listAll, function($values){return $values['score'];});
            $ranking = -1;
            foreach ($list as $key => $value) {
                if ($value['openid'] == $request->openid) {
                    $ranking = $key + 1;
                }
            }
            return $this->returnJson(1, "游戏次数用完,该成绩无效", ['user' => $user,'ranking' =>$ranking]);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required', 'regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($user['score'] < $request->score) {
            $user->fill([
                'score' => $request->score,
                'ranking_at' => now()->toDateTimeString()
            ]);
        }
        $user->game_num--;
        $user->save();
        $listAll = User::orderBy('score', 'desc')->orderBy('ranking_at', 'asc')->orderBy('created_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "成绩更新成功", ['user' => $user,'ranking' =>$ranking]);
    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $listAll = User::orderBy('score', 'desc')->orderBy('ranking_at', 'asc')->orderBy('created_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['ranking' =>$ranking,'user'=>$user,'list' => $list]);
    }
}
