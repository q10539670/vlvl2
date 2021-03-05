<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X191219Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Sswh\X191219\User;
use App\Models\Sswh\X191219\Token;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class X191219Controller extends Controller
{
    protected $id = -1;

    protected $token = 797879;

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
                return $query->where('name', 'like', '%' . $nameOrPhone . '%');
            })
            ->when($status != '', function ($query) use ($status) {
                return $query->where('status', '=', $status);
            });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x191219/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:cb20191219');
        return view('sswh.sswhAdmin.x191219', ['title' => '赤壁·雍景现金抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X191219Export, '赤壁·雍景现金抽奖名单.xlsx');
    }

    /**
     * 随机抽奖
     * @return false|\Illuminate\Http\JsonResponse|string
     */
    public function prize()
    {
        /*一等奖*/
        $firstIdNum = ['421223199001152926'];   //周细华 10000元
        /*二等奖*/
        $secondIdNum = [
            '422302198007054710',   //袁召辉    5000元
            '422323195305030029',   //葛婕      5000元
            '43052419871001412X'    //庞晓芳    5000元
        ];
        //获取一二三等奖内定人员ID以及信息
        $user = new User();
        $firstPrizeIds = $user->getPrizeId($firstIdNum);
        $firstPrize =$user->getPrize($firstIdNum);
        $secondPrizeIds = $user->getPrizeId($secondIdNum);
        $secondPrize = $user->getPrize($secondIdNum);
        //排除内定人员ID
        $ids = array_merge($firstPrizeIds, $secondPrizeIds);


        //获取抽奖资格人数
        $prizeNum = User::where('make', 1)->where('status', '!=', 1)->count();
        if ($prizeNum < 46) {
            return $this->returnJson(-1, "抽奖失败,符合抽奖人数不足");
        }

        //抽取一等奖  1名
        for ($i = 1; $i < 2; $i++) {
            $userInfo = $this->addIds($ids);
            $firstPrize[$i] = $userInfo['prize_user'];
            $firstIds = $userInfo['ids'];
            $firstPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids, $firstIds));  //合并去重
        }
        //抽取三等奖 5名
        for ($i = 2; $i < 7; $i++) {
            $userInfo = $this->addIds($ids);
            $thirdPrize[$i] = $userInfo['prize_user'];
            $thirdIds = $userInfo['ids'];
            $thirdPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids, $thirdIds));  //合并去重
        }
        //抽取四等奖 10名
        for ($i = 7; $i < 17; $i++) {
            $userInfo = $this->addIds($ids);
            $fourthPrize[$i] = $userInfo['prize_user'];
            $fourthIds = $userInfo['ids'];
            $fourthPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids, $fourthIds));  //合并去重
        }
        //抽取五等奖 30名
        for ($i = 17; $i < 47; $i++) {
            $userInfo = $this->addIds($ids);
            $fifthPrize[$i] = $userInfo['prize_user'];
            $fifthIds = $userInfo['ids'];
            $fifthPrizeIds[] = $userInfo['id'];
            $ids = array_unique(array_merge($ids, $fifthIds));  //合并去重
        }

        return $this->returnJson(1, "抽奖成功", [
            'first_prize_ids' => $firstPrizeIds,
            'first_prize' => $firstPrize,
            'second_prize' => $secondPrize,
            'second_prize_ids' => $secondPrizeIds,
            'third_prize' => $thirdPrize,
            'third_prize_ids' => $thirdPrizeIds,
            'fourth_prize' => $fourthPrize,
            'fourth_prize_ids' => $fourthPrizeIds,
            'fifth_prize' => $fifthPrize,
            'fifth_prize_ids' => $fifthPrizeIds,
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
        $firstPrizeIds = explode(',', $request->input('first_prize_ids'));
        $secondPrizeIds = explode(',', $request->input('second_prize_ids'));
        $thirdPrizeIds = explode(',', $request->input('third_prize_ids'));
        $fourthPrizeIds = explode(',', $request->input('fourth_prize_ids'));
        $fifthPrizeIds = explode(',', $request->input('fifth_prize_ids'));
//        dd($firstPrizeIds);
        $firstPrizeUser = DB::table('x191219_user')->whereIn('id', $firstPrizeIds)->update([
            'prize_id' => 1,
            'status' => 1,
            'prize' => '10000元',
        ]);
        $secondPrizeUser = DB::table('x191219_user')->whereIn('id', $secondPrizeIds)->update([
            'prize_id' => 2,
            'status' => 1,
            'prize' => '5000元',
        ]);
        $thirdPrizeUser = DB::table('x191219_user')->whereIn('id', $thirdPrizeIds)->update([
            'prize_id' => 3,
            'status' => 1,
            'prize' => '3000元',
        ]);
        $fourthPrizeUser = DB::table('x191219_user')->whereIn('id', $fourthPrizeIds)->update([
            'prize_id' => 4,
            'status' => 1,
            'prize' => '2000元',
        ]);
        $fifthPrizeUser = DB::table('x191219_user')->whereIn('id', $fifthPrizeIds)->update([
            'prize_id' => 5,
            'status' => 1,
            'prize' => '1000元',
        ]);
        if ($secondPrizeUser && $thirdPrizeUser && $firstPrizeUser && $fourthPrizeUser && $fifthPrizeUser) {
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
