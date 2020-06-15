<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200109Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200109\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200109Controller extends Controller
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
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(20);

        $exportUrl = asset('/vlvl/x200109/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:bs20200107');
        return view('sswh.sswhAdmin.x200109', ['title' => '百事可乐新年抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200109Export, '百事可乐新年抽奖.xlsx');
    }
}
