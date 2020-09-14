<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200914Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200914\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200914Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $itemId = $request->input('item_id');
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%'.$nameOrCode.'%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($itemId != '', function ($query) use ($itemId) {
                return $query->where('item_id', $itemId);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $exportUrl = asset('/vlvl/x200914/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jm20200914');
        return view('sswh.sswhAdmin.x200914', [
            'title' => '金茂武汉·报名', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X200914Export(), '金茂武汉·报名.xlsx');
    }


}
