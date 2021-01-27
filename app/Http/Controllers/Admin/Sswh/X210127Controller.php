<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210127Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210127\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210127Controller extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = User::when($status != '', function ($query) use ($status) {
                return $query->where('prize', $status);
            });
            $paginator = $query->orderBy('created_at', 'desc')->paginate(15);
        $total = User::count();
        $exportUrl = asset('/vlvl/x210127/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:tlc210127');
        return view('sswh.sswhAdmin.x210127', [
            'total' => $total,
            'title' => '中国中铁·世纪山水·天麓城',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' => 'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X210127Export, '中国中铁·世纪山水·天麓城·年货游戏.xlsx');
    }
}
