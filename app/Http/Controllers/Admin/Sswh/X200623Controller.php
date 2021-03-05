<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200623Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200623\Program;
use App\Models\Sswh\X200623\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class X200623Controller extends Controller
{
    public function index(Request $request)
    {
        $program = $request->input('program');
        $week = $request->input('week');
        $user = new User();
        $nowWeek = $user->getWeek();
        if ($nowWeek < 1) {
            $nowWeek = 1;
        }
        if ($nowWeek > 4) {
            $nowWeek = 4;
        }
        if ($week == 0  || $week == '') {
            $pollName = 'poll_'. $nowWeek;
        }else {
            $pollName = 'poll_'. $week;
        }
        $query = Program::when($program, function ($query) use ($program) {
            return $query->where('program', 'like', '%' . $program . '%');
        });
        if ($request->input('order') || $week != '') {
            $paginator = $query->where($pollName,'!=',0)->orderBy($pollName, 'desc')->orderBy('updated_at', 'asc')->paginate(15);
        } else {
            $paginator = $query->orderBy('number', 'asc')->paginate(15);
        }
        $exportUrl = asset('/vlvl/x200623/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:mdzyb20200623');
        return view('sswh.sswhAdmin.x200623', [
            'title' => '美的投票',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'nowWeek' => $nowWeek
        ]);
    }


    public function export()
    {
        return Excel::download(new X200623Export(), '美的投票.xlsx');
    }
}
