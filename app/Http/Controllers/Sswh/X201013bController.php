<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X201013b\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X201013bController extends Common
{
    //百事可乐·武汉百事--预约盖念店
    protected $itemName = 'x201013b';
    const END_TIME = '2020-10-23 23:59:59';


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
            return response()->json(['error' => '不要重复提交预约'], 422);
        }
        $reserve = $request->all();
        if (strlen($user->date) > 2 || $user->date == $request->date) {
            return response()->json(['error' => '您已预约'], 422);
        }

        if (date('d') > 17 && $request->date == 17) {
            return response()->json(['error' => '请预约24号活动时间'], 422);
        }
        $validator = Validator::make($reserve, [
            'name' => 'required',
            'mobile' => 'required',
            'gender' => 'required',
            'id_num' => 'required|max:18',
            'on_foot' => 'required',
            'date' => 'required',
            'comment' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'mobile.required' => '电话不能为空',
            'gender.required' => '性别不能为空',
            'id_num.required' => '身份证号不能为空',
            'id_num.max' => '身份证号不能查过18位',
            'on_foot.required' => '是否有徒步经验不能为空',
            'date.required' => '预约时间不能为空',
            'comment.required' => '身体状态自评不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($reserve['date'] == 17) {
            $reserve['comment_1'] = $reserve['comment'];
        } else {
            $reserve['comment_2'] = $reserve['comment'];
        }
        unset($reserve['comment']);
        if (($user->date == 17 && $reserve['date'] == 24) || ($user->date == 24 && $reserve['date'] == 17)) {
            $reserve['date'] = '17,24';
        }
        $user->fill($reserve);
        $user->save();
        $user->reserves;
        return Helper::Json(1, '预约提交成功', ['user' => $user]);
    }
}
