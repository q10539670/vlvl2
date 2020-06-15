<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\Tp190911\Users;
use App\Models\Sswh\Tp190911\User;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Tp190911\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Tp190911Controller extends Controller
{
    const END_TIME = '2019-09-15 23:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到表中
            User::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'vote_num' => 3,
            ]);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /**
     * 获取所有参赛人员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function players(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('number', $request->search);
                } else {
                    $query->where('name', 'like', '%' . $request->search . '%');
                }
            }
        };
        //获取所有参赛人员
        $perPage = $request->has('per_page') ? $request->per_page : 10; //每页显示数量
        $currentPage = $request->has('current_page') ? $request->current_page : 1; //当前页
        $total = Users::count(); //总数量
        $totalPage = ceil($total / $perPage); //总页数
        $query = Users::where($where)->orderBy('number', 'asc');
        $players = $query->offset($perPage * ($currentPage -1))->limit($perPage)->get()->toArray();
        $playerList = Users::orderBy('poll', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
        foreach ($playerList as $k => $v) {
            for ($i = 0; $i < count($players); $i++)
                if ($v['id'] == $players[$i]['id']) {
                    $players[$i]['rank'] = $k + 1;
                }
        }
        return $this->returnJson(1, "查询成功", [
            'per_page'=>$perPage,
            'current_page' =>$currentPage,
            'total'=> $total,
            'total_page'=>$totalPage,
            'players' => $players,
        ]);
    }

    /**
     * 投票
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['vote_num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用尽'], 422);
        }
        $player = Users::find($request->player_id);
        if (!$player) {
            return response()->json(['error' => '您投票的选手不存在'], 422);
        }
        $player->poll++;
        $players = $player->save();
        if ($players) {
            $user->vote_num--;
            $user->save();
            $log = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'player_id' => $request->player_id
            ];
            VoteLog::create($log);
            $player = Users::find($request->player_id);
            $poll = $player['poll'];
            return $this->returnJson(1, "投票成功", [
                'poll' => $poll
            ]);
        }

    }

    /**
     * 上传参赛人员接口(postman)
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function uploadPlayers(Request $request)
    {
        $player = $request->all();
        $validator = Validator::make($player, [
            'number' => ['required', 'regex:/^[\d]*$/'],
            'name' => 'required|max:30',
        ], [
            'number.required' => '编号不能为空',
            'number.regex' => '编号只能是数字',
            'name.required' => '名字不能为空',
            'name.max' => '名字太长(最长30个字)',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $player = Users::create($player);
        $newPlayer = Users::find($player->id);
        return $this->returnJson(1, "添加成功", [
            'newPlayer' => $newPlayer,
        ]);

    }
}

