<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jyyc\X200107\RedPack;
use mysql_xdevapi\Result;

class Jyyc extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan ticket_l191127:cmd sendredpack
     * @var string
     */
    protected $signature = 'jyyc:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发红包 均瑶宜昌中心';

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
        $money = 50;

//                $openId ='o9kSI0Sij5vxchIEcaeYBU4mzdX0';    //测试

//        $openId ='o9kSI0XtgoGoCfUwSmMvIglMRZ54';    //Ewan一万        一等奖   成功
//        $openId ='o9kSI0S7-C8T_7V0TwoVEByhGE20';    //四方八达         二等奖   成功
//        $openId ='o9kSI0TCWgZpxl9YT2McJeVhQSzk';    //千与千寻         三等奖   成功
//        $openId = 'o9kSI0bvgl9a7GyfpKPn2mY-iM_g';   //咖啡不加糖       四等奖   成功
//        $openId ='o9kSI0St2b_WVt_zqGPSbHVEmAEk';    //一葉知秋         五等奖   成功
//        $openId ='o9kSI0X5BgzHxujUKRtyOnGHOdqo';    //苗子       50元     已发
//        $openId ='o9kSI0WMqGOq90jAE5CZXreQPHac';    //鹿     50元        已发
//        $openId ='o9kSI0RIpLFJzqmEVsTJPDJmUqb0';    //小车队钟开军  50元    已发
//        $openId ='o9kSI0b2zW9wx711VrAWZVkD5isk';    //小海豚  50元    已发
//        $openId ='o9kSI0TPY61ED7WrAGfjpGcEbLGg';    //数日子的姐姐  50元    已发
        $openId ='o9kSI0X1jDmSV2TwZ5UPQokALyGQ';    //数日子的姐姐  50元    已发


        $redPack = new RedPack();
        $resultRedPack = $redPack->sendRedpack($money,$openId,self::OPEN_RED_PACK);
        if (self::DEBUG) print_r($resultRedPack);
        if ($resultRedPack['return_code'] == 'SUCCESS' && $resultRedPack['err_code'] == 'SUCCESS') {
            RedPack::create([
                'openid' => $openId,
                'money' => $money,
                'redpack_return_msg' => $resultRedPack['return_msg'],
                'redpack_describle' =>json_encode($resultRedPack, JSON_UNESCAPED_UNICODE),
            ]);
            echo "红包发送成功\n";
        } else {
            RedPack::create([
                'openid' => $openId,
                'money' => 0,
                'redpack_return_msg' => $resultRedPack['return_msg'],
                'redpack_describle' =>json_encode($resultRedPack, JSON_UNESCAPED_UNICODE),
            ]);
            echo "红包发送失败\n";
        }
    }
}
