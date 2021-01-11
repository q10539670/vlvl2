<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210111Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Ctdc\X210111\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210111Controller extends Controller
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
            ->when($status !== '', function ($query) use ($status) {
                return $query->where('status',  $status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x210111/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:ctdc210111');
        return view('sswh.sswhAdmin.x210111', [
            'title' => '楚天地产·答题抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X210111Export(), '楚天地产·答题抽奖参与名单.xlsx');
    }

}
