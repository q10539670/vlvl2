<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191008\Info;
use App\Models\Sswh\X191008\User;
use App\Models\Sswh\X191008\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191008Controller extends Common
{

    protected $itemName = 'x191008';

    const END_TIME = '2019-10-21 20:00:00';

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
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1
        ]);

    }

    /*
     * 提交信息
     * */
    public function post(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '报名已截止，请报名的参与者点击“我的奖品”查看中奖信息。'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->phone != '') {
            return response()->json(['error' => '您已提交信息,请勿重复提交'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'truename' => 'required',
            'id_num' => 'required|unique:x191008_user',
            'phone' => 'required|unique:x191008_user',
            'projects' => 'required'
        ], [
            'truename.required' => '姓名不能为空',
            'id_num.required' => '身份照号码不能为空',
            'id_num.unique' => '身份证号码已存在',
            'projects.required' => '项目名不能为空',
            'phone.required' => '电话号码不能为空',
            'phone.unique' => '电话号码已存在',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'truename' => $request->truename,
            'phone' => $request->phone,
            'id_num' => $request->id_num,
            'projects' => $request->projects

        ]);
        $user->save();//
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /**
     * 摇一摇
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function shake(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '报名已截止，请报名的参与者点击“我的奖品”查看中奖信息。'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if ($user->ticket >= 1) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        $user->fill(['ticket' => 1]);
        $user->save();
        return $this->returnJson(1, "获取成功", ['user' => $user]);
    }

    /**
     * 使用抽奖券
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function used(Request $request)
    {
        if ($this->isTimeout(self::END_TIME)) {
            return response()->json(['error' => '报名已截止，请报名的参与者点击“我的奖品”查看中奖信息。'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user->ticket == 2) {
            return response()->json(['error' => '已经使用过,不要重复使用'], 422);
        }
        if ($info = Info::where('id_num','like', '%'. $user->id_num . '%')->where('status', 0)->first()) {
            $info->status = 1;
            $info->save();
            $user->status = 1;
        } else {
            $user->status = 0;
        }
        $user->fill(['ticket' => 2]);
        $user->save();
        return $this->returnJson(1, "使用成功", ['user' => $user]);
    }

    public function prize(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $token = Token::find(1);
        if ($token->status == 0) {
            return response()->json(['error' => '还未到抽奖时间,请耐心等待'], 422);
        }
        $prize = $user->prize;
        return $this->returnJson(1, "使用成功", ['user' => $user, 'prize' => $prize]);
    }

}
