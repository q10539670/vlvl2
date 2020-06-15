<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\Tp190905\Team;
use App\Models\Sswh\Tp190905\User;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Tp190905\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Tp190905Controller extends Controller
{
    const END_TIME = '2019-11-15 23:59:59';

    protected $prod = 'wx';

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
                'nickname' => $info['nickname']?:'',
                'avatar' => $info['headimgurl'],
                'vote_num' => 3,
            ]);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' =>self::END_TIME
        ]);
    }

    /**
     * 获取所有参赛人员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function teams(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('number', $request->search);
                } else {
                    $query->where('team_name', 'like', '%' . $request->search . '%');
                }
            }
        };
        //获取所有参赛舞团
        $teams = Team::where($where)->orderBy('number', 'asc')->get();
        $teamsList = Team::orderBy('poll', 'desc')->orderBy('updated_at', 'asc')->get();
        foreach ($teamsList as $k => $v) {
            for ($i = 0; $i < count($teams); $i++)
                if ($v['id'] == $teams[$i]['id']) {
                    $teams[$i]['rank'] = $k + 1;
                }
        }
        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'teams' => $teams,
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
        $team = Team::find($request->team_id);
        if (!$team) {
            return response()->json(['error' => '您投票的舞团不存在'], 422);
        }
        $team->poll++;
        $team = $team->save();
        if ($team) {
            $user->vote_num--;
            $user->save();
            $log = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'team_id' => $request->team_id
            ];
            VoteLog::create($log);
            $team = Team::find($request->team_id);
            $poll = $team['poll'];
            return $this->returnJson(1, "投票成功", [
                'poll' => $poll
            ]);
        }
    }

    /**
     * 上传参赛舞团接口(postman)
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function uploadTeams(Request $request)
    {
        $team = $request->all();
        $validator = Validator::make($team, [
            'number' => ['required', 'regex:/^[\d]*$/'],
            'team_name' => 'required|max:30',
            'team_introduction' => 'required',
        ], [
            'number.required' => '舞团编号不能为空',
            'number.regex' => '舞团编号只能是数字',
            'team_name.required' => '舞团名字不能为空',
            'team_name.max' => '舞团名字太长(最长30个字)',
            'team_introduction.required' => '舞团简介不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($request->file('team_img')) {
            if (!$request->file('team_img')->isValid()) {
                return response()->json(['error' => '上传错误'], 422);
            }
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
            if (!$path = $request->team_img->store('/wx_items/a190905', $storeDriver)) {
                return response()->json(['error' => '上传错误,请重新上传'], 422);
            }
            $team['team_img'] = $path;
        }
        $team = Team::create($team);
        $newTeam = Team::find($team->id);
        return $this->returnJson(1, "添加成功", [
            'newTeam' => $newTeam,
        ]);
    }
}
