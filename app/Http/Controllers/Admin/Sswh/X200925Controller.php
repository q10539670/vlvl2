<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200925Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200925\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200925Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $prizeId = $request->input('prize_id');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($prizeId != '', function ($query) use ($prizeId) {
                return $query->where('prize_id', $prizeId);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200925/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:md20200925');
        return view('sswh.sswhAdmin.x200925', [
            'title' => '美的抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X200925Export(), '美的抽奖中奖名单.xlsx');
    }


}
