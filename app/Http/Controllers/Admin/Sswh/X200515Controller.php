<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200515Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200515\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200515Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $week = $request->input('week');
        $user = new User();
        $nowWeek = $user->getWeek();
        if ($week == 0  || $week == '') {
            $scoreName = 'score_'. $nowWeek;
        }else {
            $scoreName = 'score_'. $week;
        }
        $query = User::when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('name', 'like', '%' . $nameOrCode . '%');
        })
            ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', '=', $nameOrCode);
            });


        if ($request->input('order') || $week != '') {
            $paginator = $query->where($scoreName,'!=',0)->orderBy($scoreName, 'asc')->orderBy('updated_at', 'asc')->paginate(15);
        } else {
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        }
        $exportUrl = asset('/vlvl/x200515/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:mh20200515');
        return view('sswh.sswhAdmin.x200515', [
            'title' => '美好置业',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'nowWeek' => $nowWeek
        ]);
    }

    public function export()
    {
        return Excel::download(new X200515Export, '美好置业.xlsx');
    }
}
