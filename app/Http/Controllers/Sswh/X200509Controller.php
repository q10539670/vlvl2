<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200509\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200509Controller extends Common
{
    //奥莱520 天生一对
    protected $itemName = 'x200509';

    const END_TIME = '2020-05-20 23:59:59';

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
                'game_num' => 2,
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
        if ($user->game_num <= 0 && $user->share_num == 1) {
            return response()->json(['error' => '今日游戏次数已用完,分享可多玩一次哦'], 422);
        }
        if ($user->game_num <= 0 && $user->share_num <= 0) {
            return response()->json(['error' => '今日游戏次数已用完'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => 'required',
        ], [
            'score.required' => '成绩不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $score = $request->score * 100;
        if ($score <= 0) {
            $user->game_num--;
            $user->save();
            return $this->returnJson(101, "挑战失败", ['user' => $user]);
        }
        if ($user['score'] > $score || $user->score == 0) {
            $user->fill([
                'score' => $score,
                'level' => 1
            ]);
        }
        $user->game_num--;
        $user->save();
        return $this->returnJson(1, "成绩更新成功", ['user' => $user]);
    }

    /**
     * 排行榜
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $where = function ($query) use ($request) {
            $query->where('score', '!=', 0)/*->where('openid', '!=', $request->openid)*/
            ;
        };
        $perPage = $request->has('per_page') ? $request->per_page : 5; //每页条数
        $currentPage = $request->has('current_page') ? $request->current_page : 1;  //当前页
        if ($request->has('current_page') > 5) {
            $currentPage = 5;
        }
        $total = User::where($where)->count();       // 数据条数
        $totalPage = ceil($total / $perPage);   // 总页数
        $ranking = -1;
        $items = User::where($where)->orderBy('score', 'asc')->orderBy('updated_at', 'asc')->offset($perPage * ($currentPage - 1))->limit($perPage)->get()->toArray();
        foreach ($items as $key => $item) {
            $items[$key]['ranking'] = $perPage * ($currentPage - 1) + $key + 1;
        }
        $listUser = User::where('score', '!=', 0)->orderBy('score', 'asc')->orderBy('updated_at', 'asc')->take(50)->get()->toArray();

        foreach ($listUser as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "中奖记录查询成功", [
            'user' => $user,
            'ranking' => $ranking,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'items' => $items
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
            $user->game_num++;
            $user->share_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }
}
