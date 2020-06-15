<?php

namespace App\Http\Controllers\Admin\Qwt;

use App\Exports\Qwt\Dx190925\Dx190925Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Qwt\Dx190925\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Dx190925Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('phone','=', $nameOrPhone);
        })
            ->when($status != '',function($query)use($status){
                return $query->where('status','=', $status);
            });
        $paginator = $query->orderBy('created_at','desc')->paginate(10);

        $exportUrl = asset('/vlvl/dx190925/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:k_20190926');
        return view('sswh.sswhAdmin.dx190925', ['title' => '电信·9月份抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new Dx190925Export, '电信·9月份抽奖名单.xlsx');
    }
}
