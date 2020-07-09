<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200708Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200708\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200708Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
            return $query->where('phone', '=', $nameOrPhone);
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('name', 'like', '%' . $nameOrPhone . '%');
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200708/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:zh20200708');
        return view('sswh.sswhAdmin.x200708', ['title' => '兰州·中海铂悦府', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200708Export(), '兰州·中海铂悦府.xlsx');
    }
}
