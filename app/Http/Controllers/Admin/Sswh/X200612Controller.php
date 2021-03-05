<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200612Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200612\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200612Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::where('score','!=',0)->orderBy('score', 'desc')->orderBy('updated_at', 'asc')->paginate(15);
        }else {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(15);

        }
        $exportUrl = asset('/vlvl/x200612/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:p_20200611');
        return view('sswh.sswhAdmin.x200612', ['title' => '大桥·团年饭购物', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200612Export, '世纪山水.xlsx');
    }
}
