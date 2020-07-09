<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Sswh\X200701\Images;
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

    const END_TIME = '2020-07-24 23:59:59';

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
    public function send($openid, $userId, $templateId = '3PU474JAjAn37yHCjH0mPNRaRTYyC2Knqx5sns3zxFw')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . Helper::getJchnAccessToken();
        $nowStr = now()->toDateTimeString();
        $resultMsg = '';
        $contentMsg = '';
        if (!in_array($ticket->check_status, [12, 21, 22, 24])) {
            throw new \Exception("当前小票不符合模板消息发送场景", 201);
        }
        if ($ticket->check_status == 12) { //审核不通过
            $resultMsg = '不通过';
            $contentMsg = '统一小票审核： '.explode('&&', $ticket->result_check_desc)[0];
//            $contentMsg = '【小票编号：' . $ticket->id . '】 ' . explode('&&', $ticket->result_check_desc)[0];
        } elseif ($ticket->check_status == 21) { //红包已发放
            $resultMsg = '通过';
            $contentMsg = '统一小票审核： 红包已发放';
//            $contentMsg = '【小票编号：' . $ticket->id . '】 红包已发放';
        } elseif ($ticket->check_status == 22) { //红包发放失败
            $resultMsg = '通过';
//            $contentMsg = '【小票id：' . $ticket->id . '】 ' . $ticket->result_redpack_desc;
            $contentMsg = '红包已发放失败，已被微信拦截';
//            $contentMsg = '【小票编号：' . $ticket->id . '】 红包已发放失败';
        } elseif ($ticket->check_status == 24) { //当日红包已发完
            $resultMsg = '通过';
            $contentMsg = '当日红包已发完';
//            $contentMsg = '【小票编号：' . $ticket->id . '】 当日红包已发完';
        }
        $data = [
            'touser' => $openid,
            'template_id' => $templateId,
            'data' => [
                'phrase1' => ['value' => $resultMsg],
                'thing3' => ['value' => $contentMsg],
                'date5' => ['value' => $nowStr],
            ],
            "page" => "pages/index/index",
//            "page" => "pages/redlist/redlist",
            "lang" => "zh_CN",
//            'miniprogram_state' => 'trial', //跳转小程序类型：developer 为开发版；trial 为体验版；formal 为正式版；默认为正式版
        ];
        $client = new \GuzzleHttp\Client();
        $result = json_decode($client->request('POST', $url, ['json' => $data])->getBody()->getContents(), true);
        $msg = [
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'type' => 'xcx',
            'openid' => $openid,
            'msg_content' => $contentMsg,
            'msg_result' => $resultMsg,
            'result_code' => $result['errcode'],
            'result_msg' => $result['errmsg'],
            'msg_send_at' => $nowStr,
            'created_at' => now()->toDateTimeString(),
        ];
        if ($result['errcode'] == 0) {
            $msg['status'] = 1;
        } else {
            $msg['status'] = 2;
        }
        Msg::create($msg);
        $ticket->msg_status = $msg['status'];
        $ticket->save();
    }
}
