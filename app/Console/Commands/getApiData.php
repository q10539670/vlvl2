<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;


class getApiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getApiData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取第三方Api接口信息';

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
        $this->getWorkingDay(); //判断工作日
        $this->getWeather();    //获取天气信息
        return $this->info('获取成功');
    }

    public function getWeather()
    {
        $host = 'https://iweather.market.alicloudapi.com';
        $path = '/address';
        $querys = 'prov=湖北&city=武汉&needday=1';
        $appcode = 'f00ed7c6c9af40968dee7fabeae4b8fe';
        $url = $host . $path . "?" . $querys;
        $client = new \GuzzleHttp\Client();
        $resClient = $client->request('GET', $url, ['headers' => ['Authorization' => 'APPCODE ' . $appcode]]);
        $result = json_decode($resClient->getBody()->getContents(), true);
        if ($result['ret'] !== 200) {
            return false;
        }
        $cityInfo = $result['data']['cityinfo'];
        $detail = $result['data']['now']['detail'];
        $now = $result['data']['now']['city'];
        $city = $cityInfo['provinces'] . $cityInfo['city'];
        $date = $detail['date'] . '(' . $detail['nongli'] . ') , 星期' . $detail['week'];
        $updateTime = $detail['time'];
        $temperature = $detail['temperature'] . '℃';
        $nDTemperature = $now['night_air_temperature'] . '℃ ~' . $now['day_air_temperature'] . '℃';
        $weather = $now['weather'];
        $quality = $detail['quality'];
        $weatherStr = '实时天气预报:\r\n城市: ' . $city . '\r\n日期: ' . $date . '\r\n更新时间: ' . $updateTime . '\r\n当前温度: '
            . $temperature . '\r\n白天夜间温度: ' . $nDTemperature . '\r\n天气: ' . $weather . '\r\n空气质量: ' . $quality .
            '\r\n当前时间: ' . '\r\n点击查看15天天气';
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
        //过年假期 开始:20210209-20210220
        if (date('Ymd') >= '20210205' && date('Ymd') <= '20210320') {
            $result = 2;//节假日
        } else {
            $url = 'http://tool.bitefu.net/jiari?d=' . date('Ymd');
            $client = new \GuzzleHttp\Client();
            $resClient = $client->request('GET', $url);
            $result = json_decode($resClient->getBody()->getContents(), true);
        }
        $redis = app('redis');
        $redis->select(12);
        $redis->set('date', $result);
    }
}
