<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\Bt190902\User;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Bt190902\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Bt190902Controller extends Controller
{

    const SUBMIT_TIME = '2019-09-08 23:59:59';
    const VOTE_START = '2019-09-09 00:00:00';
    const END_TIME = '2019-09-13 23:59:59';

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
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'num' =>1 ,
                'share_num'=>1
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
     * 获取所有参赛队伍
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $where = function ($query) {
                    $query->where('truename', '!=', '');
        };
        $perPage = $request->has('per_page') ? $request->per_page : 15; //每页显示数量
        $currentPage = $request->has('current_page') ? $request->current_page : 1; //当前页
        $total = User::where($where)->count(); //总数量
        $totalPage = ceil($total / $perPage); //总页数
        $query = User::where($where);
        $query->orderBy('updated_at', 'asc');

        $items = $query->offset($perPage * ($currentPage -1))->limit($perPage)->get()->toArray();
        if (env('APP_ENV') == 'local') {
            $prefix = asset('/storage/').'/';
        }else{
            $prefix = 'https://'. $this->prod . '.sanshanwenhua.com/statics/';
        }
        return $this->returnJson(1, "查询成功", [
            'per_page'=>$perPage,
            'current_page' =>$currentPage,
            'total'=> $total,
            'total_page'=>$totalPage,
            'prefix' =>$prefix,
            'items' => $items,
        ]);
    }

    /*
     * 报名
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::SUBMIT_TIME)) {
            return response()->json(['error' => '报名时间已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'truename' => 'required',
            'phone' => 'required',
            'image' => 'required|image|max:' . (1024 * 6),
        ], [
            'truename.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'image.required' => '图片不能为空',
            'image.image' => '只能上传图片类型',
            'image.max' => '图片大小不能超过6M'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/a190902', $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $user->fill([
            'truename' => $request->truename,
            'phone' => $request->phone,
            'image' => $path
        ]);
        $user->save();

        return $this->returnJson(1, "报名成功", ['user'=>$user]);
    }

    /**
     * 投票
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request)
    {
        if (time() < strtotime(self::VOTE_START)) {
            return response()->json(['error' => '投票时间未开始，投票通道未开启'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用尽'], 422);
        }
        $targetUser = User::find($request->target_id);
        if (!$targetUser) {
            return response()->json(['error' => '您投票的用户不存在'], 422);
        } elseif ($targetUser['truename'] == '') {
            return response()->json(['error' => '您投票的用户暂未提交作品'], 422);
        }
        $targetUser->polls++;
        $targetUser = $targetUser->save();
        if ($targetUser) {
            $user->num--;
            $user->save();
            $log = [
                'user_id' => $user['id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'target_id' => $request->target_id
            ];
            VoteLog::create($log);
            $polls = User::find($request->target_id);
            $polls = $polls['polls'];
            return $this->returnJson(1, "投票成功", [
                'polls' =>$polls
                ]);
        }

    }

    /*
     * 分享
     */
    public function share(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->num++;
            $user->share_num--;
            $user->save();
        }

        return $this->returnJson(1, "分享成功");
    }
}

