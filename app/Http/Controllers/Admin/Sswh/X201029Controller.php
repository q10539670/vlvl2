<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201029Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201029\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201029Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::where('score','!=',0)->orderBy('score', 'asc')->orderBy('ranking_at', 'asc')->paginate(10);
        }else {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(10);

        }
        $exportUrl = asset('/vlvl/x201029/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss201028');

        return view('sswh.sswhAdmin.x201029', [
            'title' => '中国中铁·世纪山水_万圣节', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => User::count()
        ]);
    }

    public function export()
    {
        return Excel::download(new X201029Export, '中国中铁·世纪山水_万圣节参与名单.xlsx');
    }
}
