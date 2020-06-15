<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200515\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200515Controller extends Common
{
    //美好置业 用爱“智造”美好人居
    protected $itemName = 'x200515';

//    const START_TIME = '2020-06-01 00:00:00';
    const END_TIME = '2020-06-28 23:59:59';

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
            'nowWeek' =>$user->getWeek(),
            'time' => date('Y-m-d H:i:s'),
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
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 1)) {
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
            'map' => 'required',
            'score' => 'required',
        ], [
            'map.required' => '地图不能为空',
            'score.required' => '成绩不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        //获取当前周
        if (!$request->has('time')) {
            $request->time = '无';
        }else {

        }
        $week = $user->getWeek();
        //拼接数据字段名
        $mapName = 'map_' . $week;
        $scoreName = 'score_' . $week;
        $time = 'time_' . $week;
        $score = $request->score * 100;
//        dd($user[$scoreName]);
        if ($user[$scoreName] > $score || $user[$scoreName] == 0) {
            $user->fill([
                $scoreName => $score,
                $mapName => $request->map,
                $time => $request->time
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
        $nowWeek = $week = $user->getWeek();
        if ($request->has('week') && $request->input('week') != 0) {
            $week = $request->week;
        }
        $scoreName = 'score_' . $week;
        $rankingName = 'ranking_' . $week;
        $where = function ($query) use ($scoreName, $request) {
            $query->where($scoreName, '!=', 0);
        };
        $ranking = -1;
        $items = User::where($where)->orderBy($scoreName, 'asc')->orderBy('updated_at', 'asc')->take(50)->get()->toArray();
        foreach ($items as $key => $item) {
            $items[$key][$rankingName] = $key + 1;
            if ($item['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
        }
        return $this->returnJson(1, "中奖记录查询成功", [
            'nowWeek' => $nowWeek,
            'ranking' => $ranking,
            'user' => $user,
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

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '手机号不能为空',
            'address.required' => '地址不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        $user->save();
        return $this->returnJson(1, "信息更新成功", ['user' => $user]);
    }

    /**
     * 排名
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function ranking(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $week = $user->getWeek();
        if ($week > 1) {
            $week -= 1;
        } elseif ($week == 0 && time() > strtotime(self::END_TIME)) {
            $week = 4;
        } else {
            return $this->returnJson(1, "查询成功1", [
                'user' => $user,
            ]);
        }
        $scoreName = 'score_' . $week;
        $rankingName = 'ranking_' . $week;
        if ($ranking = $user[$rankingName] == 0) {
            $where = function ($query) use ($scoreName, $request) {
                $query->where($scoreName, '!=', 0);
            };
            $ranking = -1;
            $items = User::where($where)->orderBy($scoreName, 'asc')->orderBy('updated_at', 'asc')->get()->toArray();
            foreach ($items as $key => $item) {
                $userR = User::find($item['id']);
                $userR->fill([$rankingName => -1]);
                if ($key <= 2) {
                    $userR->fill([$rankingName => $key + 1]);
                }
                $userR->save();
                if ($item['openid'] == $request->openid) {
                    $ranking = $key + 1;
                }
            }
            $user->fill([$rankingName => $ranking]);
            $user->save();
            return $this->returnJson(1, "查询成功", [
                'user' => $user,
                'ranking' => $ranking,
            ]);
        } else {
            return $this->returnJson(1, "查询成功", [
                'user' => $user,
                'ranking' => $user[$rankingName],
            ]);
        }
    }
}
