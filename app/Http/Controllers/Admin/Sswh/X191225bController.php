<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191225bExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jyyc\X191225b\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191225bController extends Controller
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
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x191225b/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200106');
        return view('sswh.sswhAdmin.x191225b', ['title' => '宜昌中心·天宸府现金抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191225bExport, '宜昌中心·天宸府参与名单.xlsx');
    }
}
