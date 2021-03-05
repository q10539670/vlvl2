<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201201\Info;
use App\Models\Sswh\X201201\Order;
use App\Models\Sswh\X201201\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201201Controller extends Common
{
    //三山点餐
    protected $itemName = 'x201201';
    const END_TIME = '2021-11-10 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        if ($user->info_id != 0) {
            $user->info;
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /**
     * 验证
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '已截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':verify', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->info_id != 0) {
            return response()->json(['error' => '已提交验证'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$info = Info::where('name', $request->name)->where('mobile', $request->mobile)->where('status',
            0)->where('flag', 0)->first()) {
            return response()->json(['error' => '验证未通过,请检查姓名电话或联系人事'], 422);
        }
        $user->info_id = $info['id'];
        $user->save();
        $info->flag = 1;
        $info->save();
        $user->info;
        return Helper::Json(1, '验证成功', ['user' => $user]);
    }

    public function order(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '已截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->info_id === 0) {
            return response()->json(['error' => '未验证'], 422);
        }
        $info = Info::find($user->info_id);
        if ($info['status'] == 1) {
            return response()->json(['error' => '已离职,无法点餐'], 422);
        }
        if ($this->isWorkingDay() != 0 || date('H') < 17) {
            return response()->json(['error' => '点餐时间未到'], 422);
        }
        if (date('H') > 17) {
            return response()->json(['error' => '点餐时间已过'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':order', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (Order::where('user_id', $user->id)->where('status', 0)->whereBetween('created_at',
            $this->getToday())->first()) {
            return response()->json(['error' => '已经提交点餐'], 422);
        }
        Order::create([
            'user_id' => $user->id,
            'status' => 0
        ]);
        $user->w_num++;
        $user->m_num++;
        $user->t_num++;
        $user->save();
        return Helper::Json(1, '点餐成功');
    }

    public function cancel(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->info_id === 0) {
            return response()->json(['error' => '未验证'], 422);
        }
        if ($this->isWorkingDay() != 0 || date('H') < 17) {
            return response()->json(['error' => '点餐时间未到'], 422);
        }
        if (date('H') > 17) {
            return response()->json(['error' => '点餐时间已过'], 422);
        }
        if (!$order = Order::where('user_id', $user->id)->where('status', 0)->whereBetween('created_at',
            $this->getToday())->first()) {
            return response()->json(['error' => '未查询到点餐信息'], 422);
        }
        $order->status = 1;
        $order->save();
        $user->w_num--;
        $user->m_num--;
        $user->t_num--;
        $user->save();
        return Helper::Json(1, '取消成功');
    }

    public function list(Request $request)
    {
        $condition = $request->input('condition');
        $date = $request->input('date');
        $currentPage = $request->input('current_page'); //当前页
        $perPage = $request->input('per_page', 100);    //每页显示数量
        $query = Order::when(!preg_match("/^\d{11}$/", $condition), function ($query) use ($condition) {
            return $query->whereHas('user', function ($query) use ($condition) {
                $query->whereHas('info', function ($query) use ($condition) {
                    $query->where('name', 'like', '%' . $condition . '%');
                });
            });
        })
            ->when(preg_match("/^\d{11}$/", $condition), function ($query) use ($condition) {
                return $query->whereHas('user', function ($query) use ($condition) {
                    $query->whereHas('info', function ($query) use ($condition) {
                        $query->where('mobile', $condition);
                    });
                });
            });
        if ($date == 'all') {
            $query = $query->where('status', 0);
        } else {
            $query = $query->where('status', 0)->whereBetween('created_at', $this->formatDay($request->date));
        }

        $lists = self::paginator($query, $currentPage, $perPage);
        foreach ($lists as $list) {
            $list->user->info;
        }
        return Helper::Json(1, '查询成功', ['lists' => $lists]);
    }

    public function test()
    {
        return User::whereHas('info', function ($query) {
            $query->where('status', 0);
        })->get();
    }
}
