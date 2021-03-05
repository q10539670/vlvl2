<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use Illuminate\Http\Request;
use App\Models\Sswh\X191101\User;
use Illuminate\Support\Facades\Validator;

class X191101Controller extends Common
{
    protected $itemName = 'x191101';

    const END_TIME = '2019-11-08 23:59:59';

    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchSswhUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'game_num' => 3,
                'share_num' => 3
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time'=>self::END_TIME
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
        return $this->returnJson(1, "提交成功", ['user'=>$user]);
    }

    /*
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
        if ($user->game_num < 0) {
            return response()->json(['error' => '游戏次数不足'], 422);
        }
        if (!Helper::stopResubmit($this->itemName . ':score', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'score' => ['required','regex:/^[\d]*$/'],
        ], [
            'score.required' => '成绩不能为空',
            'score.regex' => '成绩异常'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        if ($request->score > $user->score) {
            $user->fill([
                'score' => $request->score,
            ]);
        }
        $user->game_num--;
        $user->save();
        return $this->returnJson(1, "成绩提交成功", ['user'=>$user]);

    }

    /*
     * 排行榜
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $listAll = User::orderBy('score', 'desc')->orderBy('updated_at', 'asc')->get()->toArray();
        //去掉成绩为0的结果
        $list = array_filter($listAll, function($values){return $values['score'];});
        $ranking = -1;
        foreach ($list as $key => $value) {
            if ($value['openid'] == $request->openid) {
                $ranking = $key + 1;
            }
            $list[$key]['ranking'] = $key+1;
        }
        return $this->returnJson(1, "排行榜数据查询成功", ['user' => $user,'list' => $list, 'ranking' =>$ranking]);
    }

    /*
     * 分享助力
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->game_num++;
            $user->share_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }
}
