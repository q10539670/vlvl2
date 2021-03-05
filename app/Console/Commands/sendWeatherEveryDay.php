<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X201201\Info;
use App\Models\Sswh\X201201\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class sendWeatherEveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendWeatherEveryDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送天气消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::whereHas('info', function ($query) {
            $query->where('status', 0);
        })->get();//排除未验证以及离职的同事
        $redis = app('redis');
        $redis->select(12);
        $firstMsg = '每日天气预报提醒';
        $keyword1 = '三山每日天气播报';
        $keyword2 = date('Y年m月d日 H:i');
        $remark = $redis->get('weather');
        $nowTime = date('H:i');
        $remark = str_replace('\r\n', "\r\n\r\n", $remark);
        $remark = str_replace('当前时间:', "当前时间: ". $nowTime, $remark);
//        $openid = 'oFOht0pPJmQWdIvMeYxKO6yKAbB8';  //测试
//        $result = $this->seedTemplateMsg($openid, $firstMsg, $keyword1,$keyword2, $remark);
        foreach ($users as $user) {
            $this->seedTemplateMsg($user->openid, $firstMsg, $keyword1, $keyword2, $remark);
        }
        return $this->info('模板消息发送完成');

    }

    public function seedTemplateMsg($openid, $first, $keyword1, $keyword2, $remark)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . Helper::getSswhAccessToken();
        $client = new \GuzzleHttp\Client();
        $data = [
            'touser' => $openid,
            'template_id' => 'I_q1eTKY08UhJnFY-DZoo6a8nEjAdSVqi1g2ieTGnGw',
            'data' => [
                'first' => ['value' => $first],
                'keyword1' => ['value' => $keyword1],
                'keyword2' => ['value' => $keyword2],
                'remark' => ['value' => $remark]
            ],
            "url" => "http://e.weather.com.cn/mweather/101200101.shtml",
//            "page" => "pages/redlist/redlist",
            "lang" => "zh_CN",
//            'miniprogram_state' => 'trial', //跳转小程序类型：developer 为开发版；trial 为体验版；formal 为正式版；默认为正式版
        ];
        return json_decode($client->request('POST', $url, ['json' => $data])->getBody()->getContents(), true);

    }
}
