<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200212Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200212\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200212Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::where('score','!=',0)->orderBy('score', 'asc')->orderBy('updated_at', 'asc')->paginate(15);
        }else {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(15);

        }

        $exportUrl = asset('/vlvl/x200212/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:md20200212');
        return view('sswh.sswhAdmin.x200212', [
            'title' => '美的情人节',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200212Export, '美的情人节.xlsx');
    }
}
