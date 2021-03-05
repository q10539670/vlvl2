<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191014Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X191014\Comment;
use App\Models\Sswh\X191014\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X191014Controller extends Controller
{
    protected $prod = 'wx';   //      wx/cdnn

    public function index(Request $request)
    {
        $code = $request->input('code');
        $verification = $request->input('verification');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{6}$/", $code), function ($query) use ($code) {
            return $query->where('prize_code', $code);
        })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            })
        ->when($verification != '', function($query) use ($verification) {
            return $query->where('verification', $verification);
        });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x191014/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:k_20191008');
        return view('sswh.sswhAdmin.x191014', ['title' => '金地·美食嘉年华', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191014Export, '金地·美食嘉年华用户名单.xlsx');
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
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function verify(Request $request)
    {
        $status = $request->input('status');
        $id = $request->input('id');
        $comm = Comment::find($id);
        $comm->status = $status;
        $comm->save();
        return 1;
    }

    public function del(Request $request)
    {
        $comm = Comment::find($request->input('id'));
        $comm->delete();
        return 1;
    }

    /**
     * 评论列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function comments(Request $request)
    {
        $comm = $request->input('comm');
        $status = $request->input('status');
        $query = Comment::when($comm, function ($query) use ($comm) {
            return $query->where('comment', 'like', '%' . $comm . '%');
        })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', $status);
            });
        $perPage = $request->has('per_page') ? $request->per_page : 10; //每页显示数量
        $currentPage = $request->has('current_page') ? $request->current_page : 1; //当前页
        $total = $query->count(); //总数量
//        $query = $query->orderBy('created_at', 'desc');
        $item = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get()->toArray();
        for ($i = 0; $i < count($item); $i++) {
            $item[$i]['images'] = explode('|', $item[$i]['images']);
        }
        if (env('APP_ENV') == 'local') {
            $prefix = asset('/storage') . '/';
        } else {
            $prefix = 'https://' . $this->prod . '.sanshanwenhua.com/statics/';
        }
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:k_20191008');
        $paginator = $query->paginate(10);
        return view('sswh.sswhAdmin.x191014_comm', [
            'title' => '金地·美食嘉年华',
            'paginator' => $paginator,
            'prefix' => $prefix,
            'redisShareData' => $redisShareData
        ]);
    }
}
