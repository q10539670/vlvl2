<?php

namespace App\Console\Commands;

use App\Exports\Qwt\Dx190925Export;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Qwt\Dx190925\User;

class Dx190925 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dx190925:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var stringapp
     */
    protected $description = '电信2019年9月';

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
            case 'refresh_ranklist':  //刷新排行榜(每分钟运行一次)    php artisan l190826a:cmd refresh_ranklist
                $this->refreshRanklistHandler();
                break;
            case 'app_init':    //应用初始化
                $this->appInitHandler();
                break;
            case 'location':    //解析定位 (每分钟运行一次)
                $this->locationHandler();
                break;
            case 'export':    //导出
                $this->exportHander();
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
                print_r($result);
//                exit;
                if (isset($result['status']) && $result['status'] == 0) {
                    try {
                        $user->address_str = $result['result']['address'];
                        $user->address_code = $result['result']['ad_info']['adcode'];
                    } catch (\Exception $e) {
                        $user->address_code = -1;
                    }
                } else {
                    $user->address_code = -1;
                }
                $user->save();
            }
        }
    }

    /*
     * 应用初始化
     * */
    public function appInitHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $dateArr = [
            'test',
            '20190927',
            '20190928',
            '20190929',
        ];
        $redis->del('qwt:dx190925:prizeCount');
        foreach ($dateArr as $k => $v) {
            $redis->hmset('qwt:dx190925:prizeCount:' . $v, ['0' => 0, '10' => 0, '30' => 0, '50' => 0, '100' => 0]);
        }
        $redis->set('qwt:dx190925:rankList', "[]");
//        \DB::statement('truncate table l190826a_user');
        echo "应用初始化成功 \n";
        exit;
    }

    /*
     * 刷新排行榜
     * */
    public function refreshRanklistHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $baseKey = 'qwt:dx190925:rankList';
        /*任务配置*/
        $limitSecond = 5;
//        $num = 60/$limitSecond-1;
        $num = 100;
        $nowTime = time();
        $stopTime = $nowTime + 60 - $limitSecond;
        for ($i = 0; $i < $num; $i++) {
            if (time() > $stopTime) {
                break;
            }
            /*任务处理  --开始*/
            $rankListStr = $this->getRankList();
            $redis->set($baseKey, $rankListStr);
            /*任务处理  --结束*/
            echo "处理完成 $i \n";
            sleep($limitSecond);
        }
    }


    protected function exportHander()
    {
        $count = User::count();
        $pages = ceil($count / 2000);
        $storeDriver = env('APP_ENV') == 'local' ? 'public' : 'prod';
        echo "excel表格导出成功\n";
        return Excel::store(new Dx190925Export(), '/excel/电信9月份抽奖名单_共' . $pages . '页.xlsx', $storeDriver);
    }
}
