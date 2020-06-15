<?php


namespace App\Console\Commands;


use App\Exports\Tdr\X191217Export;
use App\Models\China;
use App\Models\Ticket\L191127\Ticket;
use App\Models\Ticket\L191127\User;
use Illuminate\Console\Command;

class X191217 extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan ticket_l191127:cmd check
     *  php artisan ticket_l191127:cmd sendredpack
     * @var string
     */
    protected $signature = 'x191217:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '自动分配发红包 【in 小时光面馆】';

    const DEBUG = true;

    const TIME = ['2020-01-27 00:00:00', '2020-02-08 23:59:59'];


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
            case "export":  //导出数据到Excel
                $this->exportHandler();
                break;
            case "sendRedPack":
                $this->sendRedPackHandler();
            case "see":
                $this->count();
                break;
            default:
                echo '默认';
        }
    }


    public function sendRedPackHandler()
    {
        $ticketClient = new Ticket();
        $redisClient = app('redis');
        $redisClient->select(12);
        if (self::DEBUG) echo "开始for循环 \n";
        $limitRedPack = 218;
        //只查询审核通过的
        $redPacks = Ticket::where('check_status', 24)->where('prize_at', '>', '2019-12-30 23:59:59')->limit($limitRedPack)->get();
        foreach ($redPacks as $redPack) {

            //更改状态为红包发送中  防止别的程序同时更改状态
            if (!Ticket::where('id', $redPack->id)->where('check_status', 24)->update(['check_status' => 20])) {
                continue;
            }
            try {
                //开始发送红包
                $user = User::find($redPack->user_id);
                $result = $ticketClient->redpack($user->openid, 1, $user->id);
                if (self::DEBUG) print_r($result);
                if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
                    $resCheckStatus = 21; //红包发送成功状态码
                    $result_redpack_msg = '红包发放成功';
                    $finalMoney = $result['total_amount'];
                    $result_redpack_desc = $result['return_msg'];
                } else {
                    $resCheckStatus = 22; //红包发送失败状态码
                    $result_redpack_msg = '红包发放失败';
                    $finalMoney = 0;
                    $result_redpack_desc = $result['return_msg'];
                }
                //保存信息
                $redPack->fill([
                    'check_status' => $resCheckStatus,
                    'money' => $finalMoney,
                    'result_redpack_msg' => $result_redpack_msg,
                    'result_redpack_desc' => $result_redpack_desc,
                    'result_redpack' => json_encode($result, JSON_UNESCAPED_UNICODE),
                    'prize_at' => now()->toDateTimeString()
                ]);
                $redPack->save();
                echo 222;
                //更新 用户信息
                if ($resCheckStatus == 21) {
                    $user->prize_num++;
                    $user->total_money += $result['total_amount'];
                    $user->save();
                }
            } catch (\Exception $e) {
                $redPack->status = 23; //红包发送失败
                $redPack->red_money = 0;
                $redPack->redpack_return_msg = '红包发送异常';
                $redPack->save();
            }
        }
    }

    public function dataHandler()
    {
        $dateStr = substr(self::TIME[0], 0, 10) . '至' . substr(self::TIME[1], 0, 10);
        var_dump($dateStr);
    }

    public function exportHandler()
    {
        $export = $this->test();
        $dateStr = substr(self::TIME[0], 0, 10) . '至' . substr(self::TIME[1], 0, 10);
        return \Excel::store(new X191217Export($export), '汤达人汇总' . $dateStr . '.xlsx', 'public');
    }

    public function test()
    {
        $data = $this->count(self::TIME);
        $export = [];
        $export[] = [
            'sheng' => $data['other']['name'],
            'shi' => '/',
            'qu' => '/',
            'people' => $data['other']['people'],
            'upload_num' => $data['other']['upload_num'],
            'valid_num' => $data['other']['valid_num'],
            'redpack_num' => $data['other']['redpack_num'],
            'total_money' => $data['other']['total_money'],
        ];
        $export[] = [
            'sheng' => $data['none']['name'],
            'shi' => '/',
            'qu' => '/',
            'people' => $data['none']['people'],
            'upload_num' => $data['none']['upload_num'],
            'valid_num' => $data['none']['valid_num'],
            'redpack_num' => $data['none']['redpack_num'],
            'total_money' => $data['none']['total_money'],
        ];
        $export[] = [
            'sheng' => $data['name'],
            'shi' => '/',
            'qu' => '/',
            'people' => $data['people'],
            'upload_num' => $data['upload_num'],
            'valid_num' => $data['valid_num'],
            'redpack_num' => $data['redpack_num'],
            'total_money' => $data['total_money'],
        ];
        foreach ($data['child'] as $vv) {
            $export[] = [
                'sheng' => $data['name'],
                'shi' => $vv['name'],
                'qu' => '/',
                'people' => $vv['people'],
                'upload_num' => $vv['upload_num'],
                'valid_num' => $vv['valid_num'],
                'redpack_num' => $vv['redpack_num'],
                'total_money' => $vv['total_money'],
            ];
            foreach ($vv['child'] as $vvv) {
                $export[] = [
                    'sheng' => $data['name'],
                    'shi' => $vv['name'],
                    'qu' => $vvv['name'],
                    'people' => $vvv['people'],
                    'upload_num' => $vvv['upload_num'],
                    'valid_num' => $vvv['valid_num'],
                    'redpack_num' => $vvv['redpack_num'],
                    'total_money' => $vvv['total_money'],
                ];
            }
        }

        return $export;
    }

    public function count($time)
    {
        $hubei = [];
        $hubei['name'] = '湖北省';
        $hubei['people'] = 0;
        $hubei['upload_num'] = 0;
        $hubei['valid_num'] = 0;
        $hubei['redpack_num'] = 0;
        $hubei['total_money'] = 0;
        $hubei['child'] = [];
        $china = new China();
        $area = China::where('pid', 420000)->get()->toArray();
        foreach ($area as $k => $a) {
            $area[$k]['people'] = 0;
            $area[$k]['upload_num'] = 0;
            $area[$k]['valid_num'] = 0;
            $area[$k]['redpack_num'] = 0;
            $area[$k]['total_money'] = 0;
            $area[$k]['child'] = [];
            foreach ($china->getAllChildArea($a['id']) as $c) {
                $child = China::find($c)->toArray();
                //参与人数
                $child['people'] = \DB::select('select count(*) as total from (select count(*) from ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code ) a group by user_id) b',
                    [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $c])[0]->total;
                //上传小票
                $child['upload_num'] = \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code ) a',
                    [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $c])[0]->total;
                //合格小票
                $child['valid_num'] = \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (11, 20, 21, 22) ) a',
                    [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $c])[0]->total;
                //成功发送红包数量
                $child['redpack_num'] = \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (21) ) a',
                    [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $c])[0]->total;
                //发送红包金额
                $money = \DB::select('select sum(money) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (21) ) a',
                    [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $c])[0]->total;
                $child['total_money'] = ($money / 100) ?? 0;
                //统计市的
                $area[$k]['people'] += $child['people'];
                $area[$k]['upload_num'] += $child['upload_num'];
                $area[$k]['valid_num'] += $child['valid_num'];
                $area[$k]['redpack_num'] += $child['redpack_num'];
                $area[$k]['total_money'] += $child['total_money'];
                $area[$k]['child'][] = $child;
            }
            $hubei['people'] += $area[$k]['people'];
            $hubei['upload_num'] += $area[$k]['upload_num'];
            $hubei['valid_num'] += $area[$k]['valid_num'];
            $hubei['redpack_num'] += $area[$k]['redpack_num'];
            $hubei['total_money'] += $area[$k]['total_money'];
        }
        /*
         *
        $hubei['name'] = '湖北省';
        $hubei['people'] = 0;
        $hubei['upload_num'] = 0;
        $hubei['valid_num'] = 0;
        $hubei['redpack_num'] = 0;
        $hubei['total_money'] = 0;
         * */
        $hubei['child'] = $area;
        $area2 = $china->getAllChildArea(420000);

        $codeStr = implode(',', $area2) . ',-1';
        $money = \DB::select('select sum(money) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code not in ( :code ) and check_status in (21) ) a',
            [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $codeStr])[0]->total;
        $hubei['other'] = [
            'name' => '其他地区',
            'people' => \DB::select('select count(*) as total from (select count(*) from ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and (address_code not in ( :code )) ) a group by user_id) b',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $codeStr])[0]->total,
            'upload_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code not in ( :code ) ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $codeStr])[0]->total,
            'valid_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code not in ( :code )  and check_status in (11, 20, 21, 22) ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $codeStr])[0]->total,
            'redpack_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code not in ( :code ) and check_status in (21) ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => $codeStr])[0]->total,
            'total_money' => ($money / 100) ?? 0,
        ];
        $money = \DB::select('select sum(money) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (21) ) a',
            [':starttime' => $time[0], ':endtime' => $time[1], ':code' => -1])[0]->total;
        $hubei['none'] = [
            'name' => '空白地区',
            'people' => \DB::select('select count(*) as total from (select count(*) from ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code ) a group by user_id) b',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => -1])[0]->total,
            'upload_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => -1])[0]->total,
            'valid_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (11, 20, 21, 22) ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => -1])[0]->total,
            'redpack_num' => \DB::select('select count(*) as total from  ( select * from auto_check_v3_tickets where (created_at between :starttime and :endtime) and address_code=:code and check_status in (21) ) a',
                [':starttime' => $time[0], ':endtime' => $time[1], ':code' => -1])[0]->total,
            'total_money' => ($money / 100) ?? 0,
        ];
        return $hubei;
    }


}
