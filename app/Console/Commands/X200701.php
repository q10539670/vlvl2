<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Jchn\X200701\Images;
use App\Models\Jchn\X200701\Log;
use App\Models\Jchn\X200701\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class X200701 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'x200701:sendMsg';

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

        $images = Images::where('msg_status', 0)->where('status', '!=', 0)->get();
        $logs = Log::where('msg_status', 0)->where('status', 1)->get();
        foreach ($logs as $log) {
            $resultMsg = '恭喜中奖';
            $keyword1 = $log->result_name;
            $user = User::find($log->user_id);
            $result = $this->seedTemplateMsg($user->openid, $resultMsg, $keyword1);
            if ($result['errcode'] == 0) {
                $log->msg_status = 1;
            } else {
                $log->msg_status = 2;
            }
            $log->save();
        }
        foreach ($images as $image) {
            if ($image->status == 1) {
                $resultMsg = '审核通过';
                $keyword1 = $image->add_num.'次抽奖机会';
            } else {
                $resultMsg = '审核未通过';
                $keyword1 = '无';
            }
            $user = User::find($image->user_id);
            $result = $this->seedTemplateMsg($user->openid, $resultMsg, $keyword1);
            if ($result['errcode'] == 0) {
                $image->msg_status = 1;
            } else {
                $image->msg_status = 2;
            }
            $image->save();
        }
    }

    public function seedTemplateMsg($openid, $first, $keyword1)
    {
        $nowStr = now()->toDateTimeString();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.Helper::getJchnAccessToken();
        $client = new \GuzzleHttp\Client();
        $data = [
            'touser' => $openid,
            'template_id' => 'Etn2eu1jpDwpuQAJMPzmLM19p7FZOFn_Lw_UzSVEHpQ',
            'data' => [
                'first' => ['value' => $first],
                'keyword1' => ['value' => $keyword1],
                'keyword2' => ['value' => $nowStr],
                'remark' => ['value' => '']
            ],
            "url" => "https://wx.sanshanwenhua.com/items/hn20200703/index.html",
//            "page" => "pages/redlist/redlist",
            "lang" => "zh_CN",
//            'miniprogram_state' => 'trial', //跳转小程序类型：developer 为开发版；trial 为体验版；formal 为正式版；默认为正式版
        ];
        return json_decode($client->request('POST', $url, ['json' => $data])->getBody()->getContents(), true);

    }
}
