<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191125Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X191125\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191125Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{6}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('prize_code', '=', $nameOrCode);
        })
            ->when(!preg_match("/^\d{6}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('phone', 'like', '%' . $nameOrCode . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x191125/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:lh20191126');
        return view('sswh.sswhAdmin.x191125', ['title' => '长沙·洋湖天街感恩节', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191125Export, '长沙·洋湖天街参与名单.xlsx');
    }

    /**
     * 核销
     * @param $id
     */
    public function verification(Request $request)
    {
        $prize_code = $request->input('prize_code');
        $id = $request->input('id');
        $user = User::find($id);
        if ($user->prize_code == $prize_code) {
            $user->verification++;
            $user->verification_at = now()->toDateTimeString();
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

}
