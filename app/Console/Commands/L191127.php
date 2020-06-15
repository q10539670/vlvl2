<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket\L191127\ActivityOne as ActOne;
use App\Models\Ticket\L191127\ActivityTwo as ActTwo;
use App\Models\Ticket\L191127\User;


class L191127 extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan ticket_l191127:cmd check
     *  php artisan ticket_l191127:cmd sendredpack
     * @var string
     */
    protected $signature = 'l191127:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动分配发红包 【in 小时光面馆】';

    const DEBUG = true;

    const ACT_1 = ['2019-12-09 00:00:00', '2019-12-31 23:59:59'];

    const ACT_2 = ['2019-12-10 00:00:00', '2020-01-25 23:59:59'];

    /*红包状态码*/
    public static $status = [
        0 => '未分配',
        10 => '分配中',
        11 => '确认分配',
        20 => '红包发送中',
        21 => '红包发送 【成功】',
        22 => '红包发送 【失败】',
        23 => '红包发送 【异常】'
    ];

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
            case "testSendRedPack":
                echo "测试发红包\n";
                $this->test();
                break;
            case "insertAct1Users":  // 写入用户
                echo "开始写入活动一用户\n";
                $this->insertAct1UsersHandler();
                break;
            case "disAct1Redpack":  // 分配红包
                echo "开始分配红包\n";
                $this->disAct1RedpackHandler();
                break;
            case "sendAct1RedPack":  // 只发红包
                echo "开始发送红包\n";
                $this->sendAct1RedPackHandler();
                echo "红包发送完成\n";
                break;
            case "insertAct2Users":  // 写入用户
                echo "开始写入元气实物奖用户\n";
                $this->insertAct2UsersHandler();
                echo "元气实物奖用户写入成功\n";
                break;
            case "disAct2RedPack":  // 分配红包
                echo "开始分配元气实物奖奖品\n";
                $this->disAct2RedpackHandler();
                echo "元气实物奖奖品分配完成\n";
                break;
            case "sendAct2RedPack":  // 只发红包
                echo "开始发送红包\n";
                $this->sendAct2RedPackHandler();
                echo "红包发送完成\n";
                break;
            default:
                echo '默认';
        }
    }

    public function test()
    {
        $actOneClient = new ActTwo();
        $user = User::find(15748);
        $result = $actOneClient->redpack('o0-aavzMSEkDVMB7PxHQFUcriKSA', 121, $user->id);
        var_dump($result);
    }
    //开始写入活动一用户
    public function insertAct1UsersHandler()
    {
        $blackLists = [632];  //排除中大奖名单
        $allUsers = User::where('status', 1)->where('prize_num', '>', 0)->whereBetween('created_at', [self::ACT_1])->get()->toArray();
        foreach ($allUsers as $allUser) {
            if ($allUser['prize_num'] > 3) {
                $data = [
                    'user_id' => $allUser['id'],
                    'badge' => 1,          //标记为可以抽中大奖
                ];
            } else {
                $data = [
                    'user_id' => $allUser['id'],
                ];
            }
            ActOne::insert($data);
        }
        $superUsers = ActOne::where('badge',1)->whereNotIn('user_id',$blackLists)->get()->toArray();
        shuffle($superUsers);
        foreach ($superUsers as $key => $superUser) {
            if ($key < 130) {
                $user = ActOne::where('user_id',$superUser['user_id'])->first();
                $user->badge = 2;
                $user->save();
            }
        }
    }


    //开始分配活动一红包
    public function disAct1RedPackHandler()
    {
        $userNum = ActOne::where('status', 0)->whereNotIn('badge', [2])->count();
        $money = ceil(444800/$userNum);
//        //分配前130名
        if (ActOne::where('badge', 2)->where('status', 0)->count() != 0) {
            for ($i = 0; $i < 130; $i++) {
                $sortUser = ActOne::where('badge', 2)->where('status', 0)->first();
                $actOne = new ActOne();
                $redPacks = $actOne->randomPrize();
                $sortUser->money = $redPacks['resultPrize']['money'];
                $sortUser->status = 10; //分配奖品中
                $sortUser->save();
            }
        }
        //分配其他的
        if ($userNum != 0) {
            for ($i = 0; $i < $userNum; $i++) {
                $sortUser = ActOne::where('badge','!=', 2)->where('status', 0)->first();
                $sortUser->money = $money;
                $sortUser->status = 10; //分配奖品
                $sortUser->save();
            }
        }
    }

    //开始发送活动一用户红包
    public function sendAct1RedPackHandler()
    {
        $actOneClient = new ActOne();
        $redisClient = app('redis');
        $redisClient->select(12);
        if (self::DEBUG) echo "开始for循环 \n";
        $limitRedPack = 1000;
        //只查询审核通过的
        $redPacks = ActOne::where('status', 10)/*->orderBy('checked_at', 'asc')*/->limit($limitRedPack)->get();
        foreach ($redPacks as $redPack) {

            //更改状态为红包发送中  防止别的程序同时更改状态
            if (!ActOne::where('id', $redPack->id)->where('status', 10)->update(['status' => 20])) {
                continue;
            }
            try {
                //开始发送红包
                    $user = User::find($redPack->user_id);
                $result = $actOneClient->redpack($user->openid, $redPack->money, $user->id);
                if (self::DEBUG) print_r($result);
                if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                    $resStatus = 21; //红包发送成功状态码
                    $redpack_return_msg = '红包发放成功';
                    $finalMoney = $redPack->money;
                } else {
                    $resStatus = 22; //红包发送失败状态码
                    $redpack_return_msg = '红包发放失败';
                    $finalMoney = 0;
                }
                //保存信息
                $redPack->fill([
                    'status' => $resStatus,
                    'red_money' => $finalMoney,
                    'redpack_return_msg' => $redpack_return_msg,
                    'redpack_discribe' => json_encode($result, JSON_UNESCAPED_UNICODE),
                    'prized_at' => now()->toDateTimeString()
                ]);
                $redPack->save();
            } catch (\Exception $e) {
                $redPack->status = 23; //红包发送失败
                $redPack->red_money = 0;
                $redPack->redpack_return_msg = '红包发送异常';
                $redPack->save();
            }
        }
    }

    //开始写入活动二用户
    public function insertAct2UsersHandler()
    {
        //获取第一波中奖用户ID
        $prizeUserIds = ActOne::where('badge', 2)->get('user_id')->toArray();
        $prizeUserIds = array_column($prizeUserIds, 'user_id');
        //查询符合要求的用户,写入
        $users = User::where('status', 1)->where('prize_num', '!=', 0)->whereBetween('updated_at', [self::ACT_2])->get()->toArray();
        foreach ($users as $key => $user) {
                if ($user['prize_num'] = 1) {
                    $data = [
                        'user_id' => $user['id'],
                        'badge' => 1,          //标记为可以抽中大奖
                    ];
                } else {
                    $data = [
                        'user_id' => $user['id'],
                    ];
                }
            ActTwo::insert($data);
        }
        $superUsers = ActTwo::where('badge',1)->whereNotIn('user_id',$prizeUserIds)->get()->toArray();
        shuffle($superUsers);
        foreach ($superUsers as $key => $superUser) {
            if ($key < 141) {
                $user = ActTwo::where('user_id',$superUser['user_id'])->first();
                $user->badge = 2;
                $user->save();
            }
        }

    }

    //开始分配活动二红包
    public function disAct2RedPackHandler()
    {
        $userNum = ActTwo::where('status', 0)->where('badge','!=', 2)->count();
        //平均分配的金额
        $money = ceil(1444800/$userNum);
//        //分配前141名
        if (ActTwo::where('badge', 2)->where('status', 0)->count() != 0) {
            for ($i = 0; $i < 141; $i++) {
                $sortUser = ActTwo::where('badge', 2)->where('status', 0)->first();
                $actTwo = new ActTwo();
                $redPacks = $actTwo->randomPrize();
                if ($redPacks['resultPrize']['money'] < 3) { //中实物奖品
                    $sortUser->prize = $redPacks['resultPrize']['prize'];
                }
                $sortUser->money = $redPacks['resultPrize']['money'];
                $sortUser->status = 10; //分配奖品中
                $sortUser->save();
            }
        }
        //分配其他的
        if ($userNum != 0) {
            for ($i = 0; $i < $userNum; $i++) {
                $sortUser = ActTwo::where('badge','!=', 2)->where('status', 0)->first();
                $sortUser->money = $money;
                $sortUser->status = 10; //分配奖品
                $sortUser->save();
            }
        }
    }

    //开始发送活动二用户红包
    public function sendAct2RedPackHandler()
    {
        $redpackClient = new ActTwo();
        $redisClient = app('redis');
        $redisClient->select(12);
        if (self::DEBUG) echo "开始for循环 \n";
        $limitRedPack = 1000;
        //只查询审核通过的
        $redPacks = ActTwo::where('status', 10)->limit($limitRedPack)->get();
        foreach ($redPacks as $redPack) {
            //如果是实物奖品就不发红包
            if ($redPack->money < 3 ){
                ActTwo::where('id', $redPack->id)->where('status', 10)->update([
                    'status' => 21,
                    'red_money' => $redPack->money,
                    'prize_id' =>$redPack->money
                ]);
            } else {
                //更改状态为红包发送中  防止别的程序同时更改状态
                if (!ActTwo::where('id', $redPack->id)->where('status', 10)->update(['status' => 20])) {
                    continue;
                }
                try {
                    //开始发送红包
                    $user = User::find($redPack->user_id);
                    $result = $redpackClient->redpack($user->openid, $redPack->money, $user->id);
                    if (self::DEBUG) print_r($result);
                    if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                        $resStatus = 21; //红包发送成功状态码
                        $redpack_return_msg = '红包发放成功';
                        $finalMoney = $redPack->money;
                    } else {
                        $resStatus = 22; //红包发送失败状态码
                        $redpack_return_msg = '红包发放失败';
                        $finalMoney = 0;
                    }
                    //保存信息
                    $redPack->fill([
                        'status' => $resStatus,
                        'red_money' => $finalMoney,
                        'redpack_return_msg' => $redpack_return_msg,
                        'redpack_discribe' => json_encode($result, JSON_UNESCAPED_UNICODE),
                        'prized_at' => now()->toDateTimeString()
                    ]);
                    $redPack->save();
                } catch (\Exception $e) {
                    $redPack->status = 23; //红包发送失败
                    $redPack->red_money = 0;
                    $redPack->redpack_return_msg = '红包发送异常';
                    $redPack->save();
                }
            }

        }
    }
}
