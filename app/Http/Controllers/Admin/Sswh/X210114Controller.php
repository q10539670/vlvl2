<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210114Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210114\User;
use App\Models\Sswh\X210114\Works;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210114Controller extends Controller
{
    public function index(Request $request)
    {
        $title = $request->input('title');
        $status = $request->input('status');
        $query = Works::when($title, function ($query) use ($title) {
            return $query->where('title', 'like', '%' . $title . '%');
        })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(15);
        foreach ($paginator as $item) {
            $item->user;
        }
        $exportUrl = asset('/vlvl/x200113/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:jm210114');
        return view('sswh.sswhAdmin.x210114', [
            'title' => '金茂社群',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' => 'https://wx.sanshanwenhua.com/statics/',
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X210114Export, '金茂社群.xlsx');
    }

    /**
     * 审核
     * @param Request $request
     * @return mixed
     */
    public function check(Request $request)
    {
        $user = Works::where('id', $request->id)->first();
        $user->status = $request->status;
        $user->save();
        return 1;
    }
}
