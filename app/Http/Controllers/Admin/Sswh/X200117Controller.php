<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200117Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200117\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200117Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
        return $query->where('name', 'like', '%' . $nameOrCode . '%');
    })
        ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('phone', '=', $nameOrCode);
        })
        ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);

        $exportUrl = asset('/vlvl/x200117/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss20200117');
        return view('sswh.sswhAdmin.x200117', [
            'title' => '中国中铁·世纪山水',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' =>'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X200117Export, '中国中铁·世纪山水抽奖参与名单.xlsx');
    }
}
