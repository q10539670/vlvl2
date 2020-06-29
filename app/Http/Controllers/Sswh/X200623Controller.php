<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200623\Program;
use App\Models\Sswh\X200623\User;
use App\Models\Sswh\X200623\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200623Controller extends Common
{
    //湘中美的投票
    protected $itemName = 'x200623';
    protected $prod = 'cdnn';   // (cdnn / wx)

    const END_TIME = '2020-07-24 23:59:59';


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
                'vote_num' => 2
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
        $user = new User();
        $week = $user->getWeek();
        if ($week < 1) {
            $week = 1;
        }
        if ($week > 4) {
            $week = 4;
        }
        //获取所有参赛舞团
        $programs = Program::where($where)->orderBy('number', 'asc')->get();
        $programsList = Program::orderBy('poll_' . $week, 'desc')->orderBy('updated_at', 'asc')->get();
        foreach ($programsList as $k => $v) {
            for ($i = 0; $i < count($programs); $i++) {
                if ($v['id'] == $programs[$i]['id']) {
                    $programs[$i]['rank'] = $k + 1;
                
           
            if (strstr($programs[$i]['images'], '|')) {
              $programs[$i]['images'] = explode('|', $programs[$i]['images']);
            }else {
                $programs[$i]['images'] = [$programs[$i]['images']];
            }
}
}
        }

        $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        return $this->returnJson(1, "查询成功", [
            'prefix' => $prefix,
            'programs' => $programs,
            'week' =>$week
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
        if ($voteNums = VoteLog::where('user_id', $user->id)->where('program_id', $request->program_id)->whereBetween('created_at', $this->getToday())->count() >= 2) {
            return response()->json(['error' => '每个节目最多投2票'], 422);
        }
        $program = Program::find($request->program_id);
        if (!$program) {
            return response()->json(['error' => '您投票的节目不存在'], 422);
        }
        $data = ['20200704', '20200711', '20200718', '20200725'];
        $today = date('Ymd');
//        $today = '20200704';
        if (in_array($today,$data)) {
            return response()->json(['error' => '今日统计票数，不能投票'], 422);
        }
        $week = $user->getWeek();
        if ($week < 1) {
            $week = 1;
        }
        if ($week > 4) {
            $week = 4;
        }
        $pollName = 'poll_'.$week;
        $program->fill([$pollName => $program[$pollName]+1]);
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
            $poll = $program[$pollName];
            return $this->returnJson(1, "投票成功", [
                $pollName => $poll
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
        ], [
            'number.required' => '编号不能为空',
            'number.regex' => '编号只能是数字',
            'program.required' => '名字不能为空',
            'program.max' => '名字太长(最长30个字)',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $file = $request->file('images');
        if (count($file) > 9) {
            return response()->json(['error' => '最多上传9张图片'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        foreach ($request->file('images') as $value) {
            if (in_array(strtolower($value->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                if (!$images[] = $value->store('/wx_items/' . $this->itemName, $storeDriver)) {
                    return response()->json(['error' => '上传错误,请重新上传'], 422);
                }
            } else {
                return response()->json(['error' => '上传错误,只能上传图片类型'], 422);
            }
            $program['images'] = implode('|', $images);
        }
        $program = Program::create($program);
        $newProgram = Program::find($program->id);
        return $this->returnJson(1, "添加成功", [
            'newProgram' => $newProgram,
        ]);
    }
}
