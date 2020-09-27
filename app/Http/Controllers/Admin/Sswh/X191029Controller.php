<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191029Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X191029\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191029Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $banquetId = $request->input('banquet_id');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($banquetId != '', function ($query) use ($banquetId) {
                return $query->where('banquet_id', $banquetId);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x191029/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:whyz191029');
        return view('sswh.sswhAdmin.x191029', [
            'title' => '武汉院子', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X191029Export(), '武汉院子名单.xlsx');
    }


}
