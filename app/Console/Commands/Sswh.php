<?php


namespace App\Console\Commands;


use EasyWeChat\Factory;
use Illuminate\Console\Command;

class Sswh extends Command
{

    /**
     * The name and signature of the console command.
     *  php artisan ticket_l191127:cmd sendredpack
     * @var string
     */
    protected $signature = 'sswh:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发红包 三山文化';

    const DEBUG = true;

    const OPEN_RED_PACK = true;

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
            case "sendRedPack":
                $this->sendRedPackHandler();
            default:
                echo '默认';
        }
    }

    /**
     * 给指定用户发送红包
     */
    public function sendRedPackHandler()
    {
        $money = 1;
        $openId ='oFOht0pPJmQWdIvMeYxKO6yKAbB8';    //徐其阳

        $resultRedPack = $this->sendRedpack($openId,self::OPEN_RED_PACK);
        if (self::DEBUG) print_r($resultRedPack);
        if ($resultRedPack['return_code'] == 'SUCCESS' && $resultRedPack['err_code'] == 'SUCCESS') {
            echo "红包发送成功\n";
        } else {
            echo "红包发送失败\n";
        }
    }

    /*
    * 发现金红包
    * */
    public function sendRedPack($openid, $isOpen)
    {
        if (!$isOpen) {
            return $this->getResultTest();
        }
//        $money = 1;
//        $openid = 'oFyNdv73iU0nqkMvaaPiB-VBBLdc';
        $orderNum = 'sswh2020test' . date('His') . 'aa' . mt_rand(1, 10) . 'aa' . mt_rand(1, 10);
        $config = [
            'app_id' => 'wx24e6a8e7309fa489',
            'mch_id' => '1490899052',
            'key' => 'bqiVxY5yE3FKs8pjgGAJ2c2Gyz4SEfwI',
            'cert_path' => '/data/wwwroot/wx.sanshanwenhua.com/backend/33wh/cert/apiclient_cert.pem',
            'key_path' => '/data/wwwroot/wx.sanshanwenhua.com/backend/33wh/cert/apiclient_key.pem',
            'response_type' => 'array',
        ];

        $payment = Factory::payment($config);

        $redpack = $payment->redpack;

        $redpackData = [
            'mch_billno' => $orderNum,   //订单号
            'send_name' => '三山文化·测试红包',  //红包名称
            're_openid' => $openid,   //发送到指定的 openid
            'total_num' => 1,  //固定为1，可不传
            'total_amount' => 100,  //单位为分，不小于100
            'wishing' => '恭喜您，获得现金红包',     //祝福语
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '测试测试测试',    //测试活动
            'remark' => '',    //测试备注
        ];

        $result = $redpack->sendNormal($redpackData);

        return $result;
    }

//获取测试结果
    protected function getResultTest()
    {
        $successStr = '{
            "return_code": "SUCCESS",
            "return_msg": "发放成功",
            "result_code": "SUCCESS",
            "err_code": "SUCCESS",
            "err_code_des": "发放成功",
            "mch_billno": "jyyc000000184118aa9aa10",
            "mch_id": "1493373832",
            "wxappid": "wx6405a00853e0242a",
            "re_openid": "o9kSI0Sij5vxchIEcaeYBU4mzdX0",
            "total_amount": "100",
            "send_listid": "1000041701201912033000103008375"
        }';
        $failStr = '{
            "return_code": "SUCCESS",
            "return_msg": "IP地址非你在商户平台设置的可用IP地址",
            "result_code": "FAIL",
            "err_code": "NO_AUTH",
            "err_code_des": "IP地址非你在商户平台设置的可用IP地址",
            "mch_billno": "jyyc000000184118aa9aa10",
            "mch_id": "1493373832",
            "wxappid": "wx5b54be8b46e4a769",
            "re_openid": "oFyNdv73iU0nqkMvaaPiB-VBBLdc",
            "total_amount": "100"
        }';
        $resultMap = [
            1 => json_decode($successStr, true),
            2 => json_decode($failStr, true),
        ];
        $randomCode = mt_rand(1, 3);
        if (in_array($randomCode, [1, 2, 3])) {  //成功
            return $resultMap[1];
        }
        return $resultMap[2];  //失败
    }
}