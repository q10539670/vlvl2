<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210203Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210203\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210203Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrmobile');
        $itemId = $request->input('item_id');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('mobile', '=', $nameOrCode);
            })
            ->when($itemId != '', function ($query) use ($itemId) {
                return $query->where('item_id', $itemId);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x210203/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jm210203');
        return view('sswh.sswhAdmin.x210203', [
            'title' => '金茂武汉·报名', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X210203Export(), '金茂武汉·报名.xlsx');
    }


}
