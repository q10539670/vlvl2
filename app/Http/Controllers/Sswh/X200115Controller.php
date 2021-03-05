<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200115\User;
use App\Models\Sswh\X200115\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200115Controller extends Common
{
    //长沙美的
    protected $itemName = 'x200115';

    const SUBMIT_TIME = '2020-01-23 23:59:59';
    const VOTE_START = '2020-01-24 00:00:00';
    const END_TIME = '2020-01-31 23:59:59';

    protected $prod = 'cdnn';

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
                'num' => 3,
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /**
     * 所有照片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $perPage = $request->has('per_page') ? $request->per_page : 6; //每页显示数量
        $currentPage = $request->has('current_page') ? $request->current_page : 1; //当前页
        $where = function ($query) use ($request) {
            $query->where('image', '!=', '');
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('id', $request->search);
                } else {
                    $query->where('name', 'like', '%' . $request->search . '%');
                }
            }
        };
        $total = User::where($where)->count(); //总数量
        $totalPage = ceil($total / $perPage); //总页数
        if ($request->has('search') && ($request->search != '')) {
            if ($currentPage > $totalPage) {
                $currentPage = $totalPage;
            }
        }
        $query = User::where($where);
        if ($request->order == 'news') {
            $query->orderBy('upload_at', 'desc');
        } else {
            $query->orderBy('polls', 'desc')->orderBy('upload_at', 'asc');
        }
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get()->toArray();
        if (env('APP_ENV') == 'local') {
            $prefix = asset('/storage/') . '/';
        } else {
            $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        }
        return $this->returnJson(1, "查询成功", [
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'prefix' => $prefix,
            'items' => $items,
        ]);
    }

    public function detail(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $detail = User::where('id',$request->id)->first();
        if ($detail->image == '') {
            return response()->json(['error' => '当前ID未上传图片'], 422);
        }
        $allUsers = User::where('image', '!=', '')->get()->toArray();
        $ranking = -1;
        foreach ($allUsers as $k => $v) {
            if ($v['id'] == $request->id) {
                $ranking = $k + 1;
            }
        }
        return $this->returnJson(1, "查询成功", ['detail' => $detail, 'ranking' => $ranking]);
    }

    /*
     * 上传照片
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::SUBMIT_TIME)) {
            return response()->json(['error' => '报名时间已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->image != '') {
            return response()->json(['error' => '您已上传照片,请勿重复上传'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'image' => 'required|image|max:' . (1024 * 6),
            'slogan'=>'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'image.required' => '图片不能为空',
            'image.image' => '只能上传图片类型',
            'image.max' => '图片大小不能超过6M',
            'slogan.required' => '宣言不能为空',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/x200103', $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'slogan'=>$request->slogan,
            'image' => $path,
            'updated_at' => now()->toDateTimeString()
        ]);
        $user->save();

        return $this->returnJson(1, "报名成功", ['user' => $user, 'prefix' => 'https://' . $this->prod . '.sanshanwenhua.com/statics/']);
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
        } elseif ($targetUser['name'] == '') {
            return response()->json(['error' => '您投票的用户暂未提交作品'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':vote', $user->id, 2)) {
            return response()->json(['error' => '不要重复提交'], 422);
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
                'polls' => $polls
            ]);
        }
    }
}
