<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201109\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201109Controller extends Common
{
    //大桥老味道
    protected $itemName = 'x201109';
    const END_TIME = '2020-11-10 23:59:59';



    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        $user->reserves;
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }

    /**
     * 提交预约
     * @param  Request  $request
     * @return JsonResponse
     */
    public function reserve(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '预约时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        if ($user->name) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        $reserve = $request->all();
        $validator = Validator::make($reserve, [
            'name' => 'required',
            'mobile' => 'required',
            'gender' => 'required',
            'id_num' => 'required|max:18',
            'age' => 'required',
            'comment' => 'required',
            'reason' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
            'gender.required' => '性别不能为空',
            'id_num.required' => '身份证号不能为空',
            'id_num.max' => '身份证号不能查过18位',
            'age.required' => '年龄不能为空',
            'comment.required' => '心目中的老味道不能为空',
            'reason.required' => '加入吃货团的理由不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($reserve['image']) {
            $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
            if (!$path = $request->image->store('/wx_items/'.$this->itemName, $storeDriver)) {
                return response()->json(['error' => '上传错误,请重新上传'], 422);
            }
            $reserve['image'] = $path;
        }

        $user->fill($reserve);
        $user->save();
        $user->reserves;
        return Helper::Json(1, '报名成功', ['user' => $user]);
    }
}
