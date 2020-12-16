<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201216Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201216\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201216Controller extends Controller
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
            ->when($status === '1', function ($query) use ($status) {
                return $query->where('pay', '!=', 0);
            })
            ->when($status === '0', function ($query) use ($status) {
                return $query->where('pay',$status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x201216/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:bl201216');
        return view('sswh.sswhAdmin.x201204', [
            'title' => '保利', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201216Export(), '保利登记名单.xlsx');
    }

//夏天
}
