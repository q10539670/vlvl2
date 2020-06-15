<?php

namespace App\Http\Controllers\Ticket\L191127;

use App\Models\Ticket\L191127\ActivityOne;
use App\Models\Ticket\L191127\ActivityTwo as ActTwo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
//use App\Models\Ticket\Demo\AuthUser as AuthUser;
use App\Models\Tdr\Wuhan\TdrUsers as AuthUser;
//use App\Models\Tdr\Guangzhou\TdrUsers as AuthUser;
use App\Models\Ticket\L191127\User as User;
use App\Models\Ticket\L191127\Ticket as Ticket;
use App\Models\China;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    //汤达人
    protected $itemName = 'l191127';

    //存储路径
    const STORE_PATH = '/upload/items/ticket/l191127/';
    //活动1开始时间
    const START_TIME1 = '2019-12-10 00:00:00';
    //活动1截止时间
    const END_TIME1 = '2019-12-31 23:59:59';

    //活动2开始时间
    const START_TIME2 = '2020-01-01 00:00:00';
    //活动2截止时间
    const END_TIME2 = '2020-02-08 23:59:59';

    const URL_PREFIX = 'https://wx.sanshanwenhua.com/vlvl/storage2';



    /*
     * 测试接口
     * */
    public function test()
    {
        $china = new China();
        $res = $china->getAllChildArea(420000);
        return $res;
    }

    public function test2()
    {
        $key = "老坛酸菜";
        $words = "康师傅统一老坛酸菜";
        if (preg_match('/' . $key . '/', $words)) {
            $res = "通过";
        } else {
            $res = "不通过";
        }
        return $res;
    }

    public function test1()
    {
//        return Ticket::where('check_status', 11)
//            ->where('money', 88)
//            ->whereDate('created_at', '2019-05-16')
//            ->count();
        $ticket = new Ticket();
        $ticket->created_at = now()->toDateTimeString();
        return $ticket->randomPrizeCreatedAt();
    }

    public function publish()
    {
        $redis = app('redis');
        $res1 = $redis->publish('test', 'cdcdv');
        $res2 = $redis->publish('test11', 'cdcdv');
        return compact('res1', 'res2');
    }

    /**
     * {"return_code":"SUCCESS",
     * "return_msg":"每个红包的平均金额必须在0.80元到6.00元之间.",
     * "result_code":"FAIL","err_code":"MONEY_LIMIT",
     * "err_code_des":"每个红包的平均金额必须在0.80元到6.00元之间.","mch_billno":"ty191127174317aa22aa1",
     * "mch_id":"1367446102","wxappid":"wxf8f3ec2c13664510","re_openid":"olVD1szujYyOxxCtVHsy01gPbupM","total_amount":"1"}
     * 获取/记录 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request)
    {

        $user = User::where(['openid' => $request->openid])->with(['tickets' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->first();

        if (!$user) {
            if (!$userDetail = AuthUser::select('nickname', 'headimgurl')->where('openid', $request->openid)->first()) {
                return response()->json(['error' => '未授权'], 422);
            }
            $lastUser = User::create([
                'openid' => $request->openid,
                'nickname' => $userDetail->nickname,
                'avatar' => $userDetail->headimgurl,
                'created_at' => now()->toDateTimeString()
            ]);
            $user = User::where(['id' => $lastUser->id])->with(['tickets' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->first();
        }

        return Helper::json(1, '获取/记录 用户信息成功', [
            'user' => $user,
            'img_prefix' => self::URL_PREFIX,
            'is_active_time' => (time() > strtotime(self::END_TIME2)) ? 0 : 1
        ]);
    }

    /*
     * 提交经纬度
     * */
    public function location(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422); //
        }
        //阻止重复提交
        if (!Helper::stopResubmit(__CLASS__ . ':' . __METHOD__, $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if ($user->is_active_area == 1) {
            return response()->json(['error' => '已在活动区域，无法提交'], 422);
        }
        $validator = Validator::make($request->all(),
            [
                'latitude' => 'required|min:-90|max:90',   //纬度
                'longitude' => 'required|min:-180|max:180',  //经度
            ],
            [
                'latitude.required' => '纬度不能为空',
                'latitude.min' => '纬度不能小于-90',
                'latitude.max' => '纬度不能超过90',
                'longitude.required' => '经度不能为空',
                'longitude.min' => '经度不能小于-180',
                'longitude.max' => '经度不能超过180',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $locationStr = $request->latitude . ',' . $request->longitude;
        $user->location = $locationStr;
        $user->address_code = '-2';
        $user->last_signup_location_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '提交用户经纬度成功', [
            'location' => $user->location,
            'address_code' => $user->address_code,
            'last_signup_location_at' => $user->last_signup_location_at,
        ]);
    }

    /**
     * 上传小票
     */
    public function uploadImg(Request $request)
    {
        if (time() > strtotime(self::END_TIME2)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit(__CLASS__ . ':' . __METHOD__, $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
        }
        if (Ticket::where('user_id', $user->id)->whereDate('created_at', date('Y-m-d'))->first()) {
            return response()->json(['error' => '每天只能上传一次小票'], 422);
        }
        $validator = Validator::make($request->all(), [
            'img' => 'required|image|max:' . (1024 * 6)
        ], [
            'img.required' => '上传图片不能未空',
            'img.image' => '上传类型只能是图片',
            'img.max' => '图片大小不能超过6M',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        if (!$request->file('img')->isValid()) {
            return response()->json(['error' => '上传错误'], 422);
        }
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'storage2';
        if (!$path = $request->img->store(self::STORE_PATH . date('Ymd'), $storeDriver)) {
            return response()->json(['error' => '上传错误,请重新上传'], 422);
        }
        //添加小票
        Ticket::create([
            'user_id' => $user->id,
            'img_url' => $path,
            'created_at' => now()->toDateTimeString(),
        ]);
        //更新用户表
        $user->fill([
            'upload_num' => $user->upload_num + 1,
            'updated_at' => now()->toDateTimeString(),
            'last_upload_at' => now()->toDateTimeString(),
        ]);

        $user->save();

        $newUser = User::where(['id' => $user->id])->with(['tickets' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->first();
        return Helper::json(1, '上传小票成功', ['user' => $newUser, 'img_prefix' => self::URL_PREFIX]);
    }

    /**
     * 最新弹幕20条
     */

    public function users(Request $request)
    {
        $query = 'SELECT tickets.money, `user`.nickname FROM auto_check_v3_tickets AS tickets INNER JOIN auto_check_v3_user AS `user` ON tickets.user_id = `user`.id WHERE tickets.money > 0 ORDER BY tickets.prize_at DESC LIMIT 0, 20';
        $users = DB::select($query);
        return Helper::json(1, '查询成功', ['users' => $users]);
    }

    /**
     * 活动时间
     */
    public function time()
    {
        $time = [];
        $time['act1']['start'] = self::START_TIME1;
        $time['act1']['end'] = self::END_TIME1;
        $time['act2']['start'] = self::START_TIME2;
        $time['act2']['end'] = self::END_TIME2;
        $time['now'] = date( "Y-m-d H:i:s", time());
        return Helper::json(1, '时间查询成功', ['time' => $time]);
    }

    /**
     * 获取中奖名单
     */
    public function prizeLists()
    {
        $rankListKey = 'wx:' . $this->itemName . ':rankList';
        $redis = app('redis');
        $redis->select(12);
        if (!$rankListArr = $redis->get($rankListKey)) {
            $rankList = \DB::select('SELECT
`user`.nickname,
`user`.avatar,
act1.red_money
FROM
auto_check_v3_user AS `user`
INNER JOIN auto_check_v3_act1 AS act1 ON act1.user_id = `user`.id
WHERE
act1.red_money IN (18800, 8800, 800)
ORDER BY
act1.red_money DESC');
            $rankListArr = json_encode($rankList,JSON_UNESCAPED_UNICODE);
            $redis->set($rankListKey, $rankListArr);
        }
        $rankListArr = json_decode($rankListArr,true);

        return Helper::json(1, '查询成功', ['rank_list_arr' => $rankListArr]);
    }

    /**
     * 获取活动2中奖名单
     */
    public function prizeListsForAct2()
    {
        $rankListKey = 'wx:' . $this->itemName . ':rankListAct2';
        $redis = app('redis');
        $redis->select(12);
        $rankListArr = $redis->get($rankListKey);
        if (!$rankListArr = $redis->get($rankListKey)) {
            $rankList1 = \DB::select('SELECT
`user`.nickname,
`user`.avatar,
act2.red_money
FROM
auto_check_v3_user AS `user`
INNER JOIN auto_check_v3_act2 AS act2 ON act2.user_id = `user`.id
WHERE
act2.red_money IN (1, 2)
ORDER BY
act2.red_money ASC');
            $rankList2 = \DB::select('SELECT
`user`.nickname,
`user`.avatar,
act2.red_money
FROM
auto_check_v3_user AS `user`
INNER JOIN auto_check_v3_act2 AS act2 ON act2.user_id = `user`.id
WHERE
act2.red_money IN (18800,8800,800)
ORDER BY
act2.red_money DESC');
            $rankList = array_merge($rankList1,$rankList2);
            $rankListArr = json_encode($rankList,JSON_UNESCAPED_UNICODE);
            $redis->set($rankListKey, $rankListArr);
        }
        $rankListArr = json_decode($rankListArr,true);

        return Helper::json(1, '查询成功', ['rank_list_arr' => $rankListArr]);
    }

    /**
     * 提交信息
     */
    public function post(Request $request)
    {
        if (time() > strtotime(self::END_TIME2)) {
            return response()->json(['error' => '活动已结束'], 422);
        }
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit(__CLASS__ . ':' . __METHOD__, $user->id, 3)) {
            return response()->json(['error' => '不要重复提交'], 422);
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
        $actTwoUser = ActTwo::where('user_id',$user->id)->first();
        $actTwoUser->fill([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
        $actTwoUser->save();
        return Helper::json(1, '查询成功', ['actTwoUser' => $actTwoUser]);
    }
}
