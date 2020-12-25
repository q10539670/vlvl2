<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201224aExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201224a\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201224aController extends Controller
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
        $exportUrl = asset('/vlvl/x201224a/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jm201224');
        return view('sswh.sswhAdmin.x201224a', [
            'title' => '金茂留言', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201224aExport(), '金茂留言.xlsx');
    }

//
}

