<?php

namespace App\Http\Controllers\Admin\Jctj;

use App\Exports\X191028Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jctj\X191028\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191028Controller extends Controller
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
                return $query->where('truename', 'like', '%' . $nameOrPhone . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', '=', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x191028/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:lyw20191031');
        return view('sswh.jctj.x191028', ['title' => '江宸天街·天街潮尚之旅', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191028Export, '江宸天街·天街潮尚之旅抽奖名单.xlsx');
    }
}
