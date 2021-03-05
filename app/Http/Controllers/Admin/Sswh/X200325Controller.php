<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200325Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jyyc\X200325\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200325Controller extends Controller
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

        $exportUrl = asset('/vlvl/x200325/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200106');
        return view('sswh.sswhAdmin.x200106a', ['title' => '宜昌中心·天宸府现金抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200106aExport, '宜昌中心·天宸府台州商会参与名单.xlsx');
    }
}
