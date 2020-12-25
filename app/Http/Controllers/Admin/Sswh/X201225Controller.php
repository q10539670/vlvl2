<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201225Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Ctdc\X201225\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201225Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($status === '1', function ($query) use ($status) {
                return $query->where('status',  $status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x201225/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:ctdc201225');
        return view('sswh.sswhAdmin.x201225', [
            'title' => '楚天地产·答题抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201225Export(), '楚天地产·答题抽奖参与名单.xlsx');
    }

//夏天
}
