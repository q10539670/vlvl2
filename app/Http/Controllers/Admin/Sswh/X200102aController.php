<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200102aExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200102\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200102aController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = User::when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(20);

        $exportUrl = asset('/vlvl/x200102z/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:hw20191231');
        return view('sswh.sswhAdmin.x200102a', ['title' => '华为抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200102aExport, '华为抽奖.xlsx');
    }
}
