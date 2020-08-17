<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200817Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X200817\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200817Controller extends Controller
{
    protected $item = 'x200817';

    public function index(Request $request)
    {
        $status = $request->input('status');
        $nameOrPhone = $request->input('nameOrPhone');
        $prizeId = $request->input('prize_id');
        $query = User::when($status != '', function ($query) use ($status) {
            return $query->where('status', $status);
        })
            ->when($prizeId != '', function ($query) use ($prizeId) {
                return $query->where('prize_id', $prizeId);
            })
            ->when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('phone', '=', $nameOrPhone);
            })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('name', 'like', '%'.$nameOrPhone.'%');
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200817/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200106');

        return view('sswh.sswhAdmin.x200817', [
            'title' => '金地华中·第六届纳凉音乐节抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData
        ]);
    }

    public function export()
    {
        return Excel::download(new X200817Export, '金地华中·第六届纳凉音乐节参与名单.xlsx');
    }




    /*
     * 获取当前抽奖轮数
     */
    public function round(Request $request)
    {
        if ($request->zc != 'x200817r') {
            return redirect("https://www.baidu.com");
        }
        $round = User::getRound();
        return view('sswh.sswhAdmin.x200817r', ['title' => '金地华中·第六届纳凉音乐节抽奖', 'round' => $round]);
    }

    public function setRound(Request $request)
    {
        $round = $request->round;
        $res = User::setRound($round);
        if ($res && $round == 0) {
            return '停止';
        } else {
            return '开启';
        }
    }
}
