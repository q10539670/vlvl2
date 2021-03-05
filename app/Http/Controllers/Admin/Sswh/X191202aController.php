<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191202aExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X191202a\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191202aController extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when( $nameOrCode, function ($query) use ($nameOrCode) {
            return $query->where('nickname', 'like', '%' . $nameOrCode . '%');
        })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x191202a/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:lh20191126');
        return view('sswh.sswhAdmin.x191202a', ['title' => '奥特莱斯抽奖H5', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191202aExport(), '奥特莱斯抽奖参与名单.xlsx');
    }
}
