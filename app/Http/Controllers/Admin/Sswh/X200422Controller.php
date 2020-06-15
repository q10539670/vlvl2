<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200422Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X200422\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200422Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('nickname', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
//        dd($paginator);
        $exportUrl = asset('/vlvl/x200422/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200421');
        return view('sswh.sswhAdmin.x200422', [
            'title' => '均瑶·宜昌中心',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200422Export, '均瑶·宜昌中心.xlsx');
    }
}
