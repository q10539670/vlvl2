<?php


namespace App\Http\Controllers\Sswh;


use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X210204\User;
use App\Models\Sswh\X210204\Images;
use App\Models\Sswh\X210204\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class X210204Controller extends Common
{
    //中铁·龙盘湖·世纪山水投票
    protected $itemName = 'x210204';

    const START_TIME = '2020-01-17 09:00:00';
    const END_TIME = '2021-02-18 23:59:59';

    protected $prod = 'cdnn';   //wx

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
                'num' => 10
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
     * 所有照片
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $perPage = $request->has('per_page') ? $request->per_page : 6; //每页显示数量
        $currentPage = $request->has('current_page') ? $request->current_page : 1; //当前页
        $where = function ($query) use ($request) {
            $query->where('image', '!=', '')->where('status',0);
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('id', $request->search);
                } else {
                    $query->where('name', 'like', '%'.$request->search.'%');
                }
            }
        };
        $total = Images::where($where)->count(); //总数量
        $totalPage = ceil($total / $perPage); //总页数
        if ($request->has('search') && ($request->search != '')) {
            if ($currentPage > $totalPage) {
                $currentPage = $totalPage;
            }
        }
        $query = Images::where($where);
        if ($request->order == 'news') {
            $query->orderBy('id', 'desc');
        } else {
            $query->orderBy('poll', 'desc')->orderBy('created_at', 'asc');
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
        $detail = Images::where('id', $request->id)->first();
        if ($detail->image == '' || $detail->status != 0) {
            return response()->json(['error' => '当前ID错误'], 422);
        }
        $allUsers = Images::where('image', '!=', '')->orderBy('poll','desc')->get()->toArray();
        $ranking = -1;
        foreach ($allUsers as $k => $v) {
            if ($v['id'] == $request->id) {
                $ranking = $k + 1;
            }
        }
        return $this->returnJson(1, "查询成功", ['detail' => $detail, 'ranking' => $ranking]);
    }

    /**
     * 投票
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function poll(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用尽'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':poll', $user->id, 3)) {
            return response()->json(['error' => '每3秒才能投一次票哦'], 422);
        }
        $image = Images::find($request->id);
        if (!$image) {
            return response()->json(['error' => '您投票的照片不存在'], 422);
        }
        //开启事务
        DB::beginTransaction();
        try {
            $image->poll+=13;
            if (!$image->save()) {
                throw new \Exception("数据库错误");
            }
            $log = Log::create([
                'images_id' => $image->id,
                'user_id' => $user->id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            if (!$log) {
                throw new \Exception("投票数据库错误");
            }
            $user->num--;
            if (!$user->save()) {
                throw new \Exception("用户数据库错误");
            }
            DB::commit();
            $poll = $image->poll;
            return $this->returnJson(1, "投票成功", [
                'poll' => $poll,
                'user' => $user]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '投票失败'], 422);
//            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
