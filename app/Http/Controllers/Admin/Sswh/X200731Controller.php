<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200731Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200731\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200731Controller extends Controller
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
        $exportUrl = asset('/vlvl/x200731/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:ycsjss20200727');
        return view('sswh.sswhAdmin.x200731', ['title' => '世纪山水·投票', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData,'total'=>$total]);
    }

    public function export()
    {
        return Excel::download(new X200731Export(), '世纪山水·投票.xlsx');
    }
}
