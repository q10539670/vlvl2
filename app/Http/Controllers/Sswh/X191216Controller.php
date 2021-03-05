<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191216\Program;
use App\Models\Sswh\X191216\User;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\X191216\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class X191216Controller extends Common
{
    //湘中美的投票
    const END_TIME = '2019-12-25 23:59:59';

    protected $prod = 'cdnn';   // (cdnn / wx)

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
                'vote_num' => 4,
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
     * 获取所有节目
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function programs(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('number', $request->search);
                } else {
                    $query->where('program', 'like', '%' . $request->search . '%');
                }
            }
        };
        //获取所有参赛舞团
        $programs = Program::where($where)->orderBy('number', 'asc')->get();
        $programsList = Program::orderBy('poll', 'desc')->orderBy('updated_at', 'asc')->get();
        foreach ($programsList as $k => $v) {
            for ($i = 0; $i < count($programs); $i++)
                if ($v['id'] == $programs[$i]['id']) {
                    $programs[$i]['rank'] = $k + 1;
                }
        }
        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'programs' => $programs,
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
            return response()->json(['error' => '今日投票次数已用完，明天可以继续为TA投票'], 422);
        }
        if ($voteNums = VoteLog::where('user_id',$user->id)->where('program_id',$request->program_id)->whereBetween('created_at', $this->getToday())->count() >= 2) {
            return response()->json(['error' => '每个节目最多投2票'], 422);
        }
        $program = Program::find($request->program_id);
        if (!$program) {
            return response()->json(['error' => '您投票的节目不存在'], 422);
        }
        $program->poll++;
        $program = $program->save();
        if ($program) {
            $user->vote_num--;
            $user->save();
            $log = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'program_id' => $request->program_id
            ];
            VoteLog::create($log);
            $program = Program::find($request->program_id);
            $poll = $program['poll'];
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
    public function uploadProgram(Request $request)
    {
        $program = $request->all();
        $validator = Validator::make($program, [
            'number' => ['required', 'regex:/^[\d]*$/'],
            'program' => 'required|max:30',
            'slogan' => 'required',
        ], [
            'number.required' => '编号不能为空',
            'number.regex' => '编号只能是数字',
            'program.required' => '名字不能为空',
            'program.max' => '名字太长(最长30个字)',
            'slogan.required' => '简介不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($request->file('img1')) {
            if (!$request->file('img1')->isValid()) {
                return response()->json(['error' => '上传错误'], 422);
            }
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
            if (!$path = $request->img1->store('/wx_items/x191216', $storeDriver)) {
                return response()->json(['error' => '上传错误,请重新上传'], 422);
            }
            $program['img1'] = $path;
        }
        if ($request->file('img2')) {
            if (!$request->file('img2')->isValid()) {
                return response()->json(['error' => '上传错误'], 422);
            }
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
            if (!$path = $request->img2->store('/wx_items/x191216', $storeDriver)) {
                return response()->json(['error' => '上传错误,请重新上传'], 422);
            }
            $program['img2'] = $path;
        }
        $program = Program::create($program);
        $newProgram = Program::find($program->id);
        return $this->returnJson(1, "添加成功", [
            'newProgram' => $newProgram,
        ]);
    }
}
