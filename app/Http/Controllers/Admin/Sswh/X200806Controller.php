<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200806\Images;
use App\Models\Sswh\X200806\User;
use Illuminate\Http\Request;

class X200806Controller extends Controller
{
    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $query = Images::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
            return $query->whereHas('user', function ($query) use ($nameOrPhone) {
                $query->where('phone','like', '%'.$nameOrPhone.'%');
            });
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->whereHas('user', function ($query) use ($nameOrPhone) {
                    $query->where('name', 'like', '%'.$nameOrPhone.'%');
                });
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        foreach ($paginator as $item) {
            $item->user;
        }
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:ct20200806');
        return view('sswh.sswhAdmin.x200806', ['title' => '楚天地产·上传', 'paginator' => $paginator, 'redisShareData' => $redisShareData]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        if (!$image = Images::find($id)) return Helper::Json(-1,'删除失败,该照片参数错误');
        $user = User::find($image->user_id);
        Images::destroy($id);
        $user->img_upload_num--;
        $user->save();
        return Helper::Json(1,'删除成功');
    }
}
