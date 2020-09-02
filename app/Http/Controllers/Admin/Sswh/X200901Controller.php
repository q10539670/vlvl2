<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200901Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X200901\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200901Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200901/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200901');
        return view('sswh.sswhAdmin.x200901', [
            'title' => '宜昌中心·臻享福利', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData
        ]);
    }

    public function export()
    {
        return Excel::download(new X200901Export(), '宜昌中心·臻享福利.xlsx');
    }


}
