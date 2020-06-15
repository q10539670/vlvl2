<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200106aExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jyyc\X200106a\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200106aController extends Controller
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

        $exportUrl = asset('/vlvl/x200106a/export');
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
