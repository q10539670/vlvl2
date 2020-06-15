<?php


namespace App\Models\Jyyc\X20200107;


use EasyWeChat\Factory;
use Illuminate\Database\Eloquent\Model;

class RedPack extends Model
{

    protected $table = 'x200107dym_redpack';

    protected $guarded = [];

    /*
    * 发现金红包
    * */
    public function sendRedpack($money, $openid, $user_id, $isOpen)
    {
        if (!$isOpen) {
            return $this->getResultTest();
        }
//        $money = 1;
//        $openid = 'oFyNdv73iU0nqkMvaaPiB-VBBLdc';
        $orderNum = 'jyyc20200107dym' . date('His') . 'aa' . mt_rand(1, 10) . 'aa' . mt_rand(1, 10);
        $config = [
            'app_id' => 'wx6405a00853e0242a',
            'mch_id' => '1493373832',
            'key' => '885341b4d8ee7538840a14410c87a19e',
            'cert_path' => '/data/wwwroot/wx.sanshanwenhua.com/z/jyyichang/cert/apiclient_cert.pem',
            'key_path' => '/data/wwwroot/wx.sanshanwenhua.com/z/jyyichang/cert/apiclient_key.pem',
            'response_type' => 'array',
        ];

        $payment = Factory::payment($config);

        $redpack = $payment->redpack;

        $redpackData = [
            'mch_billno' => $orderNum,   //订单号
            'send_name' => '宜昌中心·天宸府',  //红包名称
            're_openid' => $openid,   //发送到指定的 openid
            'total_num' => 1,  //固定为1，可不传
            'total_amount' => $money * 100,  //单位为分，不小于100
            'wishing' => '恭喜您，获得现金红包',     //祝福语
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '扫码赢红包,惊喜赢大奖',    //测试活动
            'remark' => '',    //测试备注
        ];

        $result = $redpack->sendNormal($redpackData);

        return $result;
    }

//获取测试结果
    protected function getResultTest()
    {
        $successStr = '{
            "return_code": "SUCCESS",
            "return_msg": "发放成功",
            "result_code": "SUCCESS",
            "err_code": "SUCCESS",
            "err_code_des": "发放成功",
            "mch_billno": "jyyc000000184118aa9aa10",
            "mch_id": "1493373832",
            "wxappid": "wx6405a00853e0242a",
            "re_openid": "o9kSI0Sij5vxchIEcaeYBU4mzdX0",
            "total_amount": "100",
            "send_listid": "1000041701201912033000103008375"
        }';
        $failStr = '{
            "return_code": "SUCCESS",
            "return_msg": "IP地址非你在商户平台设置的可用IP地址",
            "result_code": "FAIL",
            "err_code": "NO_AUTH",
            "err_code_des": "IP地址非你在商户平台设置的可用IP地址",
            "mch_billno": "jyyc000000184118aa9aa10",
            "mch_id": "1493373832",
            "wxappid": "wx5b54be8b46e4a769",
            "re_openid": "oFyNdv73iU0nqkMvaaPiB-VBBLdc",
            "total_amount": "100"
        }';
        $resultMap = [
            1 => json_decode($successStr, true),
            2 => json_decode($failStr, true),
        ];
        $randomCode = mt_rand(1, 3);
        if (in_array($randomCode, [1, 2, 3])) {  //成功
            return $resultMap[1];
        }
        return $resultMap[2];  //失败
    }
}
