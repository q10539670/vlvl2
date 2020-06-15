<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200121Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200121\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200121Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('order')) {
            $paginator = User::orderBy('updated_at', 'desc')->paginate(15);
        }else {
            $paginator = User::orderBy('love', 'desc')->paginate(15);
        }

        $exportUrl = asset('/vlvl/x200121/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:bs20200119');
        return view('sswh.sswhAdmin.x200121', [
            'title' => '百事可乐新年',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' =>'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X200115Export, '百事可乐新年.xlsx');
    }

    /**
     * 删除照片
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        $user->fill(['image' => '','polls'=>0]);
        $user->save();
        return 1;
    }
}
