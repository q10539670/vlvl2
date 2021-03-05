<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200413Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200413\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200413Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);

        $exportUrl = asset('/vlvl/x200413/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss20200413');
        return view('sswh.sswhAdmin.x200413', [
            'title' => '中国中铁·世纪山水_全民砍房',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200413Export, '中国中铁·世纪山水_全民砍房.xlsx');
    }
}
