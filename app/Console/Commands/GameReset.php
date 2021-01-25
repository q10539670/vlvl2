<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X200515\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class GameReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天重置游戏次数';

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
     * @throws GuzzleException
     */
    public function handle()
    {
        $this->getWorkingDay();
//        $this->getWeather();

        DB::table('x201208_user')->update([
            'num' => 3
        ]);
        DB::table('x201225_user')->update([
            'game_num' => 3,
            'share_num' => 1
        ]);
        DB::table('x201229_user')->update([
            'game_num' => 1,
            'share_num' => 2
        ]);
        DB::table('x210111_user')->update([
            'game_num' => 3,
            'share_num' => 1
        ]);
        return $this->info('重置成功');
    }

    public function getWeather()
    {
        $host = 'https://iweather.market.alicloudapi.com';
        $path = '/address';
        $querys = 'prov=湖北&city=武汉&needday=1';
        $appcode = 'f00ed7c6c9af40968dee7fabeae4b8fe';
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $url = $host . $path . "?" . $querys;
        $client = new \GuzzleHttp\Client();
        $resClient = $client->request('GET', $url);
        $result = $resClient->getBody()->getContents();
        if ($result->ret !== 200) {
            return false;
        }
        $cityInfo = $result->data->cityInfo;
        $detail = $result->data->detail;
        $now = $result->data->now->city;
        $city = $cityInfo->provinces . $cityInfo->city;
        $date = $detail->date . '(' . $detail->nongli . ') ,' . $detail->week;
        $updateTime = $detail->time;
        $temperature = $detail->temperature . '℃';
        $nDTemperature = $now->night_air_temperature . '℃ ~' . $now->day_air_temperature . '℃';
        $weather = $detail->weather;
        $quality = $detail->quality;
        $nowTime = now()->toDateTimeString();
        $weatherStr = '实时天气预报:\r\n城市: ' . $city . '\r\n日期: ' . $date . '\r\n更新时间: ' . $updateTime . '\r\n当前温度: '
            . $temperature . '\r\n白天夜间温度: ' . $nDTemperature . '\r\n天气: ' . $weather . '\r\n空气质量: ' . $quality .
            '\r\n当前时间: ' . $nowTime . '\r\n点击查看15天天气';
        $redis = app('redis');
        $redis->select(12);
        $redis->set('weather', $weatherStr);
    }

    /**
     * 判断工作日并缓存
     * @throws GuzzleException
     */
    public function getWorkingDay()
    {
        $url = 'http://tool.bitefu.net/jiari?d=' . date('Ymd');
        $client = new \GuzzleHttp\Client();
        $resClient = $client->request('GET', $url);
        $result = json_decode($resClient->getBody()->getContents(), true);
        $redis = app('redis');
        $redis->select(12);
        $redis->set('date', $result);
    }

    //9DAE9Jx6s-qhZjBX_mO3uRH28eNlmbKafBGMuUZtTdk 模板ID
    public function seedTemplateMsg($openid, $first, $keyword1)
    {
        $nowStr = now()->toDateTimeString();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . Helper::getJchnAccessToken();
        $client = new \GuzzleHttp\Client();
        $data = [
            'touser' => $openid,
            'template_id' => '9DAE9Jx6s-qhZjBX_mO3uRH28eNlmbKafBGMuUZtTdk',
            'data' => [
                'first' => ['value' => $first],
                'keyword1' => ['value' => $keyword1],
                'keyword2' => ['value' => $nowStr],
                'remark' => ['value' => '']
            ],
            "url" => "https://wx.sanshanwenhua.com/items/sswh201201/index.html",
//            "page" => "pages/redlist/redlist",
            "lang" => "zh_CN",
//            'miniprogram_state' => 'trial', //跳转小程序类型：developer 为开发版；trial 为体验版；formal 为正式版；默认为正式版
        ];
        return json_decode($client->request('POST', $url, ['json' => $data])->getBody()->getContents(), true);

    }
}
