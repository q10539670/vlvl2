<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200730Export;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200730\Contestant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200730Controller extends Common
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        $query = Contestant::when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        });
        if ($request->input('order')) {
            $paginator = $query->orderBy('poll', 'desc')->orderBy('updated_at', 'asc')->paginate(15);
        } else {
            $paginator = $query->orderBy('number', 'asc')->paginate(15);
        }
        $exportUrl = asset('/vlvl/x200730/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200730');
        return view('sswh.sswhAdmin.x200730', [
            'title' => '宜昌中心·物业女神_投票',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData
        ]);
    }


    public function export()
    {
        return Excel::download(new X200730Export(), '宜昌中心·物业女神投票.xlsx');
    }
}
