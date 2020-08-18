<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X200817\Site2_2User as User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class X200817Site2_2Controller extends Common
{

    //金地华中第六届纳凉音乐节
    protected $itemName = 'x200817_site1';

    const END_TIME = '2020-08-30 23:59:59';  //结束时间

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
        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }


    /*
     * 提交信息
     * */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束'], 422);
        }

        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }

        if (!Helper::stopResubmit($this->itemName.':post', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        //检查信息
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'room_no' => 'required'
        ], [
            'name.required' => '姓名不能为空',
            'phone.required' => '电话号码不能为空',
            'room_no.required' => '房号不能为空'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'room_no' => $request->room_no
        ]);
        $user->save();
        return $this->returnJson(1, "提交成功", ['user' => $user]);
    }

    /*
     * 获取抽奖用户池信息
     */
    public function prizeUsers()
    {
        $prizeUsers = User::where('status', '!=', 1)->where('phone','!=','')->get()->toArray();
        $count = count($prizeUsers);
        $prizeUsers = User::getFormatUser($prizeUsers);
        return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers, 'count' => $count]);

    }

    /**
     * @return false|JsonResponse|string
     */
    public function prize()
    {
        $round = User::getRound();
        switch ($round) {
            case 0 :
                return $this->returnJson(1, ['error' => '请选择抽奖轮数']);
                break;
            case 1:
                if (User::where('round', 1)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    $prizeUsers = User::getFormatUser($prizeUsers);
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 5) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->where('phone','!=','')->get()->random(5);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 4;
                    $user->prize = '幸运奖';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $prizes[$key]['hide_phone'] = User::formatPhone($user->phone);
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case 2:
                if (User::where('round', 2)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    $prizeUsers = User::getFormatUser($prizeUsers);
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 5) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->where('phone','!=','')->get()->random(5);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 4;
                    $user->prize = '幸运奖';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $prizes[$key]['hide_phone'] = User::formatPhone($user->phone);
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case 3:
                if (User::where('round', 3)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    $prizeUsers = User::getFormatUser($prizeUsers);
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 3) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->where('phone','!=','')->get()->random(3);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 3;
                    $user->prize = '三等奖';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $prizes[$key]['hide_phone'] = User::formatPhone($user->phone);
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case 4 :
                if (User::where('round', 4)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    $prizeUsers = User::getFormatUser($prizeUsers);
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 2) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->where('phone','!=','')->get()->random(2);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 2;
                    $user->prize = '二等奖';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $prizes[$key]['hide_phone'] = User::formatPhone($user->phone);
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成', ['prizes' => $prizes]);
                break;
            case 5 :
                if (User::where('round', 5)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    $prizeUsers = User::getFormatUser($prizeUsers);
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 1) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->where('phone','!=','')->get()->random(1);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 1;
                    $user->prize = '一等奖';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $prizes[$key]['hide_phone'] = User::formatPhone($user->phone);
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            default:
                break;
        }
    }


}
