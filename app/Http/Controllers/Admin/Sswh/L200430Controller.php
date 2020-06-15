<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\L200430Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\L200430\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class L200430Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $item = $request->input('item');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('truename', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
        ->when($item != '', function ($query) use ($item) {
        return $query->where('item_id', $item);
    });
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);

        $exportUrl = asset('/vlvl/l200430/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:whyz20200430');
        return view('sswh.sswhAdmin.l200430', [
            'title' => '武汉院子',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new L200430Export(), '武汉院子.xlsx');
    }
}
