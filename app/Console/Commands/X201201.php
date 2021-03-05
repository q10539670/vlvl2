<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X201201\Info;
use App\Models\Sswh\X201201\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class X201201 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x201201:sendMsg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送模板消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * template-id ： 3PU474JAjAn37yHCjH0mPNRaRTYyC2Knqx5sns3zxFw
     * openid= oYYl55JPc5rJsUFIVomGcIZSs8E4 //test
     *
     * */
    public function handle()
    {
        $users = User::whereHas('info', function ($query) {
            $query->where('status', 0);
        })->get();//排除未验证以及离职的同事
        $redis = app('redis');
        $redis->select(12);
        $date = $redis->get('date');
        if ($date == 0) {
            foreach ($users as $user) {
                $date = date('Y年m月d日');
                $firstMsg = $date. '加班晚餐预定开始了';
                $keyword1 = '17:00至18:00';
                $keyword2 = '点击查看详情开始预定晚餐';
                $result = $this->seedTemplateMsg($user->openid, $firstMsg, $keyword1,$keyword2);
                $user->errmsg = $result['errmsg'];
                $user->save();
            }
            return $this->info('模板消息发送完成');
        } else {
            return $this->info('今天不是工作日');
        }

    }

    public function seedTemplateMsg($openid, $first, $keyword1,$keyword2)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.Helper::getSswhAccessToken();
        $client = new \GuzzleHttp\Client();
        $data = [
            'touser' => $openid,
            'template_id' => '9DAE9Jx6s-qhZjBX_mO3uRH28eNlmbKafBGMuUZtTdk',
            'data' => [
                'first' => ['value' => $first],
                'keyword1' => ['value' => $keyword1],
                'keyword2' => ['value' => $keyword2],
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
