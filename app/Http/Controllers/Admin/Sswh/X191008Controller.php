<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191008Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X191008\User;
use App\Models\Sswh\X191008\Token;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class X191008Controller extends Controller
{
    protected $id = -1;

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $nameOrPhone = $request->input('nameOrPhone');
        $status = $request->input('status');
        $query = User::when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
            return $query->where('phone', '=', $nameOrPhone);
        })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('truename', 'like', '%' . $nameOrPhone . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', '=', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x191008/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:baolimoney191010');
        return view('sswh.sswhAdmin.x191008', ['title' => '中国梦·保利家', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191008Export, '中国梦·保利家抽奖名单.xlsx');
    }

    /**
     * 随机抽奖
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function prize()
    {
        //内控人员身份证信息数组
        /*一等奖*/
        $firstIdNum = ['420621199710211229'];
        /*二等奖*/
        $secondIdNum = [
            '420582199609095427',
            '420104199406011616'
        ];
        /*三等奖*/
        $thirdIdNum = [
            '421181199612023523',
            '42900619941122518X',
            '620421199401074810',
            '420107199408293716',
        ];

        //获取一二三等奖内定人员ID以及信息
        $user = new User();
        $firstPrizeId = $user->getPrizeId($firstIdNum);
        $firstPrize =$user->getPrize($firstIdNum);
        $secondPrizeIds = $user->getPrizeId($secondIdNum);
        $secondPrize = $user->getPrize($secondIdNum);
        $thirdPrizeIds = $user->getPrizeId($thirdIdNum);
        $thirdPrize = $user->getPrize($thirdIdNum);

        //排除内定人员ID
        $ids = array_merge($firstPrizeId, $secondPrizeIds, $thirdPrizeIds);

        //获取抽奖资格人数
        $prizeNum = User::where('ticket', 2)->where('status', 1)->whereNotIn('id', $ids)->count();
        if ($prizeNum<5) {
            return $this->returnJson(-1, "抽奖失败,符合抽奖人数不足");
        }

        //抽取二等奖1名
        for ($i = 2; $i < 3; $i++) {
            $userInfo = $this->addIds($ids);
            $secondPrize[$i] = $userInfo['prize_user'];
            $secondIds = $userInfo['ids'];
            $secondPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids , $secondIds));
        }
        $ids += $thirdPrizeIds;

        //抽取三等奖4名
        for ($i = 4; $i < 8; $i++) {
            $userInfo = $this->addIds($ids);
            $thirdPrize[$i] = $userInfo['prize_user'];
            $thirdIds = $userInfo['ids'];
            $thirdPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids , $thirdIds));
        }
        return $this->returnJson(1, "抽奖成功", [
            'first_prize_id' => $firstPrizeId,
            'first_prize' => $firstPrize,
            'second_prize' => $secondPrize,
            'second_prize_ids' => $secondPrizeIds,
            'third_prize' => $thirdPrize,
            'third_prize_ids' => $thirdPrizeIds,
//            'ids'=>$ids
        ]);
    }

    /**
     * 确认中奖名单
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function send(Request $request)
    {
        $token = Token::find(1);
        $tokenStr = $token->token;
        $status = $token->status;
        if ($request->input('token') != $tokenStr) {
            return $this->returnJson(-3, "抽奖结果确认失败,口令错误");
        }
        if ($status == 1) {
            return $this->returnJson(-2, "抽奖结果确认失败,口令已使用");
        }
        $secondPrizeIds = explode(',', $request->input('second_prize_ids'));
        $thirdPrizeIds = explode(',', $request->input('third_prize_ids'));
        $firstPrizeId=  $request->input('first_prize_id');
        $firstPrizeUser = DB::table('x191008_user')->where('id', $firstPrizeId)->update([
            'prize' => '100000元',
        ]);
        $secondPrizeUser = DB::table('x191008_user')->whereIn('id', $secondPrizeIds)->update([
            'prize' => '10000元',
        ]);
        $thirdPrizeUser = DB::table('x191008_user')->whereIn('id', $thirdPrizeIds)->update([
            'prize' => '5000元',
        ]);
        if ($secondPrizeUser && $thirdPrizeUser && $firstPrizeUser) {
            $token->status = 1;
            $token->used_at = now()->toDateString();
            $token->save();
            return $this->returnJson(1, "抽奖结果确认成功");
        } else {
            return $this->returnJson(-1, "抽奖结果确认失败");
        }
    }

    /**
     *
     * 抽取中奖用户,拼接中奖id
     * @param $ids
     * @return array
     */
    public function addIds($ids)
    {
        $user = new User();
        $prizeUser = $user->getUser($ids);
        $ids[] = $prizeUser['id'];
        return ['prize_user' => $prizeUser, 'ids' => $ids, 'id' => $prizeUser['id']];
    }
}
