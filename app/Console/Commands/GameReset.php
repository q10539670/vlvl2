<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X200515\User;
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
     */
    public function handle()
    {
        $url = 'http://tool.bitefu.net/jiari?d=' . date('Ymd');
        $client = new \GuzzleHttp\Client();
        $resClient = $client->request('GET', $url);
        $result = json_decode($resClient->getBody()->getContents(), true);
        $redis = app('redis');
        $redis->select(12);
        $redis->set('date',$result);
//        DB::table('x201105_user')->update([
//            'game_num' => 3
//        ]);   //世纪山水
        DB::table('x201208_user')->update([
            'num' => 3
        ]);
        DB::table('x201225_user')->update([
            'game_num' => 3,
            'share_num' =>1
        ]);
        return $this->info('重置成功');
    }

    //9DAE9Jx6s-qhZjBX_mO3uRH28eNlmbKafBGMuUZtTdk 模板ID
    public function seedTemplateMsg($openid, $first, $keyword1)
    {
        $nowStr = now()->toDateTimeString();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.Helper::getJchnAccessToken();
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
