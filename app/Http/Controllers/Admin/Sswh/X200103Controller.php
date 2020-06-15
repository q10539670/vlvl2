<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200103Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X200103\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200103Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
//        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
//            return $query->where('name', 'like', '%' . $nameOrCode . '%');
//        })
//            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
//                return $query->where('phone', '=', $nameOrCode);
//            })
//            ->when($status != '', function ($query) use ($status) {
//                return $query->where('status', $status);
//            });
        $paginator = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->paginate(10);
        $exportUrl = asset('/vlvl/x200103/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200102');
        return view('sswh.sswhAdmin.x200103', ['title' => '大桥·团年饭购物', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200103Export, '大桥·团年饭购物.xlsx');
    }
}
