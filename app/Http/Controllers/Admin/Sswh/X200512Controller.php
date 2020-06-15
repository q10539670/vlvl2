<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200512Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200512\Poll;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200512Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = Poll::where('polls','!=',0)->orderBy('polls', 'desc')->orderBy('updated_at', 'asc')->paginate(15);
        }else {
            $paginator = Poll::orderBy('updated_at', 'desc')->paginate(15);

        }
        $exportUrl = asset('/vlvl/x200512/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss20200514');
        return view('sswh.sswhAdmin.x200512', [
            'title' => '投票',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
        ]);
    }

    public function export()
    {
        return Excel::download(new X200512Export, '投票.xlsx');
    }
}
