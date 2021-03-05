<?php

namespace App\Http\Controllers\Admin\Qwt;

use App\Exports\X191212Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Qwt\X191212\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191212Controller extends Controller
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

        $exportUrl = asset('/vlvl/x191212/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yy_20191212');
        return view('sswh.sswhAdmin.x191212', ['title' => '电信·12月份抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191212Export(), '电信·12月份抽奖名单.xlsx');
    }
}
