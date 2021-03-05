<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201105Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201105\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201105Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::where('score', '!=', 0)->orderBy('score', 'desc')->orderBy('updated_at',
                'asc')->paginate(15);
        } else {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(15);

        }
        $exportUrl = asset('/vlvl/x201105/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss201105');
        return view('sswh.sswhAdmin.x201105', [
            'title' => '中国中铁·世纪山水', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => User::count()
        ]);
    }

    public function export()
    {
        return Excel::download(new X201105Export, '世纪山水.xlsx');
    }
}
