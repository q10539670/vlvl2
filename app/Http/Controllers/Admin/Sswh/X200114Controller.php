<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200114Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200114\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200114Controller extends Controller
{
    //奥莱大富翁游戏
    protected $item = 'x200114';



    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = User::when($status != '', function ($query) use ($status) {
            return $query->where('status', $status);
        });
        $paginator = $query->orderBy('score', 'desc')->orderBy('seat', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200114/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:atls20200110');
        return view('sswh.sswhAdmin.x200114', ['title' => '奥特莱斯·大富翁游戏', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200114Export, '奥特莱斯·大富翁游戏.xlsx');
    }
}
