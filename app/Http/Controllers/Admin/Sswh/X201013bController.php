<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201013bExport;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201013b\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201013bController extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x201013b/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:dq201013');
        return view('sswh.sswhAdmin.x201013b', [
            'title' => '大桥·徒步预约', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X201013bExport(), '大桥·徒步预约名单.xlsx');
    }


}
