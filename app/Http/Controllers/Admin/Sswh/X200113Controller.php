<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200113Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200113\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200113Controller extends Controller
{
    public function index(Request $request)
    {
        $nameOrCode = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::where('image','!=','')->when(!preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
        return $query->where('name', 'like', '%' . $nameOrCode . '%');
    })
        ->when(preg_match("/^\d{11}$/", $nameOrCode), function ($query) use ($nameOrCode) {
            return $query->where('phone', '=', $nameOrCode);
        })
        ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        if ($request->order) {
            $paginator = $query->orderBy('updated_at', 'desc')->paginate(15);
        }else {
            $paginator = $query->orderBy('polls', 'desc')->paginate(15);
        }


        $exportUrl = asset('/vlvl/x200113/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:y_20200114');
        return view('sswh.sswhAdmin.x200113', [
            'title' => '湘中年味照片征集',
            'paginator' => $paginator,
            'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData,
            'imgPrefix' =>'https://wx.sanshanwenhua.com/statics/'
        ]);
    }

    public function export()
    {
        return Excel::download(new X200113Export, '湘中年味照片征集.xlsx');
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

    /**
     * 拉黑
     * @param Request $request
     * @return mixed
     */
    public function blackList(Request $request)
    {
        $user = User::where('id',$request->id)->first();
        $user->status = 1;
        $user->save();
        return 1;
    }
}
