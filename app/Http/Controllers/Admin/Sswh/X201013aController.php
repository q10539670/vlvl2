<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201013aExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201013a\User;
use App\Models\Sswh\X201013a\Reserve;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201013aController extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = Reserve::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
        $paginator = $query->orderBy('reserve_date', 'asc')->orderBy('reserve_time', 'asc')->paginate(15);
        $exportUrl = asset('/vlvl/x201013a/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:bs201013');
        return view('sswh.sswhAdmin.x201013a', [
            'title' => '百事可乐·盖念店预约', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201013aExport(), '百事可乐·盖念店预约名单.xlsx');
    }


}
