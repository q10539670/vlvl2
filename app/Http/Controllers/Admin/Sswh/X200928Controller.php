<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200928Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X200928\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200928Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $money = $request->input('money');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($money != '', function ($query) use ($money) {
                return $query->where('money', $money);
            })
        ;
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200928/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jyyc20200928');
        return view('sswh.sswhAdmin.x200928', [
            'title' => '宜昌中心·天宸府', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X200928Export(), '宜昌中心·天宸府名单.xlsx');
    }


}
