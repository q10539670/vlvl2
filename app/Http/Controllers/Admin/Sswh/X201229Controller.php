<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X201229Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X201229\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201229Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('mobile', '=', $nameOrCode);
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
                return $query->where('name', 'like', '%' . $nameOrCode . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);

        $exportUrl = asset('/vlvl/x201229/export');
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:whyz201229');
        return view('sswh.sswhAdmin.x201229', ['title' => '城开·泰禾武汉院子', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData,'total' => $total]);
    }

    public function export()
    {
        return Excel::download(new X201229Export, '城开·泰禾武汉院子中奖名单.xlsx');
    }

    /**
     * 核销
     * @param $id
     */
    public function verification(Request $request)
    {
        $code = $request->input('code');
        $id = $request->input('id');
        $user = User::find($id);
        if ($user->code == $code) {
            $user->code_status++;
            $user->code_at = now()->toDateTimeString();
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

}
