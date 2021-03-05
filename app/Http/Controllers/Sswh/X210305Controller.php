<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X210305\User;
use App\Models\Sswh\X210305\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class X210305Controller extends Common
{
    //中国中铁·世纪山水·天麓城
    protected $itemName = 'x210305';

    const SUBMIT_TIME = '2020-02-05 00:00:00';
    const START_TIME = '2020-02-05 00:00:00';
    const END_TIME = '2021-03-25 23:59:59';

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
            'prefix' => 'https://'.$this->prod.'.sanshanwenhua.com/statics/',
            'is_active_time' => (time() > strtotime(self::END_TIME) && time() < strtotime(self::START_TIME)) ? 0 : 1
        ]);
    }

    /**
     * 所有照片
     * @param  Request  $request
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
                    $query->where('name', 'like', '%'.$request->search.'%');
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
            $prefix = asset('/storage/').'/';
        } else {
            $prefix = 'https://'.$this->prod.'.sanshanwenhua.com/statics/';
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
        $detail = User::where('id', $request->id)->first();
        if ($detail->image == '') {
            return response()->json(['error' => '当前ID未上传图片'], 422);
        }
        $allUsers = User::where('image', '!=', '')->orderBy('polls', 'desc')->get()->toArray();
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
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '投稿时间还未开始'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '投稿时间已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if (!Helper::stopResubmit($this->itemName.':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->image != '') {
            return response()->json(['error' => '您已上传照片,请勿重复上传'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'slogan' => 'required',
            'image' => 'required|image|max:'.(1024 * 6),
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话号码不能为空',
            'image.required' => '图片不能为空',
            'slogan.required' => '宣言不能为空',
            'image.image' => '只能上传图片类型',
            'image.max' => '图片大小不能超过6M',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/x201208', $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $imageInfo = getimagesize($request->image);
        $user->fill([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'image' => $path,
            'slogan' => $request->slogan,
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'upload_at' => now()->toDateTimeString()
        ]);
        $user->save();

        return $this->returnJson(1, "报名成功",
            ['user' => $user, 'prefix' => 'https://'.$this->prod.'.sanshanwenhua.com/statics/']);
    }

    /**
     * 投票
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function vote(Request $request)
    {
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '投票未开始，投票通道未开启'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间已截止'], 422);
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
        } elseif ($targetUser['image'] == '') {
            return response()->json(['error' => '您投票的用户暂未提交作品'], 422);
        }
        if ($user->id == $request->target_id) {
            return response()->json(['error' => '自己不能给自己投票'], 422);
        }
        if (!Helper::stopResubmit($this->itemName.':vote', $user->id, 2)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $targetUser->polls++;
        $user->num--;
        $log = [
            'user_id' => $user['id'],
            'nickname' => $user['nickname'],
            'avatar' => $user['avatar'],
            'target_id' => $request->target_id
        ];
        //开启事务
        DB::beginTransaction();
        try {
            if (!$targetUser->save()) {
                throw new \Exception('数据库错误');
            }
            if (!$user->save()) {
                throw new \Exception('数据库错误');
            }
            if (!VoteLog::create($log)) {
                throw new \Exception('数据库错误');
            }
            DB::commit();
            $polls = User::find($request->target_id);
            $polls = $polls['polls'];
            return $this->returnJson(1, "投票成功", [
                'polls' => $polls
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '投票失败'], 422);
        }
    }
}
