<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X201013\User;
use App\Models\Sswh\X201013\Title;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X201013Controller extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = Title::when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        $total = User::count();
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:ycsjss201013');
        return view('sswh.sswhAdmin.x201013', [
            'title' => '宜昌中铁·世纪山水', 'paginator' => $paginator,
            'redisShareData' => $redisShareData,
            'total' => $total
        ]);
    }

    public function checkTitle(Request $request)
    {
        $title = Title::find($request->id);
        if (!$title->status == 0) {
            return 0;
        }
        $title->status = $request->status;
        $title->save();
        return 1;
    }
}
