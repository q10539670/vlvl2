<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200716Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jyyc\X200716\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class X200716Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $nickname = $request->input('nickname');
        $query = User::when($nickname != '', function ($query) use ($nickname) {
            return $query->where('nickname', 'like', '%'.$nickname.'%');
        });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200716/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200716');
        return view('sswh.sswhAdmin.x200716', ['title' => '宜昌中心·生活服务启示录', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200716Export(), '宜昌中心·生活服务启示录.xlsx');
    }
}
