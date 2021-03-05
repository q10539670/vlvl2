<?php

namespace App\Http\Middleware\Ticket;

use Closure;
use Carbon\Carbon;
use App\Models\Ticket\L191127\Admin;
use App\Models\Ticket\L191127\Ticket as Ticket;

class L191127
{

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*登陆*/
        $routeName = \Route::currentRouteName();
        if (request()->method() == 'GET') {
            if (Admin::validateLogin()) {
                if ($routeName == Admin::$itemPrefix . '.login.index') {
                    return redirect()->route(Admin::$itemPrefix . '.ticket.index');
                } else {
                    $this->siderbar();
                    $this->getServeiceStatus();
                }
            } else {
                if ($routeName != Admin::$itemPrefix . '.login.index') {
                    return redirect()->route(Admin::$itemPrefix . '.login.index');
                }
            }
        }

        return $next($request);
    }

    public function siderbar()
    {
        $menus = $this->getMenu();
        $currentRouteName = \Route::currentRouteName();
        $parrentLabelNameArr = [];
        foreach ($menus as $menu) {
            if (!isset($menu['submenu'])) {
                continue;
            }
            foreach ($menu['submenu'] as $secondMenu) {
                if ($secondMenu['route_name'] == $currentRouteName) {
                    $parrentLabelNameArr[] = $menu['label'];
                    break;
                }
                if (!isset($secondMenu['submenu'])) {
                    continue;
                }
                foreach ($secondMenu['submenu'] as $threeMenu) {
                    if ($threeMenu['route_name'] == $currentRouteName) {
                        $parrentLabelNameArr[] = $secondMenu['label'];
                    }
                }
            }
        }
        view()->share('menus', $menus);
        view()->share('parrentLabelNameArr', $parrentLabelNameArr);
        view()->share('currentRouteName', $currentRouteName);
    }

    public function getServeiceStatus()
    {
        $key = Admin::$itemPrefix . '_login:' . 'service';
        $redis = app('redis');
        $redis->select(12);
        if (!$redis->exists($key)) {
            $redis->hset($key, 'redpack', 0);
        }
        $serviceRedpackStatus = $redis->hget($key, 'redpack');
        view()->share('serviceRedpackStatus', $serviceRedpackStatus);
        view()->share('repackCount', $this->redpackCount());
        view()->share('checkCount', $this->ticketCount());
    }

    protected function getMenu()
    {
        return [
            ['label' => '用户列表', 'route_name' => 'l191127.user.index', 'icon' => 'fa-users',],
            ['label' => '小票列表', 'route_name' => 'l191127.ticket.index', 'icon' => 'fa-file-text-o',],
            ['label' => '元气奖金池', 'route_name' => 'l191127.act1.index', 'icon' => 'fa-money',],
            ['label' => '元气实物奖', 'route_name' => 'l191127.act2.index', 'icon' => 'fa-money',],
//    ['label' => '首页', 'route_name' => 'demo.index', 'icon' => ''],
//    ['label' => '用户管理', 'route_name' => '','icon' => '', 'hidden' => false, 'submenu' => [
//        ['label' => '用户列表', 'route_name' => 'demo.user.index', 'icon' => '', ],
//    ]],
//    ['label' => '小票管理', 'route_name' => '', 'icon' => '', 'submenu' => [
//        ['label' => '小票列表', 'route_name' => 'demo.ticket.index', 'icon' => '',],
//    ]],
        ];
    }

    /*
     * 红包数量统计
     * */
    protected function redpackCount()
    {
        $todayStr = Carbon::today();
        $yesterdayStr = Carbon::yesterday();
        $result = [
            'today' => [
                '100' => Ticket::where('check_status', 21)->whereDate('created_at', $todayStr)->where('money', 100)->count(),
                '200' => Ticket::where('check_status', 21)->whereDate('created_at', $todayStr)->where('money', 200)->count(),
                '500' => Ticket::where('check_status', 21)->whereDate('created_at', $todayStr)->where('money', 500)->count(),
            ],
            'yesterday' => [
                '100' => Ticket::where('check_status', 21)->whereDate('created_at', $yesterdayStr)->where('money', 100)->count(),
                '200' => Ticket::where('check_status', 21)->whereDate('created_at', $yesterdayStr)->where('money', 200)->count(),
                '500' => Ticket::where('check_status', 21)->whereDate('created_at', $yesterdayStr)->where('money', 500)->count(),
            ]
        ];
        $result['today']['totalMoney'] = $result['today']['100'] * 100
            + $result['today']['200'] * 200
            + $result['today']['500'] * 500;
        $result['yesterday']['totalMoney'] = $result['yesterday']['100'] * 100
            + $result['yesterday']['200'] * 200
            + $result['yesterday']['500'] * 500;
        $result['totalMoney'] = Ticket::where('check_status', 21)->sum('money');
        return $result;
    }

    /*
     * 小票统计
     * */
    protected function ticketCount()
    {
        $todayStr = Carbon::today();
        $yesterdayStr = Carbon::yesterday();
        $result = [
            'today' => [
                'total' => Ticket::whereDate('created_at', $todayStr)->count(),
                'notChecked' => Ticket::where('check_status', 0)->whereDate('created_at', $todayStr)->count(),
            ],
            'yesterday' => [
                'total' => Ticket::whereDate('created_at', $yesterdayStr)->count(),
                'notChecked' => Ticket::where('check_status', 0)->whereDate('created_at', $yesterdayStr)->count(),
            ],
            'allNotChecked' => Ticket::where('check_status', 0)->count(),
        ];
        $result['today']['checked'] = $result['today']['total'] - $result['today']['notChecked'];
        $result['yesterday']['checked'] = $result['yesterday']['total'] - $result['yesterday']['notChecked'];
        return $result;
    }
}
