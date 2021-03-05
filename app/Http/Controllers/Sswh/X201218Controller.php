<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201218\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201218Controller extends Common
{
    //报名
    protected $itemName = 'x201218';
    const END_TIME = '2025-12-07 23:59:59';

    /**
     * 提交报名
     * @param  Request  $request
     * @return JsonResponse
     */
    public function apply(Request $request)
    {
//        if (time() > strtotime(self::END_TIME)) {
//            return response()->json(['error' => '报名时间截止'], 422);
//        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'mobile' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user = User::create($data);
        return Helper::Json(1, '预约成功', ['user' => $user]);
    }
}
