<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191220Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X191220\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191220Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{6}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('verification_code', '=', $nameOrCode);
        })
            ->when(!preg_match("/^\d{6}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('truename', 'like', '%' . $nameOrCode . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x191220/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:lh20191220');
        return view('sswh.sswhAdmin.x191220', ['title' => '长沙·洋湖天街', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191220Export, '长沙·洋湖天街中奖名单.xlsx');
    }

    /**
     * 核销
     * @param $id
     */
    public function verification(Request $request)
    {
        $verification_code = $request->input('verification_code');
        $id = $request->input('id');
        $user = User::find($id);
        if ($user->verification_code == $verification_code) {
            $user->verification++;
            $user->verification_at = now()->toDateTimeString();
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

}
