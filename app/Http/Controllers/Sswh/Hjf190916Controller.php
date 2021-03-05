<?php


namespace App\Http\Controllers\Sswh;

use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Sswh\Hjf190916\User;
use App\Models\Sswh\Hjf190916\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Hjf190916Controller extends Controller
{

    const START_TIME = '2018-09-21 00:00:00';
    const END_TIME = '2019-10-20 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = Sswh::select('nickname', 'headimgurl')
                ->where('openid', $openid)
                ->first();
            //新增数据到表中
            User::create([
                'openid' => $openid,
                'nickname' => $info['nickname'],
                'avatar' => $info['headimgurl'],
                'help_num' => 3
            ]);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1
        ]);
    }

    /*
     * 资料填写
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        //检查信息
        $validator = Validator::make($request->all(), [
            'truename' => 'required',
            'phone' => 'required',
        ], [
            'truename.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'truename' => $request->truename,
            'phone' => $request->phone
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    public function share(Request $request)
    {
        if (time() < strtotime(self::START_TIME)) {
            return response()->json(['error' => '投票时间未开始，投票通道未开启'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $url = $request->fullUrl();

        $paramStr = substr(strstr($url, '?'), 1);
        $paramArr = explode('&', $paramStr);

        //从$referer中提取target_user_id
        foreach ($paramArr as $param) {
            $params = explode('=', $param);
            if ($params['0'] == 'target_user_id') {
                $targetUserId = $params['1'];
                $targetUser = User::find($targetUserId);
            } else {
                return response()->json(['error' => '未查询到目标ID'], 422);
            }
        }
        return $this->returnJson(1, "查询成功", ['target_user' => $targetUser]);
    }

    /*
     * 分享加油
     */
    public function help(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $targetUserId = $request->input('target_user_id');
        //判断target与help的id是否相同
        $help_user_id = User::where('openid', $request->openid)->first('id')['id'];
        if ($targetUserId == $help_user_id) {
            return response()->json(['error' => '自己不能给自己助力'], 422);
        }
        $targetUser = User::find($targetUserId);
        if ($targetUser->truename == '') {
            return response()->json(['error' => '目标ID未参与活动'], 422);
        }
        if ($user->help_num <= 0) {
            return response()->json(['error' => '今日加油次数已用尽'], 422);
        }
        $targetUser = User::find($targetUserId);
        $targetUser->gasoline++;
        $res = $targetUser->save();
        //写入数据库,加油次数-1
        if ($res) {
            Help::create([
                'target_user_id' => $targetUserId,
                'help_user_id' => $help_user_id,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ]);
            $user->help_num--;
            $user->save();
        }
        return $this->returnJson(1, "加油成功", ['target_user' => $targetUser]);
    }

    /*
     * 查看油气值以及排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $listAll = User::orderBy('gasoline', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function ($values) {
            return $values['gasoline'];
        });
        return $this->returnJson(1, "排行榜数据查询成功", ['list' => $list, 'user' => $user]);
    }
}
