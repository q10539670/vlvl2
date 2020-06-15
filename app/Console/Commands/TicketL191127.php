<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket\L191127\Ticket as Ticket;
use App\Models\Ticket\L191127\User as User;
use App\Models\Ticket\L191127\Admin;

class TicketL191127 extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan ticket_l191127:cmd check
     *  php artisan ticket_l191127:cmd sendredpack
     * @var string
     */
    protected $signature = 'ticket_l191127:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动审核小票服务 【in 小时光面馆】';

    const DEBUG = true;
    const SYMBOLS = '&&';
    const IMG_PREFIX = 'https://wx.sanshanwenhua.com/vlvl/storage2';

    //自动识别配置
    protected $checkConf = [
        //黑名单验证
        'blacklist' => [
            'err_msg' => '账号进入黑名单',
        ],
        //活动地区验证
        'active_area' => [
            'err_msg' => '小票非本次活动区域内',
        ],
        //关键字
        'keywords' => [
            'words' => '汤达人',
            'err_msg' => '小票与本次活动无关'
        ],
        //日期
        'date' => [
            'err_msg' => '小票日期与上传日期不符',
        ],
        //重复上传
        'repeat' => [
            'err_msg' => '小票重复上传',
        ],
    ];

    protected $baiduAiConf = [
        'appId' => '15796769',
        'apiKey' => '1gjNqPdDXbNcc14RPzg2DREx',
        'sercetKey' => 's1dSAkGtjcmbfAq1Ahlx6AY0mDYC42gs',
    ];

    protected $acitveCityConf = null;
    //http client
    protected $client = null;

    //locationClient
    protected $loctionClient = null;

    /*审核状态码*/
    public static $checkStatus = [
        0 => '未审核',
        10 => '审核中',
        11 => '通过 【已审核】',
        12 => '不通过 【已审核】',
        13 => '审核失败',
        20 => '红包发送中',
        21 => '红包发送 【成功】',
        22 => '红包发送 【失败】',
        23 => '抽奖失败',
        24 => '当日红包已发完'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $handle = $this->argument('handle');
        switch ($handle) {
            case "check":  // 只审核识别小票
                echo "开始自动审核\n";
                $this->checkHandler();
                break;
            case "sendredpack":  // 只发红包
                echo "开始发送红包\n";
                $this->sendredpackHandler();
                break;
            case "recheckFail":  //重置审核失败的红包
                $this->recheckFailHandler();
                break;
            case "export":  //导出数据到Excel
                $this->exportHandler();
                break;
            default:
                echo '默认';
        }
    }

    public function recheckFailHandler()
    {
        Ticket::where('check_status', 13)->update(['check_status' => 0]);
        echo "重置错误状态成功";
    }

    /*
     * 发送红包
     * */
    public function sendredpackHandler()
    {
        $limitSecond = 7;
        $num = 60 / $limitSecond - 1;
        $nowTime = time();
        $stopTime = $nowTime + 60 - $limitSecond;
        $ticketClient = new Ticket();
        $redisClient = app('redis');
        $redisClient->select(12);
        if (self::DEBUG) echo "开始for循环 \n";
        for ($i = 0; $i < $num; $i++) {
            if (time() > $stopTime) {
                break;
            }
            $limitTicket = 5;
            //只查询审核通过的
            $tickets = Ticket::where('check_status', 11)->orderBy('checked_at', 'asc')->limit($limitTicket)->get();
            if ((count($tickets) == $limitTicket) && ($limitSecond > 3)) {
                $limitSecond -= 3;
            } else if ($limitSecond < 7) {
                $limitSecond += 3;
            }
            foreach ($tickets as $ticket) {
//                $key = Admin::$itemPrefix . '_login:' . 'service';
//                if ($redisClient->exists($key)) {
//                    if ($redisClient->hget($key, 'redpack') != 1) {
//                        continue;
//                    }
//                }
                //更改状态为红包发送中  防止别的程序同时更改状态
                if (!Ticket::where('id', $ticket->id)->where('check_status', 11)->update(['check_status' => 20])) {
                    continue;
                }
                try {
                    //抽奖
                    $prizeReuslt = $ticket->randomPrizeCreatedAt(); //获取抽奖结果
                    var_dump($prizeReuslt);
                    if ($prizeReuslt['resultPrize']['money'] <= 0) {
                        $resCheckStatus = 24;
                        $ticket->fill([
                            'check_status' => 24,
                            'money' => 0,
                            'result_redpack_msg' => '当日红包已发完',
                            'result_redpack_desc' => '当日红包已发完',
                            'result_redpack' => '当日红包已发完',
                            'prize_at' => now()->toDateTimeString()
                        ]);
//                        $ticket->save();
                    } else {
                        //开发发送红包
                        $user = User::find($ticket->user_id);
                        $result = $ticketClient->redpack($user->openid, $prizeReuslt['resultPrize']['money'], $user->id);
                        if (self::DEBUG) print_r($result);
                        if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                            $resCheckStatus = 21; //红包发送成功状态码
                            $result_redpack_msg = '红包发放成功';
                            $finalMoney = $prizeReuslt['resultPrize']['money'];
                            $result_redpack_desc = $result['return_msg'];
                        } else {
                            $resCheckStatus = 22; //红包发送失败状态码
                            $result_redpack_msg = '红包发放失败';
                            $finalMoney = 0;
                            $result_redpack_desc = $result['return_msg'];
                        }
                        //保存信息
                        $ticket->fill([
                            'check_status' => $resCheckStatus,
                            'money' => $finalMoney,
                            'result_redpack_msg' => $result_redpack_msg,
                            'result_redpack_desc' => $result_redpack_desc,
                            'result_redpack' => json_encode($result, JSON_UNESCAPED_UNICODE),
                            'prize_at' => now()->toDateTimeString()
                        ]);

                    }

                    $ticket->save();
                    echo 222;
                    //更新 用户信息
                    if ($resCheckStatus == 21) {
                        $user->prize_num++;
                        $user->total_money += $prizeReuslt['resultPrize']['money'];
                        $user->save();
                    }
                } catch (\Exception $e) {
                    echo ($e->getMessage());
                    $ticket->check_status = 23; //红包发送失败
                    $ticket->result_redpack_msg = '红包发送异常';
                    $ticket->save();
                }
            }
            sleep($limitSecond);
        }
    }

    /*
     * 自动审核
     * */
    public function checkHandler()
    {
        require app_path('Packages/baidu/') . 'AipOcr.php';
        $this->client = new \AipOcr($this->baiduAiConf['appId'], $this->baiduAiConf['apiKey'], $this->baiduAiConf['sercetKey']);
//        $this->acitveCityConf = require app_path('Http/Controllers/Ticket/L191127') . '/TicketL191127ConfV2.php';
        $this->acitveCityConf = $this->getAreaIdArr();
        $this->loctionClient = new \GuzzleHttp\Client;
        $limitSecond = 7;
        $num = 60 / $limitSecond - 1;
        $nowTime = time();
        $stopTime = $nowTime + 60 - $limitSecond;
        for ($i = 0; $i < $num; $i++) {
            if (time() > $stopTime) {
                break;
            }
            $limitTicket = 5;
            $tickets = Ticket::where('check_status', 0)->orderBy('created_at', 'asc')->limit($limitTicket)->get();
            if ((count($tickets) == $limitTicket) && ($limitSecond > 3)) {
                $limitSecond -= 3;
            } else if ($limitSecond < 7) {
                $limitSecond += 3;
            }
            foreach ($tickets as $ticket) {
                try {
                    $this->validationTicket($ticket);
                } catch (\Exception $e) {
//                    print_r($e->getMessage());
                    $ticket->check_status = 13; //审核失败
                    $ticket->checked_at = now()->toDateTimeString();
                    $ticket->save();
                    continue;
                }
                sleep(1);
            }
            sleep($limitSecond);
        }
    }

    /*
     * 审核单张小票
     * */
    protected function validationTicket($ticket)
    {
        if (!Ticket::where(['id' => $ticket->id, 'check_status' => 0])->update(['check_status' => 10])) {
            return;
        }
        $user = User::find($ticket->user_id);
        $result = [
            'status' => 0,
            'msgArr' => [],
            'msg' => '',
        ];
        $url = self::IMG_PREFIX . '/' . $ticket['img_url'];
        if (self::DEBUG) print_r($url);
        $res = $this->client->basicGeneralUrl($url);  //获取识别结果
        if (self::DEBUG) print_r($res);
        //如果可以识别到文字
        if ($res['words_result_num'] > 0) {
            $wordsResultArr = [];
            foreach ($res['words_result'] as $wd) {
                $wordsResultArr[] = $wd['words'];
            }
            $allWords = implode(self::SYMBOLS, $wordsResultArr); //拼接所有识别字符
            $jsonAllWords = json_encode($wordsResultArr, JSON_UNESCAPED_UNICODE);
            $hasWords = md5($jsonAllWords);
            foreach ($this->checkConf as $name => $conf) {
                switch ($name) {
                    case "blacklist":     //检测黑名单
                        if ($user->status == 2) {
                            $result['msgArr'][] = $this->checkConf['blacklist']['err_msg'];
                            if (self::DEBUG) echo "黑名单检测: 不通过\n";
                        } else {
                            $result['status']++;
                            if (self::DEBUG) echo "黑名单检测: 通过\n";
                        }
                        break;
                    case 'active_area':   //检测定位
                        if (!$this->validateLocation($ticket, $user)) { //不通过定位检测
                            $result['msgArr'][] = $this->checkConf['active_area']['err_msg'];
                            if (self::DEBUG) echo "定位地区检测: 不通过\n";
                        } else {
                            $result['status']++;
                            if (self::DEBUG) echo "定位地区检测: 通过\n";
                        }
                        //地理位置写入小票
                        $lastUser = User::find($ticket->user_id);
                        $ticket->address_str = $user->address_str;
                        $ticket->address_code = $user->address_code;
                        $ticket->save();
                        break;
                    case "keywords":   //检测关键字
                        if (preg_match('/' . $conf['words'] . '/', $allWords)) {
                            $result['status']++;
                            if (self::DEBUG) echo "关键字检测: 通过\n";
                        } else {
                            $result['msgArr'][] = $this->checkConf['keywords']['err_msg'];
                            if (self::DEBUG) echo "关键字检测: 不通过\n";
                        }
                        break;
                    case "date":       //检测日期
//                        $dateStr = '/' . date('Y.*m.*d', strtotime($ticket->created_at)) . '/';
                        $time = strtotime($ticket->created_at);
                        $year = date('Y', $time);
                        $month = (int)date('m', $time);
                        $day = (int)date('d', $time);
                        $rule = '/' . $year . '.{0,3}[0]?' . $month . '.{0,3}[0]?' . '.{0,3}[0]?' . $day . '/';
                        if (preg_match($rule, $allWords)) {
                            $result['status']++;
                            if (self::DEBUG) echo "上传日期检测: 通过\n";
                        } else {
                            $result['msgArr'][] = $this->checkConf['date']['err_msg'];
                            if (self::DEBUG) echo "上传日期检测: 不通过\n";
                        }
                        break;
                    case "repeat":     //检测是否重复
                        if (Ticket::where('pic_hash', $hasWords)->first()) {
                            $result['msgArr'][] = $this->checkConf['repeat']['err_msg'];
                            if (self::DEBUG) echo "小票重复上传检测: 不通过\n";
                        } else {
                            $result['status']++;
                            if (self::DEBUG) echo "小票重复上传检测: 通过\n";
                        }
                        break;
                }
            }
            if (count($this->checkConf) != $result['status']) {
                $checkStatus = 12;
                $result_msg = '不通过【自动审核】';
                $result_desc = implode(self::SYMBOLS, $result['msgArr']);
            } else {
                $checkStatus = 11;
                $result_msg = '通过【自动审核】';
                $result_desc = '';
            }
            Ticket::where('id', $ticket->id)->update([
                'check_status' => $checkStatus,
                'result_check_msg' => $result_msg,
                'result_check_desc' => $result_desc,
                'pic_hash' => $hasWords,
                'pic_words' => $jsonAllWords,
                'checked_at' => now()->toDateTimeString()
            ]);
            return;
        }
        if (self::DEBUG) echo "没有识别到文字 \n";
        //没有识别到文字
        Ticket::where('id', $ticket->id)->update([
            'check_status' => 12,  //状态码
            'result_check_msg' => '不通过【自动审核】',
            'result_check_desc' => '系统无法识别该小票',
            'checked_at' => now()->toDateTimeString()
        ]);
        return;
    }

    //验证 当前小品用户 当前是否在活动区域
    protected function validateLocation($ticket, $user)
    {
        if ($user->location == '') {
            return false;
        }
        if ($user->is_active_area == 1) {
            return true;
        }
        // 判断是否重新解析地理位置  并重新解析
        if ($this->isReparse($user->is_active_area, $user->last_location_at)) {
            if (self::DEBUG) echo "重新解析定位 \n";
            $url = "https://apis.map.qq.com/ws/geocoder/v1/?key=P3FBZ-OVJK5-OYPII-QKKVX-MNWZE-GIBNQ&location={$user->location}";
            $res = $this->loctionClient->request('GET', $url);
            $result = json_decode($res->getBody()->getContents(), true);
//            print_r($result);
            if (isset($result['status']) && $result['status'] == 0) {
                try {
                    $user->address_str = $result['result']['address'];
                    $user->address_code = $result['result']['ad_info']['adcode'];
//                    $ticket->address_str = $result['result']['address'];
//                    $ticket->address_code = $result['result']['ad_info']['adcode'];
                } catch (\Exception $e) {
                    $user->address_code = -1;
//                    $ticket->address_code = -1;
                }
            } else {
                $user->address_code = -1;
//                $ticket->address_code = -1;
            }
            $isActiveAreaCityStatus = $this->inActiveAreaCity(intval($user->address_code));
            $user->is_active_area = $isActiveAreaCityStatus ? 1 : 2;
            $user->last_location_at = now()->toDateTimeString();
            $user->save();
//            $ticket->save();
            if ($user->address_code == -1) {
                return false;
            }
            if ($isActiveAreaCityStatus) {
                return true;
            }
            return false;
        }
        return false;
    }

    //判断是否需要重新解析地址
    public function isReparse($isActiveArea, $lastLocationAt)
    {
        if ($isActiveArea == 0) {
            return true;
        }
        if ($isActiveArea == 1) {
            return false;
        }
        if ($isActiveArea == 2 && (substr($lastLocationAt, 0, 10) != date('Y-m-d'))) {
            return true;
        }
        return false;
    }

    //是否在活动区域城市
    protected function inActiveAreaCity($cityId)
    {
        foreach ($this->acitveCityConf as $c) {
            if (in_array($cityId, $c)) {
                return true;
            }
        }
        return false;
    }

    //导出小票
    protected function exportHandler()
    {
        $checkStatus = [
            0 => '未审核',
            10 => '审核中',
            11 => '通过 【已审核】',
            12 => '不通过 【已审核】',
            13 => '审核失败',
            20 => '红包发送中',
            21 => '红包发送 【成功】',
            22 => '红包发送 【失败】',
            23 => '抽奖失败'
        ];
        $limit = 1000;
        $offset = 0;
        $page = 0;
        $count = Ticket::count();
        $totalPage = ceil($count / $limit);
        $data = [];
        for (; $page < $totalPage; $page++) {
            $res = Ticket::with('user')
                ->orderBy('created_at', 'desc')
                ->offset($offset * $page)->limit($limit)
                ->get()
                ->map(function ($item, $key) use ($checkStatus) {
                    $word_str = '';
                    if (!empty($item['pic_words'])) {
                        foreach (json_decode($item['pic_words'], true) as $k => $s) {
                            if ($k > 0) $word_str .= '||';
                            $word_str .= $s;
                        }
                    }
                    $sendList = '';
                    if (!empty($item['result_redpack'])) {
                        $sendList = json_decode($item['result_redpack'], true)['send_listid'] ?? '';
                    }
                    return [
                        '小票ID' => $item['id'],
                        '用户ID' => $item['user_id'],
                        '微信昵称' => '`' . $item['user']['nickname'],
                        '审核结果' => $item['result_check_msg'],
                        '审核结果详情' => $item['result_check_desc'],
                        '红包发送结果' => $item['result_redpack_desc'],
                        '微信红包单号' => $sendList,
                        '金额(元)' => '`' . ($item['money'] / 100),
                        '小票识别到的文字' => '`' . $word_str,
                        '位置' => $item['address_str'],
                        '小票上传时间' => $item['created_at'],
                        '审核时间' => $item['checked_at'],
                        '微信头像' => $item['user']['avatar'],
                        '小票地址' => env('APP_URL') . '/storage2/' . $item['img_url'],
                    ];
                });
            $data[] = $res;
        }
        \Excel::create('统一老坛酸菜小票_所有小票数据', function ($excel) use ($data) {
            foreach ($data as $k => $page) {
                $excel->sheet('第' . ($k + 1) . '页', function ($sheet) use ($page) {
                    $sheet->fromArray($page);
                });
            }
        })->store('xls');
//            ->download('xls');
    }

    public function getAreaIdArr()
    {
        return
            [
                [
                429021,
                429006,
                429005,
                429004,
                429000,
                422828,
                422827,
                422826,
                422825,
                422823,
                422822,
                422802,
                422801,
                422800,
                421381,
                421321,
                421303,
                421301,
                421300,
                421281,
                421224,
                421223,
                421222,
                421221,
                421202,
                421201,
                421200,
                421182,
                421181,
                421127,
                421126,
                421125,
                421124,
                421123,
                421122,
                421121,
                421102,
                421101,
                421100,
                421087,
                421083,
                421081,
                421024,
                421023,
                421022,
                421003,
                421002,
                421001,
                421000,
                420984,
                420982,
                420981,
                420923,
                420922,
                420921,
                420902,
                420901,
                420900,
                420881,
                420822,
                420821,
                420804,
                420802,
                420801,
                420800,
                420704,
                420703,
                420702,
                420701,
                420700,
                420684,
                420683,
                420682,
                420626,
                420625,
                420624,
                420607,
                420606,
                420602,
                420601,
                420600,
                420583,
                420582,
                420581,
                420529,
                420528,
                420527,
                420526,
                420525,
                420506,
                420505,
                420504,
                420503,
                420502,
                420501,
                420500,
                420381,
                420325,
                420324,
                420323,
                420322,
                420304,
                420303,
                420302,
                420301,
                420300,
                420281,
                420222,
                420205,
                420204,
                420203,
                420202,
                420201,
                420200,
                420117,
                420116,
                420115,
                420114,
                420113,
                420112,
                420111,
                420107,
                420106,
                420105,
                420104,
                420103,
                420102,
                420101,
                420100,
                420000
            ]
        ];
    }

}
