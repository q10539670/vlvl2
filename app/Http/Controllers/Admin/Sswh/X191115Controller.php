<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191115Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X191115\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191115Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('phone','=', $nameOrPhone);
        })
        ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('name','like','%'.$nameOrPhone.'%');
        })
        ->where('phone','!=', '');
        ;
        $paginator = $query->paginate(10);
        $exportUrl = asset('/vlvl/x191115/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:k_20191008');
        return view('sswh.sswhAdmin.x191115',['title'=>'湘中美的置业报名','paginator' => $paginator, 'exportUrl' =>$exportUrl, 'redisShareData' => $redisShareData]);
    }
    public function export()
    {
        return Excel::download(new X191115Export(), '湘中美的置业报名名单.xlsx');
    }
}
