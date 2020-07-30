<?php


namespace App\Http\Controllers\Jyyc;

use App\Helpers\Helper;
use App\Http\Controllers\Common\Common;
use App\Models\Jyyc\X200730\Contestant;
use App\Models\Jyyc\X200730\User;
use App\Models\Jyyc\X200730\Log;
use DemeterChain\C;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class X200730Controller extends Common
{
    //物业女神投票
    protected $itemName = 'x200730';

    //结束时间
    const END_TIME = '2020-08-16 12:00:00';


    /*
     * 获取/记录用户授权信息
     * */
    public function user(Request $request)
    {
        $openid = $request->openid;
        if (!$user = User::where(['openid' => $openid])->first()) {
            //查询总表
            $info = $this->searchJyycUser($request);
            $userInfo = $this->userInfo($request, $info, [
                'vote_num' => 2
            ]);
            //新增数据到表中
            User::create($userInfo);
            //查询
            $user = User::where(['openid' => $openid])->first();
        }
        return $this->returnJson(1, "查询成功", [
            'user' => $user,
            'is_active_time' => (time() > strtotime(self::END_TIME)) ? 0 : 1,
            'end_time' => self::END_TIME
        ]);
    }


    /**
     * 投票
     * @param  Request  $request
     * @return JsonResponse
     */
    public function vote(Request $request)
    {
        if (time() > strtotime(self::END_TIME)) {
            return response()->json(['error' => '活动时间截止，投票通道已关闭'], 422);
        }
        if (!$user = User::where('openid', $request->openid)->first()) {
            return response()->json(['error' => '未授权'], 422);
        }
        if ($user['vote_num'] <= 0) {
            return response()->json(['error' => '今日投票次数已用完，明天可以继续为她投票'], 422);
        }

        if (!$contestant = Contestant::find($request->input('cid'))) {
            return response()->json(['error' => '投票失败,参数错误'], 422);
        }
        $contestant->poll++;
        $user->vote_num--;
        $log = [
            'user_id' => $user['id'],
            'nickname' => $user['nickname'],
            'avatar' => $user['avatar'],
            'cid' => $request->input('cid')
        ];
        DB::beginTransaction();
        try {
            $contestant->save();
            $user->save();
            Log::create($log);
            DB::commit();
            return $this->returnJson(1, "投票成功", ['poll' => $contestant->poll]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }

    }

    /**
     * 查询所有
     * @return false|JsonResponse|string
     */
    public function contestants()
    {

        $contestants = Contestant::orderBy('number', 'asc')->get();
        return $this->returnJson(1, "查询成功", ['contestants' => $contestants]);

    }
}
