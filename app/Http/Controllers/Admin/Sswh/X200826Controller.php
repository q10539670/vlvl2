<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200826Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200826\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200826Controller extends Controller
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
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200826/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:hqc20200826');
        return view('sswh.sswhAdmin.x200826', [
            'title' => '华侨城', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData,'total'=>$total
        ]);
    }

    public function export()
    {
        return Excel::download(new X200826Export, '华侨城.xlsx');
    }
}
