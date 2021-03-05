<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201028Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X201028\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201028Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', $nameOrCode);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x201028/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc201029');
        return view('sswh.sswhAdmin.x201028', [
            'title' => '宜昌中心·万圣节', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201028Export(), '宜昌中心·万圣节参与名单.xlsx');
    }


}
