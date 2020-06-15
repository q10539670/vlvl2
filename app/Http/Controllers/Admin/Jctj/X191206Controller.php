<?php

namespace App\Http\Controllers\Admin\Jctj;

use App\Exports\X191206Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jctj\X191206\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191206Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
            return $query->where('phone', '=', $nameOrPhone);
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('name', 'like', '%' . $nameOrPhone . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', '=', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x191206/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:tj20191209');
        return view('sswh.jctj.x191206', ['title' => '江宸天街·天街锦鲤', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191206Export, '江宸天街·天街锦鲤抽奖名单.xlsx');
    }
}
