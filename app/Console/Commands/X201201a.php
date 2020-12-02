<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X201201\Info;
use App\Models\Sswh\X201201\User;
use App\Models\Sswh\X201201\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class X201201a extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x201201a:sendMsg';

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
        $openid = 'oFOht0pPJmQWdIvMeYxKO6yKAbB8';
        $openidRS = 'oFOht0pWRi3I1mbLe3mf4F_7qDA0'; //人事
        if (Helper::isWorkingDay() == 0) {
            $infos = Order::where('status', 0)->whereBetween('created_at', Helper::formatDay())->get();
            $staffs = '';
            foreach ($infos as $info) {
                $staffs .= $info->user->info->name.'、';
            }
            $date = date('Y年m月d日');
            $firstMsg = $date.'加班晚餐预定详情';
            $keyword1 = '晚餐';
            $keyword2 = '共'.Order::where('status', 0)->whereBetween('created_at', Helper::formatDay())->count().'人';
            $this->seedTemplateMsg($openidRS, $firstMsg, $keyword1, $keyword2, $staffs, '无');
            $this->seedTemplateMsg($openid, $firstMsg, $keyword1, $keyword2, $staffs, '无');
            return $this->info('模板消息发送完成');
        } else {
            return $this->info('今天不是工作日');
        }
    }

    public function seedTemplateMsg($openid, $first, $keyword1, $keyword2, $keyword3, $keyword4)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.Helper::getSswhAccessToken();
        $client = new \GuzzleHttp\Client();
        $data = [
            'touser' => $openid,
            'template_id' => 'LuIKcTSADxB83pQ9Z3jNdq-abDa65jYuNV2iE4G0f20',
            'data' => [
                'first' => ['value' => $first],
                'keyword1' => ['value' => $keyword1],
                'keyword2' => ['value' => $keyword2],
                'keyword3' => ['value' => $keyword3],
                'keyword4' => ['value' => $keyword4],
                'remark' => ['value' => '更多请点击查看详情']
            ],
            "url" => "https://wx.sanshanwenhua.com/items/sswh201201/index.html",
//            "page" => "pages/redlist/redlist",
            "lang" => "zh_CN",
//            'miniprogram_state' => 'trial', //跳转小程序类型：developer 为开发版；trial 为体验版；formal 为正式版；默认为正式版
        ];
        return json_decode($client->request('POST', $url, ['json' => $data])->getBody()->getContents(), true);

    }
}
