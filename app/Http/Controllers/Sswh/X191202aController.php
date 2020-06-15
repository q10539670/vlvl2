<?php


namespace App\Http\Controllers\Sswh;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Sswh\X191202a\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class X191202aController extends Common
{

    protected $itemName = 'x191202a';

    const END_TIME = '2019-12-12 23:59:59';

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
                'prize_num' => 2,
                'share_num' => 1,
                'prize' =>'未中奖'
            ]);
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
     * 中奖记录
     */
    public function list(Request $request)
    {
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        $where = function ($query) use ($request) {
            $query->where('prize_id', '!=', 0)->where('openid', '!=', $request->openid);
        };
        $perPage = $request->has('per_page') ? $request->per_page : 5; //每页条数
        $currentPage = $request->has('current_page') ? $request->current_page : 1;  //当前页
        $total = User::where($where)->count();       // 数据条数
        $totalPage = ceil($total / $perPage);   // 总页数
        $items = User::where($where)->orderBy('prize_id','asc')->orderBy('updated_at', 'asc')->offset($perPage * ($currentPage - 1))->limit($perPage)->get()->toArray();
        return $this->returnJson(1, "中奖记录查询成功", [
            'user' => $user,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total' => $total,
            'total_page' => $totalPage,
            'items' => $items
        ]);
    }

    /*
     * 抽奖
     */
    public function randomPrize(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        //阻止重复提交
        if (!Helper::stopResubmit($this->itemName . ':randomPrize', $user->id, 3)) {
            return response()->json(['error' => '不要重复提交抽奖'], 422);
        }
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动已结束']);
        }

        if ($user->prize_num <= 0 && $user->share_num <= 0) {
            return response()->json(['error' => '抽奖次数已用完']);
        } elseif ($user->prize_num <= 0 && $user->share_num > 0) {
            return response()->json(['error' => '可以分享']);
        }
        if ($user->status == 1) {
            $user->prize_num--;
            $user->save();
            return response()->json(['error' => '未中奖','user' =>$user]);
        }
        $redisBaseKey = 'wx:' . $this->itemName;
        $prize = new User();
        try {
            $resultPrize = $prize->fixRandomPrize($redisBaseKey); // 固定概率抽奖
            $redisCountKey = $resultPrize['prizeCountKey'];
        } catch (\Exception $e) {
            \Log::channel('wx')->info('百事感恩节_抽奖', ['message' => $e->getMessage()]);
            return response()->json(['error' => '抽奖失败,系统错误 ' . $e->getCode()], 422);
        }
        $redis = app('redis');
        $redis->select(12);
        $redisCount = $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], 1); //中奖数累计+1

        //超发 中奖数回退 此次抽奖失效
        if ($redisCount > $resultPrize['resultPrize']['limit']) {
            $redis->hIncrBy($redisCountKey, $resultPrize['resultPrize']['prize_id'], -1);  //超发 中奖数回退
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', -1);
            return response()->json(['error' => '抽奖失败,请重新抽奖'], 422);
        }
        if ($resultPrize['resultPrize']['prize_id'] != 0) {
            $redis->incrBy('wx:' . $this->itemName . ':prizeNum', 1);
            $user->status = 1;
        } else {
            $user->status = 2;
        }
        $user->prize_num--;
        $user->prize = $resultPrize['resultPrize']['prize_name'];
        $user->prize_id = $resultPrize['resultPrize']['prize_id'];
        $user->prize_at = now()->toDateTimeString();
        $user->save();
        return Helper::json(1, '抽奖成功', [
            'user' => $user,
            'prize' => $resultPrize['resultPrize']['prize_name'],
            'prize_id' => $resultPrize['resultPrize']['prize_id'],
            'resultPrize' => $resultPrize
        ]);
    }

    /**
     * 分享
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function share(Request $request)
    {
        if (!$user = User::where(['openid' => $request->openid])->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['share_num'] > 0) {
            $user->prize_num++;
            $user->share_num--;
            $user->save();
        }
        return $this->returnJson(1, "分享成功", ['user' => $user]);
    }

    /*
     * 应用初始化
     */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        foreach ($redis->keys('wx:' . $this->itemName . ':prizeCount') as $v) {
//            if(!in_array($v,$dateArr)){
            $redis->del($v);
//            }
        }
        $redis->hmset('wx:' . $this->itemName . ':prizeCount', ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0]);
        $redis->set('wx:' . $this->itemName . ':prizeNum', 0);
        echo '应用初始化成功';
        exit();
    }
}
