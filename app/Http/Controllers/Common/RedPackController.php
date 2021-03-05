<?php


namespace App\Models;

use EasyWeChat\Factory;

class RedPackController
{
    /**
     * 均瑶宜昌中心·天城府红包配置
     * @param $money
     * @param $openid
     * @param $user_id
     * @param $isOpen
     * @param $itemName
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function sendRedPackForJyyc($itemName, $money, $openid, $user_id, $isOpen)
    {
        if (!$isOpen) {
            return $this->getResultTest($money);
        }
        $orderNum = $itemName . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
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

    /**
     * 汤达人红包配置
     * @param $itemName
     * @param $openid
     * @param $money
     * @param $user_id
     * @param $isOpen
     * @return array|\EasyWeChat\Kernel\Support\Collection|mixed|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function sendRedPackForTdr($itemName, $openid, $money, $user_id, $isOpen)
    {
        if (!$isOpen) {
            return $this->getResultTest($money);
        }

        /*IN小时光面馆*/
        $config = [
            'app_id' => 'wx948e9215c4c1d501',
            'mch_id' => '1416852202',
            'key' => 'RnWHJMHC1sw3EDJAzS2IHuAcDf3u5e6p',
            'cert_path' => '/data/wwwroot/wx.sanshanwenhua.com/z/tdr/cert/apiclient_cert.pem',
            'key_path' => '/data/wwwroot/wx.sanshanwenhua.com/z/tdr/cert/apiclient_key.pem',
            'response_type' => 'array',
        ];

        $payment = Factory::payment($config);

        $redpack = $payment->redpack;
        $orderNum = $itemName . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
        $redpackData = [
            'mch_billno' => $orderNum,
            'send_name' => 'IN小时光面馆',
            're_openid' => $openid,
            'total_num' => 1,  //固定为1，可不传
            'total_amount' => $money *100,  //单位为分，不小于100
            'wishing' => '元气来袭！汤达人给你发红包啦！',
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '汤达人元气红包',
            'remark' => '测试备注',
        ];

        return $redpack->sendNormal($redpackData);
    }

    //获取测试结果
    protected function getResultTest($money=1)
    {
        $money = $money * 100;
        $successStr = "{
            'return_code': 'SUCCESS',
            'return_msg': '发放成功',
            'result_code': 'SUCCESS',
            'err_code': 'SUCCESS',
            'err_code_des': '发放成功',
            'mch_billno': 'test0000000000000000000',
            'mch_id': '1000000000',
            'wxappid': 'wx000000000000000',
            're_openid': 'abc123456789123456',
            'total_amount': $money,
            'send_listid': '1000000000000000000000000000000'
        }";
        $failStr = "{
            'return_code': 'SUCCESS',
            'return_msg': 'IP地址非你在商户平台设置的可用IP地址',
            'result_code': 'FAIL',
            'err_code': 'NO_AUTH',
            'err_code_des': 'IP地址非你在商户平台设置的可用IP地址',
            'mch_billno': 'test0000000000000000000',
            'mch_id': '1000000000',
            'wxappid': 'wx000000000000000',
            're_openid': 'abc123456789123456',
            'total_amount': $money
        }";
        $resultMap = [
            1 => json_decode($successStr, true),
            2 => json_decode($failStr, true),
        ];
        $randomCode = mt_rand(1, 4);
        if (in_array($randomCode, [1, 2, 3])) {  //成功
            return $resultMap[1];
        }
        return $resultMap[2];  //失败
    }
}
