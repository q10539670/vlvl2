<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200424Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200424\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200424Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
        ->when($status != '', function ($query) use ($status) {
        return $query->where('prize_id', $status);
    });
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);

        $exportUrl = asset('/vlvl/x200424/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:dq20200424');
        return view('sswh.sswhAdmin.x200424', [
            'title' => '大桥·厨艺答题',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200424Export(), '大桥·厨艺答题.xlsx');
    }
}
