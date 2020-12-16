<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201216\User;
use App\Models\Sswh\X201216\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201216Controller extends Common
{
    //保利
    protected $itemName = 'x201216';
    const END_TIME = '2021-12-07 23:59:59';


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

    public function verify(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '报名时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->code) {
            return response()->json(['error' => '您已通过验证'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':verify', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ], [
            'code.required' => '验证码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if (!$code = Code::where('code', $request->input('code'))->where('status', 0)->first()) {
            return response()->json(['error' => '验证码错误或已被使用'], 422);
        }
        $code->status = 1;
        $user->code = $request->input('code');
        $code->save();
        $user->save();
        return Helper::Json(1, '验证通过');

    }

    /**
     * 提交报名
     * @param  Request  $request
     * @return JsonResponse
     */
    public function apply(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '报名时间截止'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName.':apply', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        if ($user->code) {
            return response()->json(['error' => '您未通过验证'], 422);
        }
        if ($user->name) {
            return response()->json(['error' => '不要重复提交报名'], 422);
        }
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'mobile' => 'required',
            'company' => 'required',
            'type' => 'required',
            'house' => 'required',
            'pay' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
            'company.required' => '所属公司不能为空',
            'type.required' => '意向房源不能为空',
            'house.required' => '意向户型不能为空',
            'pay.required' => '时候交齐2成首付不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill($data);
        $user->save();
        $user->reserves;
        return Helper::Json(1, '登记成功', ['user' => $user]);
    }

    public function generateRandomCode()
    {
        Code::getUniqueCode(6,50);
        return
    }
}
