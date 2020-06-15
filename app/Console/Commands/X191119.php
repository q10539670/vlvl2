<?php

namespace App\Console\Commands;

use App\Exports\Qwt\X191119\X191119Export;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Qwt\X191119\User;

class X191119 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x191119:cmd {handle?}';

    /**
     * The console command description.
     *
     * @var stringapp
     */
    protected $description = '电信2019年11月';

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
            case 'send':    //应用初始化
                $this->send();
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
     * 补发红包
     */
    public function send()
    {
        $user = new User();
//        $users = $userZzz->where('id', '36')->get();

        $users = $user->where('prize_at', '<', '2019-10-25 14:15:19')->where('money', '>', 0)->whereNotIn('id',[36,40])->get();
        foreach ($users as $user) {
            $money = $user->money;
            $resultRedpack = $user->sendRedpack($money, $user->openid, $user->id, true);
            $user->where('id',$user->id)->update([
                'location'=>1,
                'redpack_return_msg'=>$resultRedpack['return_msg'],
                'redpack_describle'=>json_encode($resultRedpack, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    /*
     * 刷新排行榜
     * */
    public function refreshRanklistHandler()
    {
        $redis = app('redis');
        $redis->select(12);
        $baseKey = 'qwt:x191119:rankList';
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
        $res = Excel::store(new X191119Export(), '/excel/' . $this->description . '抽奖名单_共' . $pages . '页.xlsx', $storeDriver);
        if ($res) {
            echo "excel表格导出成功 \n";
            return $res;
        }
    }
}
