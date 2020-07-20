<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Jchn\X200701\Admin;
use App\Models\Jchn\X200701\User;
use App\Models\Jchn\X200701\Log;
use App\Models\Jchn\X200701\Images;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class X200701Controller extends Controller
{
    //红牛抽奖
    protected $itemName = 'x200701';
    const TYPE = 'test';

//    public function __construct()
//    {
//        $this->middleware('x200701', ['except' => ['register', 'login']]);
//    }

    /**
     * 后台管理员注册
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $name = $request->name;
        $username = $request->username;
        $password = $request->password;
        $check_password = $request->check_password;

        if (!$name || !$password || !$username) {
            return response()->json(['error' => '用户名、昵称或密码必填！']);
        }

        if ($check_password != $password) {
            return response()->json(['error' => '两次密码输入不一致！']);
        }

        $admin = Admin::where('username', $username)->first();
        if ($admin) {
            return response()->json(['error' => '用户名已被注册！']);
        }

        $password = Hash::make($password);
        $admin = Admin::create([
            'name' => $name,
            'username' => $username,
            'password' => $password
        ]);

        return Helper::Json(1, '注册成功', ['admin' => $admin]);
    }

    /**
     * 用户登录
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        if (!$username || !$password) {
            return response()->json(['error' => '用户名或密码填写错误！'], 422);
        }

        $admin = Admin::where('username', $username)->first();
        if (!$admin) {
            return response()->json(['error' => '此用户名不存在！']);
        }
        if ($admin->status != 1) {
            return response()->json(['error' => '此用户已被停用！']);
        }
        if (!Hash::check($password, $admin->password)) {
            return response()->json(['error' => '密码填写错误！']);
        }

        $credentials = request(['username', 'password']);
        if (!$token = auth('admins')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return Helper::Json(1, '登陆成功', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admins')->factory()->getTTL() * 60,
            'admin' => $admin
        ]);
    }

    /**
     * 用户
     * @param  Request  $request
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        $condition = $request->input('condition');
        $type = $request->input('type');
        $perPage = ($request->input('per_page') != '') ? $request->input('per_page') : 10;
        $currentPage = $request->input('current_page') != '' ? $request->input('current_page') : 1;
        $query = User::when(($type && $condition), function ($query) use ($condition, $type) {
            return $query->where($type, 'like', '%'.$condition.'%');
        });
        $query = $query->orderBy('created_at', 'desc');
        $total = $query->count();//获取查询总数
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $user) {
            $user->images;
        }
//        $exportUrl = asset('/vlvl/x200701/export_user');

        return Helper::Json(1, '用户获取成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     * 小票
     * @param  Request  $request
     * @return JsonResponse
     */
    public function ticket(Request $request)
    {
        $condition = $request->input('condition');
        $type = $request->input('type');
        $status = $request->input('status');
        $dateRange = $request->input('date_range');
        $msg = $request->input('msg_status');
        $dateRange = $dateRange != '' ? [$dateRange[0].' 00:00:00', $dateRange[1].' 23:59:59'] : '';
        $perPage = ($request->input('per_page') != '') ? $request->input('per_page') : 10;
        $currentPage = $request->input('current_page') != '' ? $request->input('current_page') : 1;
        $query = Images::when($status != '', function ($query) use ($status) {
            return $query->where('status', $status);
        })
            ->when($dateRange != '', function ($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })
            ->when($msg != '', function ($query) use ($msg) {
                return $query->where('msg_status', $msg);
            })
            ->when($condition != '', function ($query) use ($condition, $type) {
                return $query->whereHas('user', function ($query) use ($condition, $type) {
                    $query->where($type, 'like', '%'.$condition.'%');
                });
            });
        $query = $query->orderBy('created_at', 'desc');
        $total = $query->count();//获取查询总数
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $image) {
            $image->user;
            $image->admin;
        }
//        $exportUrl = asset('/vlvl/x200701/export_images');
        return Helper::Json(1, '小票查询成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     * 中奖记录
     * @param  Request  $request
     * @return JsonResponse
     */
    public function prizeLog(Request $request)
    {
        $condition = $request->input('condition');
        $type = $request->input('type');
        $status = $request->input('status') != '' ? $request->input('status') : [1, 2, 11, 21]; //默认查已中奖
        $prizeId = $request->input('result_id');
        $dateRange = $request->input('date_range');
        $dateRange = $dateRange[0] != '' && $dateRange[1] != '' ? [
            $dateRange[0].' 00:00:00', $dateRange[1].' 23:59:59'
        ] : '';
        $perPage = ($request->input('per_page') != '') ? $request->input('per_page') : 10;
        $currentPage = $request->input('current_page') != '' ? $request->input('current_page') : 1;
        $query = Log::when($prizeId, function ($query) use ($prizeId) {
            return $query->whereIn('result_id', $prizeId);
        })
            ->when($dateRange != '', function ($query) use ($dateRange) {
                return $query->whereBetween('prized_at', $dateRange);
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->whereIn('status', $status);
            })
            ->when($condition != '', function ($query) use ($type, $condition) {
                return $query->whereHas('user', function ($query) use ($type, $condition) {
                    $query->where($type, 'like', '%'.$condition.'%');
                });
            });
        $query = $query->orderBy('prized_at', 'desc');
        $total = $query->count();//获取查询总数
        $currentPage = $currentPage ?? 1; //当前页
        $perPage = $perPage ?? 10; //每页数量
        $items = $query->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $paginator = new Paginator($items, $total, $perPage, $currentPage);
        foreach ($paginator as $prize) {
            $prize->user;
        }
        foreach ($paginator as $prize) {
            $prize->image;
        }
//        $exportUrl = asset('/vlvl/x200701/export_prize');
        return Helper::Json(1, '中奖记录查询成功', [
            'paginator' => $paginator,
//            'exportUrl' => $exportUrl,
        ]);
    }

    /**
     *审核
     * @param  Request  $request
     * @param $id
     * @return JsonResponse
     */
    public function check(Request $request)
    {
        //检查信息
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:l20200701_hn_images,id',
            'status' => 'required|regex:/^[1-2]$/',
            'num' => 'required_if:status,1'
        ], [
            'id.required' => 'id不能为空',
            'id.exists' => 'id参数错误,该小票不存在',
            'status.required' => '状态不能为空',
            'status.regex' => '状态参数错误',
            'num.required_if' => '产品数量不能为空',
        ]);
        if (!$image = Images::find($request->id)) {
            return Helper::Json(-1, '该小票不存在');
        }
        if ($image->status != 0) {
            return Helper::Json(-1, '小票已被审核');
        }
        if ($validator->fails()) {
            return Helper::Json(-1, $validator->errors()->first());
        }

        $image->status = $request->status;
        $image->checked_at = now()->toDateTimeString();
        $image->admin_id = $request->user->id;
        $user = User::find($image->user_id);
        $log = Log::where('origin', 1)->where('origin_image_id', $request->id)->where('status', 11)->first();
        if ($request->status == 1) {
            if ($request->num == 1) {
                $add_num = 0;
            } else {
                $add_num = floor($request->num / 2) - 1;
            }
            $image->add_num = $add_num;
            $image->num = $request->num;
            $user->game_num += $add_num;
            $user->img_pass_num++;
            static $success = 0;
            DB::beginTransaction();
            for ($i = 0; $i < $add_num; $i++) {
                Log::create([
                    'user_id' => $image->user_id,
                    'origin' => 2,    //来源审核
                    'origin_image_id' => $image->id
                ]);
                $success++;
            }
            if ($log) {
                $log->status = 1;
                $log->save();
            }
            $user->save();
            $image->save();
            if ($success == $add_num && $user->save() && $image->save()) {
                DB::commit();
                $image->admin;
                return Helper::Json(1, '小票审核成功', ['images' => $image]);
            } else {
                DB::rollBack();
                return Helper::Json(-1, '小票审核失败');
            }
        }
        if ($request->status == 2) {
            if ($log) {
                $redis = app('redis');
                $redis->select(12);
                $redisCountKey = 'wx:'.$this->itemName.':prizeCount:'.self::TYPE;
                $redis->hIncrBy($redisCountKey, $log->result_id, -1);  //超发 中奖数回退
                $log->status = 2;
                $log->result_id = 0;
                $log->result_name = '未中奖';
                $log->content = '小票审核未通过';
                $user->bingo_num--; //中奖次数+1
                $log->save();
            }
            $user->save();
            $image->save();
            $image->admin;
            if ($user->save() && $image->save()) {
                DB::commit();
                $image->admin;
                return Helper::Json(1, '小票审核成功', ['images' => $image]);
            } else {
                DB::rollBack();
                return Helper::Json(-1, '小票审核失败');
            }
        }
    }
    /**
     * h5信息
     * @return JsonResponse
     */
    public function info()
    {
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200701');
        return Helper::Json(1, 'h5信息获取成功', ['redisShareData' => $redisShareData]);
    }

//    /*
//     * 下载用户数据表
//     */
//    public function exportUser()
//    {
//        return Excel::download(new X200701ExportUser(), '红牛·用户信息表.xlsx');
//    }
}
