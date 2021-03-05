<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200823Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200823\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200823Controller extends Controller
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
        $exportUrl = asset('/vlvl/x200823/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jq20200823');
        return view('sswh.sswhAdmin.x200823', ['title' => '金桥璟园·预约', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200823Export(), '金桥璟园·预约.xlsx');
    }
}
