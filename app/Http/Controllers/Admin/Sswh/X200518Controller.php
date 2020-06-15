<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200518Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200518\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200518Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
        if ($request->input('order')) {
            $paginator = $query->where('score','!=',0)->orderBy('score', 'asc')->orderBy('updated_at', 'asc')->paginate(15);
        } else {
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        }
        $exportUrl = asset('/vlvl/x200518/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:mh20200515');
        return view('sswh.sswhAdmin.x200518', [
            'title' => '美好置业',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200518Export, '美好置业.xlsx');
    }
}
