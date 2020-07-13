<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Sswh\X191220\User;

class X191220 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x191220:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '长沙洋湖天街';

    protected $addrCode = '4301'; //抽奖地区   长沙市

    protected $stopSecond = 7; //睡眠时间

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
            case 'location':    //解析定位 (每分钟运行一次)
                $this->locationHandler();
                break;
            default:
                echo '默认';
        }
    }

    /*
     * 解析定位处理器
     * */
    public function locationHandler()
    {
        $startTime = time();
        $client = new \GuzzleHttp\Client;
//        $totalSecond = 60;//脚本执行时间
//        $num = $totalSecond/$this->limitSecond-1;
        $num = 100;
        for ($i = 1; $i < $num; $i++) {
            if (time() - $startTime > 50) {
                exit;
            }
            $this->parseLocation($client);
            sleep($this->stopSecond);
        }
    }

    /*
     * 解析定位
     * {"latitude":"29.855632781982422","longitude":"121.39921569824219"}
     * 29.855632781982422,121.39921569824219
     * */
    protected function parseLocation($client)
    {
        $users = User::where('address_code', '-2')->orderBy('created_at', 'asc')->limit(8)->get();
//        print_r($users);exit;
        if ($users) {
            if (count($users) == 8 && $this->stopSecond > 3) {
                $this->stopSecond -= 3;
            } else {
                $this->stopSecond += 3;
            }
            foreacH ($users as $user) {
                $url = "https://apis.map.qq.com/ws/geocoder/v1/?key=P3FBZ-OVJK5-OYPII-QKKVX-MNWZE-GIBNQ&location={$user->location}";
                $res = $client->request('GET', $url);
                $result = json_decode($res->getBody()->getContents(), true);
//                print_r($result);
//                exit;
                if (isset($result['status']) && $result['status'] == 0) {
                    try {
                        $user->address_str = $result['result']['address'];
                        $user->address_code = $result['result']['ad_info']['adcode'];
                    } catch (\Exception $e) {
                        $user->address_code = -1;
                        $user->ip_status = -1;
                    }
                    if (substr($result['result']['ad_info']['adcode'],0,4) == $this->addrCode) {
                        $user->ip_status = 1;
                    } else {
                        $user->ip_status = -1;
                    }
                } else {
                    $user->address_code = -1;
                    $user->ip_status = -1;
                }
                $user->save();
            }
        }
    }

}
