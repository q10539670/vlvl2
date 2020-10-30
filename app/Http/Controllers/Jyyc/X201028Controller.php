<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use Illuminate\Http\Request;
use App\Models\Jyyc\X201028\User;
use App\Models\Jyyc\X201028\Help;
use Illuminate\Support\Facades\Validator;

class X201028Controller extends Common
{
    //宜昌中心
    protected $itemName = 'x201028';

    const END_TIME = '2020-11-06 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJyycUser($request);
            $userInfo = $this->userInfo($request, $info);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        $help = 0;
        $referer = $request->header("referer");
        if (strpos($referer, 'target_user_id')) {
            //截取参数
            $paramStr = substr(strstr($referer, '?'), 1);
            //把参数拆分成数组
            $paramArr = explode('&', $paramStr);
            //从$referer中提取target_user_id
            foreach ($paramArr as $param) {
                $params = explode('=', $param);
                if ($params['0'] == 'target_user_id') {
                    $target_user_id = $params['1'];
                }
            }
            //判断target与help的id是否相同
            if (preg_match("/^\d*$/", $target_user_id) && $target_user_id != $user->id && $target_user_id != 0) {
                $todayStart = date('Y-m-d').' 00:00:00';
                $todayend = date('Y-m-d').' 23:59:59';
                //检查此用户今天是否助力过目标用户
                $res = Help::where('target_user_id', $target_user_id)
                    ->where('help_user_id', $user->id)
                    ->WhereBetween('created_at', [$todayStart, $todayend])->get()->toArray();
                if (empty($res)) {
                    $targetUser = User::find($target_user_id);
                    if ($targetUser != null) {
                        $targetUser->help_num++;
                        $targetUser->fill(['total' => $targetUser->total + 10]);
                        $targetUser->save();
                        //写入数据库,助力次数+1
                        Help::create([
                            'target_user_id' => $target_user_id,
                            'help_user_id' => $user->id
                        ]);
                        $help++;//助力成功标志
                    }
                }
            }
        }
        return Helper::Json(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME,
            'help' => $help
        ]);
    }

    /**
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
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone
        ]);
        $user->save();
        return Helper::Json(1, "提交成功", ['user' => $user]);
    }

    /**
     * 成绩提交
     */
    public function score(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已截止'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required', 'regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        //查询成绩
        $oldScore = User::where('openid', $request->openid)->first(['score', 'help_num']);
        //判断当前成绩是否超过之前成绩
        $total = $oldScore['help_num'] * 10 + $request->score;
        if ($request->score > $oldScore['score']) {
            $user->fill([
                'score' => $request->score,
                'total' => $total
            ]);
            $user->save();
            return $this->returnJson(1, "打破记录 成绩更新成功", ['user' => $user]);
        }
        return Helper::Json(1, "未超过最高纪录", ['user' => $user]);

    }

    /**
     * 排行榜
     */
    public function list()
    {
        //去0排序(成绩相同,按参与时间排序)
        $list = User::where('score', '!=', 0)->orderBy('total', 'desc')->orderBy('updated_at', 'desc')->take(20)->get();
        return Helper::Json(1, "排行榜数据查询成功", $list);
    }
}
