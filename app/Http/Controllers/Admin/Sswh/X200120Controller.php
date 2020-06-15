<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200120Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200120\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200120Controller extends Controller
{
    public function index(Request $request)
    {
        $paginator = User::orderBy('last_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200120/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:whyz20200120');
        return view('sswh.sswhAdmin.x200120', [
            'title' => '照片征集',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' => 'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X200120Export, '武汉院子照片征集.xlsx');
    }
}
