<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210126Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210126\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210126Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::where('image', '!=', '')->when(!preg_match("/^\d{11}$/", $nameOrCode),
            function ($query) use ($nameOrCode) {
                return $query->where('name', 'like', '%'.$nameOrCode.'%');
            })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        if ($request->order) {
            $paginator = $query->orderBy('upload_at', 'desc')->paginate(15);
        } else {
            $paginator = $query->orderBy('polls', 'desc')->paginate(15);
        }
        $total = User::count();
        $exportUrl = asset('/vlvl/x210126/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:tlc210126');
        return view('sswh.sswhAdmin.x210126', [
            'total' => $total,
            'title' => '中国中铁·世纪山水·天麓城',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' => 'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X210126Export, '中国中铁·世纪山水·天麓城·"年味"照片.xlsx');
    }

    /**
     * 删除照片
     * @param  Request  $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->fill(['image' => '', 'polls' => 0,'slogan' => '', 'upload_at' => NULL,'polls_bf' =>$user->polls]);
        $user->save();
        return 1;
    }
}
