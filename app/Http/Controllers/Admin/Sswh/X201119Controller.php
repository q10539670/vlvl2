<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201119Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201119\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201119Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x201119/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:whyz201119');
        return view('sswh.sswhAdmin.x201119', [
            'title' => '武汉院子报名', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201119Export(), '武汉院子报名名单.xlsx');
    }


}
