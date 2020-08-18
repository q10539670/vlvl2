<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200818Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X200818\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200818Controller extends Controller
{
    protected $item = 'x200818';

    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::where('score','!=',0)->orderBy('score', 'desc')->orderBy('updated_at', 'asc')->paginate(10);
        }else {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(10);

        }
        $exportUrl = asset('/vlvl/x200818/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jdmusic0817');

        return view('sswh.sswhAdmin.x200818', [
            'title' => '金地华中·第六届纳凉音乐节_听歌识曲', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData
        ]);
    }

    public function export()
    {
        return Excel::download(new X200818Export, '金地华中·第六届纳凉音乐节_听歌识曲参与名单.xlsx');
    }
}
