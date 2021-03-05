<?php


namespace App\Http\Controllers\Sswh;
use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191219\Info;
use App\Models\Sswh\X191219\User;
use App\Models\Sswh\X191219\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191219Controller extends Common
{

    //赤壁现金抽奖
    protected $itemName = 'x191219';

    const END_TIME = '2020-04-04 19:00:00';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info,['prize_id'=>0]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        $openPrize = 0;
        $token = Token::where('id', 1)->first();
        if ($token->status == 1) {
            $openPrize = 1 ;
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => ($this->isTimeout(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
            'open_prize' => $openPrize
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
        if (!Helper::stopResubmit($this->itemName . ':post', $user->id, 15)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_num' => 'required',
            'phone' => 'required', //|unique:x191219_user
        ], [
            'name.required' => '姓名不能为空',
            'id_num.required' => '身份照号码不能为空',
//            'id_num.unique' => '身份证号码已存在',
            'phone.required' => '电话号码不能为空',
//            'phone.unique' => '电话号码已存在',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'id_num' => $request->id_num,

        ]);
        if ($info = Info::where('id_num','like', '%'. $request->id_num . '%')->where('status', 0)->first()) {
            $info->status = 1;
            $info->save();
            $user->make = 1;
        } else {
            $user->status = 0;
        }
        $user->save();//
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }



}
