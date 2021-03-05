<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200114\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200114Controller extends Common
{
    //奥特莱斯掷骰子
    protected $itemName = 'x200114';

    const START_TIME = '2020-01-17 00:00:00';
    const END_TIME = '2020-01-23 23:59:59';

    /**
     * 获取/记录用户授权信息
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'game_num' => 6,
                'share_num' => 1
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    public function gameNum(Request $request)
    {
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '活动尚未开启'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '游戏已结束，可进入排行榜查看您的积分排行'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->game_num <= 0 && $user->share_num > 0) {
            return response()->json(['error' => '您今日游戏次数已用完，转发此游戏链接至朋友圈/好友，可再获得1次游戏机会'], 422);
        }
        if ($user->game_num <= 0 && $user->share_num <= 0) {
            return response()->json(['error' => '您今日的求助次数已用完，明天才能玩哦'], 422);
        }
        return $this->returnJson(1, "查询成功", ['gameNum' => $user->game_num]);
    }

    /**
     * 提交分数
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function score(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'seat' => 'required',
            'score' => 'required',
        ], [
            'seat.required' => '位置参数异常',
            'score.required' => '分数参数异常',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'seat' => $request->seat,
            'score' => $request->score
        ]);
        $user->game_num--;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    public function addGame(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $user->game_num++;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 排行榜
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
        $items = User::where($where)->orderBy('score', 'desc')->orderBy('updated_at', 'asc')->offset($perPage * ($currentPage - 1))->limit($perPage)->get()->toArray();
        foreach ($items as $key => $item) {
            $items[$key]['ranking'] = $perPage * ($currentPage - 1) + $key + 1;
        }
        $listUser = User::where('score', '!=', 0)->orderBy('score', 'desc')->orderBy('updated_at', 'asc')->take(5)->get()->toArray();

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':share', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if ($user->share_num > 0) {
            $user->share_num--;
            $user->game_num += 3;
            $user->save();
        }
        return Helper::json(1, '分享成功', ['user' => $user]);
    }
}

