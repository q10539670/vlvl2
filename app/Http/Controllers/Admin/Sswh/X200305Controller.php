<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200305Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200305\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200305Controller extends Controller
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
        if ($request->input('order')) {
            $paginator = $query->where('likes','!=',0)->orderBy('likes', 'desc')->orderBy('updated_at', 'asc')->paginate(15);
        }else {
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);

        }

        $exportUrl = asset('/vlvl/x200305/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:md20200305');
        return view('sswh.sswhAdmin.x200305', [
            'title' => '美的业主专宠',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200305Export, '美的业主专宠.xlsx');
    }
}
