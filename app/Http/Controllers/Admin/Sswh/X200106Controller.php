<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200106Export;
use App\Helpers\Helper;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

use App\Models\Jyyc\X200106\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class X200106Controller extends Controller
{
    protected $item = 'x200106';

    const REDPACK_OPEN = false;  //红包开关


    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = User::when($status != '', function ($query) use ($status) {
            return $query->where('status', $status);
        });
        $paginator = $query->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/api/sswh/x200106/export');
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200106');
        return view('sswh.sswhAdmin.x200106', ['title' => '宜昌中心·天宸府现金抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl, 'redisShareData' => $redisShareData]);
    }

    public function export()
    {
        return Excel::download(new X200106Export, '宜昌中心·天宸府--台州商会.xlsx');
    }

    /*
     * 获取抽奖用户信息
     */
    public function prizeUsers()
    {
        $prizeUsers = User::all()->toArray();
        $count = count($prizeUsers);
        return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers, 'count' => $count]);

    }


    /*
     * 获取当前抽奖轮数
     */
    public function round(Request $request)
    {
        if ($request->zc != 'x200106r') {
            return redirect("https://www.baidu.com");
        }
        $round = $this->getRound();
        return view('sswh.sswhAdmin.x200106r', ['title' => '宜昌中心·天宸府抽奖', 'round' => $round]);
    }

    /*
     * 获取当前抽奖轮数
     */
    public function getRound()
    {
        $redis = app('redis');
        $redis->select(12);
        $redisKey = 'wx:' . $this->item . ':round';
        $round = $redis->get($redisKey);
        return $round;
    }

    /*
     * 设置抽奖轮数
     */
    public function setRound(Request $request)
    {
        $round = $request->round;
        $redis = app('redis');
        $redis->select(12);
        $redisKey = 'wx:' . $this->item . ':round';
        if ($round < 0 && $round > 5) {
            $round = 0;
        }
        $res = $redis->set($redisKey, $round);
        if ($res && $round == 0) {
            return '停止';
        } else {
            return '开启';
        }
    }


    /*
     * 抽奖
     */
    public function prize()
    {
        $round = $this->getRound();
        switch ($round) {
            case 0 :
                return $this->returnJson(1, ['error' => '请选择抽奖轮数']);
                break;
            case 1:
                if (User::where('round', 1)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                $prizes = User::where('status', '!=', 1)->get()->random(15);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 1;
                    $user->prize = '礼品五件套';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case 2:
                if (User::where('round', 2)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }

                if (User::where('status', '!=', 1)->count() < 45) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->get()->random(45);
                foreach ($prizes as $key => $user) {
                    if ($key < 30) {
                        $user->prize_id = 2;
                        $user->prize = '邮折礼盒';
                        $user->status = 1;
                        $user->prized_at = now()->toDateTimeString();
                        $user->round = $round;
                        $user->save();
                    } else {
                        $user->prize_id = 3;
                        $user->prize = '88元现金红包';
                        $resultRedpack = $user->sendRedpack(88, $user->openid, $user->id, self::REDPACK_OPEN);
                        $user->redpack_return_msg = $resultRedpack['return_msg'];
                        $user->status = $resultRedpack['result_code'] == 'SUCCESS' ? 1 : 2;
                        $user->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
                        $user->prized_at = now()->toDateTimeString();
                        $user->round = $round;
                        $user->save();
                    }


                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case
            3 :
                if (User::where('round', 3)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 10) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->get()->random(10);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 4;
                    $user->prize = '188元现金红包';
                    $resultRedpack = $user->sendRedpack(188, $user->openid, $user->id, self::REDPACK_OPEN);
                    $user->redpack_return_msg = $resultRedpack['return_msg'];
                    $user->status = $resultRedpack['result_code'] == 'SUCCESS' ? 1 : 2;
                    $user->redpack_describle = json_encode($resultRedpack, JSON_UNESCAPED_UNICODE);
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            case 4 :
                if (User::where('round', 4)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 4) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->get()->random(4);
                foreach ($prizes as $key => $user) {
                    if ($key < 2) {
                        $user->prize_id = 5;
                        $user->prize = '666元红包';
                    } else {
                        $user->prize_id = 6;
                        $user->prize = '古八景定制金钞';
                    }
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成', ['prizes' => $prizes]);
                break;
            case 5 :
                if (User::where('round', 5)->first()) {
                    $prizeUsers = User::where('round', $round)->get()->toArray();
                    return $this->returnJson(1, '查询成功', ['prizeUsers' => $prizeUsers]);
                }
                if (User::where('status', '!=', 1)->count() < 2) {
                    return $this->returnJson(1, ['error' => '抽奖人数不足']);
                }
                $prizes = User::where('status', '!=', 1)->get()->random(2);
                foreach ($prizes as $key => $user) {
                    $user->prize_id = 7;
                    $user->prize = '脉冲净水器';
                    $user->status = 1;
                    $user->prized_at = now()->toDateTimeString();
                    $user->round = $round;
                    $user->save();
                }
                return $this->returnJson(1, '抽奖成功', ['prizes' => $prizes]);
                break;
            default:
                break;
        }
    }
}
