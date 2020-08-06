<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200806\Images;
use App\Models\Sswh\X200806\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X200806Controller extends Common
{
    //楚天地产·我爱我家  上传
    protected $itemName = 'x200806';
    protected $prod = 'cdnn';   // (cdnn / wx)

    const END_TIME = '2021-08-31 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
//            //查询总表
//            $info = $this->searchSswhUser($request);
//            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create(['openid' => $openid]);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /**
     * 上传
     * @param  Request  $request
     * @return
     */
    public function upload(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:'.(1024 * 6),
        ], [
            'image.required' => '上传图片不能为空',
            'image.image' => '上传类型只能是图片',
            'image.max' => '图片大小不能超过6M',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$request->file('image')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        if (!$path = $request->image->store('/wx_items/'.$this->itemName, $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        $image = [
            'user_id' => $user->id,
            'images' => $path,
        ];
        $images = Images::create($image);
        $user->img_upload_num++;
        $user->save();
        return $this->returnJson(1, '上传小票成功', ['images' => $images]);


    }

    /*
     * 提交信息
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'phone.required' => '电话不能为空',
            'name.required' => '名字不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /**
     * 获取所有照片
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function images(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $perPage = $request->input('per_page') != '' ? $request->input('per_page') : 6;
        $currentPage = $request->input('current_page') != '' ? $request->input('current_page') : 1;
        $total = Images::count();
        $totalPage = ceil($total / $perPage); //总页数
        $images = Images::orderBy('created_at', 'desc')->offset($perPage * ($currentPage - 1))->limit($perPage)->get();
        $urlPrefix = 'https://'.$this->prod.'.sanshanwenhua.com/statics/';
        return Helper::Json(1, '查询成功', [
            'url_prefix' => $urlPrefix,
            'per_page'=>$perPage,
            'current_page' =>$currentPage,
            'total'=> $total,
            'total_page'=>$totalPage,
            'images' => $images
            ]);
    }
}
