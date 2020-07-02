<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200701ExportUser;
use App\Helpers\Helper;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200701\User;
use App\Models\Sswh\X200701\Log;
use App\Models\Sswh\X200701\Images;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class X200701Controller extends Controller
{
    /**
     * 用户
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        $condition = $request->input('condition');
        $type = $request->input('type');
        $perPage = ($request->input('per_page') != '') ?$request->input('per_page'): 10;
        $currentPage = $request->input('current_page')!='' ?$request->input('current_page'): 1;
        $query = User::when(($type && $condition), function ($query) use ($condition, $type) {
            return $query->where($type, 'like', '%' . $condition . '%');
        });
        $query = $query->orderBy('created_at', 'desc');
        $total = $query->count();//获取查询总数
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $user) $user->images;
//        $exportUrl = asset('/vlvl/x200701/export_user');

        return Helper::Json(1, '用户获取成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     * 小票
     * @param Request $request
     * @return JsonResponse
     */
    public function ticket(Request $request)
    {
        $nickname = $request->input('nickname');
        $status = $request->input('status');
        $perPage = ($request->input('per_page') != '') ?$request->input('per_page'): 10;
        $currentPage = $request->input('current_page')!='' ?$request->input('current_page'): 1;
        $query = Images::when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
            ->when($nickname, function ($query) use ($nickname) {
                return $query->whereHas('user', function ($query) use ($nickname) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                });
            });
        $query = $query->orderBy('created_at', 'desc');
        $total = $query->count();//获取查询总数
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $image) $image->user;
//        $exportUrl = asset('/vlvl/x200701/export_images');
        return Helper::Json(1, '小票查询成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     * 中奖记录
     * @param Request $request
     * @return JsonResponse
     */
    public function prizeLog(Request $request)
    {
        $nickname = $request->input('nickname');
        $prizeId = $request->input('prize_id');
        $perPage = ($request->input('per_page') != '') ?$request->input('per_page'): 10;
        $currentPage = $request->input('current_page')!='' ?$request->input('current_page'): 1;
        $query = Log::when($prizeId, function ($query) use ($prizeId) {
            return $query->where('result_id', $prizeId);
        })
            ->when($nickname, function ($query) use ($nickname) {
                return $query->whereHas('user', function ($query) use ($nickname) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                });
            });
        $query = $query->orderBy('created_at', 'desc');
        $total = $query->count();//获取查询总数
        $currentPage = $currentPage ?? 1; //当前页
        $perPage = $perPage ?? 10; //每页数量
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $prize) $prize->user;
//        $exportUrl = asset('/vlvl/x200701/export_prize');
        return Helper::Json(1, '中奖记录查询成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     *审核
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function check(Request $request)
    {
        //检查信息
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:l20200701_hn_images,id',
            'status' => 'required|regex:/^[1-2]$/',
            'num' => 'required_if:status,1'
        ], [
            'id.required' => 'id不能为空',
            'id.exists' => 'id参数错误,该小票不存在',
            'status.required' => '状态不能为空',
            'status.regex' => '状态参数错误',
            'num.required_if' => '产品数量不能为空',
        ]);
        if ($validator->fails()) {
            return Helper::Json(-1, $validator->errors()->first());
        }
        $add_num = floor($request->num / 2);
        $image = Images::find($request->id);
        if ($image->status != 0) return Helper::Json(-1, '小票已被审核');
        $image->fill($request->all());
        $image->checked_at = now()->toDateTimeString();
        $image->add_num = $add_num;
        $result = $image->save();
        if ($request->status == 1) {
            $user = User::find($image->user_id);
            $user->prize_num += $add_num;
            $user->game_num += $add_num;
            $user->img_pass_num++;
            $user->save();
        }
        static $success = 0;
        for ($i=0;$i<$add_num;$i++) {
            Log::create([
                'user_id' => $image->user_id,
                'origin' => 2    //来源审核
            ]);
            $success++;
        }
        if ($success == $add_num && $result) {
            DB::commit();
            return Helper::Json(1, '小票审核成功', ['images' => $image]);
        } else {
            DB::rollBack();
            return Helper::Json(-1, '小票审核失败');
        }


    }

    /**
     * h5信息
     * @return JsonResponse
     */
    public function info()
    {
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200701');
        return Helper::Json(1, 'h5信息获取成功', ['redisShareData' => $redisShareData]);
    }

//    /*
//     * 下载用户数据表
//     */
//    public function exportUser()
//    {
//        return Excel::download(new X200701ExportUser(), '红牛·用户信息表.xlsx');
//    }
}
