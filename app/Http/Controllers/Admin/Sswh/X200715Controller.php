<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200715Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200715\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200715Controller extends Controller
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
        $exportUrl = asset('/vlvl/x200715/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss20200715');
        return view('sswh.sswhAdmin.x200715', ['title' => '中国中铁·世纪山水_积木游戏', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200715Export(), '中国中铁·世纪山水_积木游戏.xlsx');
    }
}
