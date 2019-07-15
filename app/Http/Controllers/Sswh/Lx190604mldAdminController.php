<?php


namespace App\Http\Controllers\Sswh;


use App\Http\Controllers\Common\BaseV1Controller as Controller;
use Illuminate\Http\Request;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Lx190604mld\User as User;
use App\Models\Sswh\Lx190604mld\Post as Post;
use App\Models\Sswh\Lx190604mld\Message as Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Lx190604mldAdminController extends Controller
{

    /*
       * 用户提交信息列表11
       * */
    public function postList(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    //正则匹配电话号码
                    if(preg_match("/^1[34578]\d{9}$/", $request->search )){
                        $query->where('user_phone','like', '%'.$request->search.'%');
                    }else{
                        $query->where('user_id', $request->search);
                    }
                } else {
                    $query->where('user_name','like', '%'.$request->search.'%');
                }
            }

            if ($request->has('start_time') && ($request->start_time != '') && $request->has('end_time') && ($request->end_time != '')) {
                $query->whereBetween('created_at', array($request->start_time,$request->end_time));
            }

        };

//        DB::connection()->enableQueryLog(); // 开启查询日志
        $perPage = $request->has('per_page') ? $request->per_page : 15; //每页条数
        $currentPage = $request->has('current_page') ? $request->current_page : 1;  //当前页
        $total = Post::where($where)->count();       // 数据条数
        $totalPage = ceil($total / $perPage);   // 总页数
        $query = Post::where($where);
        $query->orderBy('created_at', 'desc'); //
        $items = $query->offset($perPage * ($currentPage - 1))->with("userInfo")
            ->limit($perPage)->get()->toArray();

//        print_r(DB::getQueryLog());die;
        return $this->returnJson(1,"用户提交列表 查询成功",[
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'items' => $items,
        ]);

    }



    /*
   * 用户反馈列表
   * */
    public function messageList(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('search') && ($request->search != '')) {
                if (is_numeric($request->search)) {
                    $query->where('user_id', $request->search);
                } else {
                    $query->where('content','like', '%'.$request->search.'%');
                }
            }

            if ($request->has('start_time') && ($request->start_time != '') && $request->has('end_time') && ($request->end_time != '')) {
                $query->whereBetween('created_at', array($request->start_time,$request->end_time));
            }

        };

//        DB::connection()->enableQueryLog(); // 开启查询日志
        $perPage = $request->has('per_page') ? $request->per_page : 15; //每页条数
        $currentPage = $request->has('current_page') ? $request->current_page : 1;  //当前页
        $total = Message::where($where)->count();       // 数据条数
        $totalPage = ceil($total / $perPage);   // 总页数
        $query = Message::where($where);
        $query->orderBy('created_at', 'desc'); //
        $items = $query->offset($perPage * ($currentPage - 1))->with("userInfo")
            ->limit($perPage)->get()->toArray();

//        print_r(DB::getQueryLog());die;
        return $this->returnJson(1,"用户反馈列表 查询成功",[
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'items' => $items,
        ]);

    }



}