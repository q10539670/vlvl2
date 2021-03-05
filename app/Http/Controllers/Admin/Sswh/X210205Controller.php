<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X210205Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X210205\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X210205Controller extends Controller
{
    public function index(Request $request)
    {
        $prizeId = $request->input('prize_id');
        $status = $request->input('status');
        $nameOrMobile = $request->input('nameOrMobile');
        $yh = $request->input('yh');
        $ty = $request->input('ty');
        $query = User::when($prizeId != '', function ($query) use ($prizeId) {
            return $query->where('prize_id', $prizeId);
        })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!preg_match("/^\d{11}$/", $nameOrMobile), function ($query) use ($nameOrMobile) {
                return $query->where('name', 'like', '%'.$nameOrMobile.'%');
            })
            ->when(preg_match("/^\d{11}$/", $nameOrMobile), function ($query) use ($nameOrMobile) {
                return $query->where('mobile', '=', $nameOrMobile);
            })
            ->when($yh === '0', function ($query) use ($yh) {
                return $query->where('verification1_at',null);
            })
            ->when($yh ==='1', function ($query) use ($yh) {
                return $query->where('verification1_at', '!=', null);
            })
            ->when($ty === '0', function ($query) use ($ty) {
                return $query->where('verification2_at', null)->whereIn('prize_id',[1,2,3]);
            })
            ->when($ty === '1', function ($query) use ($ty) {
                return $query->where('verification2_at', '!=', null);
            })
            ;
        $total = User::count();
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x210205/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:sjss210207');
        return view('sswh.sswhAdmin.x210205', [
            'title' => '中国中铁·世纪山水答题领红包',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function export()
    {
        return Excel::download(new X210205Export(), '中国中铁·世纪山水答题领红包.xlsx');
    }

    public function verify(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $user = User::find($id);
        if ($type == 1) {
            $user->verification1_at = now()->toDateTimeString();
            $user->save();
            return ['code' => 1, 'time' =>$user->verification1_at];
        }else {
            $user->verification2_at = now()->toDateTimeString();
            $user->save();
            return ['code' => 1, 'time' =>$user->verification2_at];
        }

    }
}
