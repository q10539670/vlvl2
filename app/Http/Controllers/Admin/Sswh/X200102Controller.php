<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200102Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jyyc\X200102\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200102Controller extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = User::when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x200102/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200102');
        return view('sswh.sswhAdmin.x200102', ['title' => '宜昌中心·天宸府现金抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200102Export, '宜昌中心·天宸府--招商音乐会.xlsx');
    }
}
