<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200629Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X200629\User;
use App\Models\Jyyc\X200629\Advise;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200629Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = Advise::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
            return $query->where('phone', '=', $nameOrPhone);
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('name', 'like', '%' . $nameOrPhone . '%');
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200629/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200629');
        return view('sswh.sswhAdmin.x200629', ['title' => '宜昌中心·建议收集', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200629Export(), '宜昌中心·建议收集.xlsx');
    }
}
