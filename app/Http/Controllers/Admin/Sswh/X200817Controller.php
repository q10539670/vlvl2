<?php

namespace App\Http\Controllers\Admin\Sswh;

use App\Exports\X200817Site1_1Export;
use App\Exports\X200817Site1_2Export;
use App\Exports\X200817Site2_1Export;
use App\Exports\X200817Site2_2Export;
use App\Http\Controllers\Common\BaseV1Controller as Controller;
use App\Models\Sswh\X200817\Site1_1User;
use App\Models\Sswh\X200817\Site1_2User;
use App\Models\Sswh\X200817\Site2_1User;
use App\Models\Sswh\X200817\Site2_2User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class X200817Controller extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $nameOrPhone = $request->input('nameOrPhone');
        $prizeId = $request->input('prize_id');
        $site = $request->input('site');
        switch ($site) {
            case '1-1':
            default :
                $model = new Site1_1User();
                break;
            case '1-2':
                $model = new Site1_2User();
                break;
            case '2-1':
                $model = new Site2_1User();
                break;
            case '2-2':
                $model = new Site2_2User();
                break;
        }
        $query = $model->when($status != '', function ($query) use ($status) {
            return $query->where('status', $status);
        })
            ->when($prizeId != '', function ($query) use ($prizeId) {
                return $query->where('prize_id', $prizeId);
            })
            ->when(preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('phone', '=', $nameOrPhone);
            })
            ->when(!preg_match("/^\d{11}$/", $nameOrPhone), function ($query) use ($nameOrPhone) {
                return $query->where('name', 'like', '%'.$nameOrPhone.'%');
            });
        $paginator = $query->where('nickname','!=','')->where('avatar','!=','')->orderBy('round','desc')->orderBy('created_at', 'desc')->paginate(10);
        $exportUrl = asset('/vlvl/x200817/export'.$site);
        $redis = app('redis');
        $redis->select(0);
        $redisShareData = $redis->hGetAll('wx:view:yc20200106');

        return view('sswh.sswhAdmin.x200817', [
            'title' => '金地华中·第六届纳凉音乐节'.$site.'抽奖', 'paginator' => $paginator, 'exportUrl' => $exportUrl,
            'redisShareData' => $redisShareData
        ]);
    }

    public function export_site1_1()
    {
        return Excel::download(new X200817Site1_1Export, '金地华中·第六届纳凉音乐节场地一第一场名单.xlsx');
    }

    public function export_site1_2()
    {
        return Excel::download(new X200817Site1_2Export, '金地华中·第六届纳凉音乐节场地一第二场名单.xlsx');
    }

    public function export_site2_1()
    {
        return Excel::download(new X200817Site2_1Export, '金地华中·第六届纳凉音乐节场地二第一场名单.xlsx');
    }

    public function export_site2_2()
    {
        return Excel::download(new X200817Site2_2Export, '金地华中·第六届纳凉音乐节场地二第二场名单.xlsx');
    }


    /*
     * 获取当前抽奖轮数
     */
    public function site1Round(Request $request)
    {
        if ($request->zc != 'x200817site1') {
            return redirect("https://www.baidu.com");
        }
        $round = Site1_1User::getRound();
        return view('sswh.sswhAdmin.x200817_site1', ['title' => '金地华中·第六届纳凉音乐节场地一抽奖', 'round' => $round]);
    }

    /*
 * 获取当前抽奖轮数
 */
    public function site2Round(Request $request)
    {
        if ($request->zc != 'x200817site2') {
            return redirect("https://www.baidu.com");
        }
        $round = Site2_1User::getRound();
        return view('sswh.sswhAdmin.x200817_site2', ['title' => '金地华中·第六届纳凉音乐节场地二抽奖', 'round' => $round]);
    }

    public function setSite1Round(Request $request)
    {
        $round = $request->round;
        $res = Site1_1User::setRound($round);
        if ($res && $round == 0) {
            return '停止';
        } else {
            return '开启';
        }
    }

    public function setSite2Round(Request $request)
    {
        $round = $request->round;
        $res = Site2_1User::setRound($round);
        if ($res && $round == 0) {
            return '停止';
        } else {
            return '开启';
        }
    }
}
