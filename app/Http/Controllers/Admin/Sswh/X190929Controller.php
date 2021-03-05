<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X190929Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X190929\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X190929Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('phone','=', $nameOrPhone);
        })
        ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query)use($nameOrPhone){
            return $query->where('truename','like','%'.$nameOrPhone.'%');
        })
        ->when($status != '',function($query)use($status){
            return $query->where('status','=', $status);
        });
        $paginator = $query->orderBy('created_at','desc')->paginate(10);

        $exportUrl = asset('/vlvl/x190929/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:e_20190929');
        return view('sswh.sswhAdmin.x190929',['title'=>'保利香颂国庆狂欢周幸运转转转','paginator' => $paginator, 'exportUrl' =>$exportUrl, 'redisShareData' =>$redisShareData]);
    }
    public function export()
    {
        return (new X190929Export)->download('抽奖名单.xlsx');
    }
}
