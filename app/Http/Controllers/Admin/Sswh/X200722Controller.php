<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200722Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200722\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200722Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nickname = $request->input('nickname');
        $query = User::when($nickname != '', function ($query) use ($nickname) {
            return $query->where('nickname', 'like', '%'.$nickname.'%');
        });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200722/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:dq20200722');
        return view('sswh.sswhAdmin.x200722', ['title' => '大桥·龙虾节_猜拳游戏', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200722Export(), '大桥·龙虾节_猜拳.xlsx');
    }
}
