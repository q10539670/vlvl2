<?php

namespace App\Models\Ticket\L191127;

use EasyWeChat\Factory;
use Illuminate\Database\Eloquent\Model;

class ActivityOne extends Model
{
    //
    protected $guarded = [];
    protected $table = 'auto_check_v3_act1';

    public $timestamps = false;

    const PRIZE_KEY = 'money';  //数据表的 中奖类型索引

    const TRUE_SEND_REDPACK = true; //是否真实发送红包

    protected $prizeConf = [
        0 => [self::PRIZE_KEY => 18800, 'prize' => '188 元红包', 'v' => 10, 'count' => 0, 'limit' => 10],
        1 => [self::PRIZE_KEY => 8800, 'prize' => '88 元红包', 'v' => 20, 'count' => 0, 'limit' => 20],
        2 => [self::PRIZE_KEY => 800, 'prize' => '8 元红包', 'v' => 100, 'count' => 0, 'limit' => 100],
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Ticket\L191127\User', 'user_id', 'id');
    }

    /*
     * 随机抽奖
     * 以上传时间计算
     * */
    public function randomPrize()
    {
        $finalConf = [];
        $jingduSum = 0;
        foreach ($this->prizeConf as $k => $arr) {
            if ($k != (count($this->prizeConf))) {
                $this->prizeConf[$k]['count'] =
                    self::where('status', 10)
                        ->where(self::PRIZE_KEY, $arr[self::PRIZE_KEY])
                        ->count();
                $this->prizeConf[$k]['v'] = $arr['v'] * 100;   //中奖权重 增加除不中奖以外的权重
            }
        }
        // 只取还有奖品和还有中奖率的选项
        foreach ($this->prizeConf as $k => $arr) {
            if (($arr['count'] < $arr['limit']) && ($arr['v'] > 0)) {
                $finalConf[] = $arr;
                $jingduSum += $arr['v'];
            }
        }
        //计算中奖率
        foreach ($finalConf as $k => $arr) {
            $finalConf[$k]['v100'] = round($arr['v'] * 100 / $jingduSum, 2) . '%';
        }
        //计算中奖
        foreach ($finalConf as $key => $prize) {
            $randNum = mt_rand(1, $jingduSum);
            if ($randNum <= $prize['v']) {
                $resultPrize = $prize;
                break;
            } else {
                $jingduSum -= $prize['v'];
            }
        }
        return ['resultPrize' => $resultPrize, 'finalConf' => $finalConf, 'conf' => $this->prizeConf];
    }

    /*
     * 发送红包
     * */
    public function redpack($openid, $money, $user_id)
    {
        if (!self::TRUE_SEND_REDPACK) {
            return $this->getResultTest($money);
        }

        /*IN小时光面馆*/
        $config = [
            'app_id' => 'wx948e9215c4c1d501',
            'mch_id' => '1416852202',
            'key' => 'RnWHJMHC1sw3EDJAzS2IHuAcDf3u5e6p',
            'cert_path' => '/data/wwwroot/wx.sanshanwenhua.com/backend/tdr/cert/apiclient_cert.pem',
            'key_path' => '/data/wwwroot/wx.sanshanwenhua.com/backend/tdr/cert/apiclient_key.pem',
            'response_type' => 'array',
        ];

        $payment = Factory::payment($config);

        $redpack = $payment->redpack;
//        \App::environment()
//        if (env('APP_ENV') == 'local') $openid = 'o0-aavyCu-3wJGHHoyfrlNqXlUxw';  // 本地环境
        $orderNum = 'ty191127' . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
        $redpackData = [
            'mch_billno' => $orderNum,
            'send_name' => 'IN小时光面馆',
            're_openid' => $openid,
            'total_num' => 1,  //固定为1，可不传
            'total_amount' => $money,  //单位为分，不小于100
            'scene_id' => 'PRODUCT_2',  //发放红包使用场景，红包金额大于200或者小于1元时必传   PRODUCT_2:抽奖
            'wishing' => '元气来袭！汤达人给你发红包啦！',
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '汤达人元气红包',
            'remark' => '测试备注',
//            'scene_id' => 'PRODUCT_8'    //发放红包使用场景，红包金额大于200或者小于1元时必传
        ];

        return $redpack->sendNormal($redpackData);
    }

    //获取模拟的红包发送结果
    protected function getResultTest($money)
    {
        $successConf = [
            'return_code' => 'SUCCESS',
            'return_msg' => '发放成功',
            'result_code' => 'SUCCESS',
            'err_code' => 'SUCCESS',
            'err_code_des' => '发放成功',
            'mch_billno' => 'ty00000020190124174614286',
            'mch_id' => '1385867702',
            'wxappid' => 'wx5b54be8b46e4a769',
            're_openid' => 'oFyNdv73iU0nqkMvaaPiB-VBBLdc',
            'total_amount' => (string)$money,
            'send_listid' => '1000041701201901243000168698346',
        ];
        $failConf = [
            'return_code' => 'SUCCESS',
            'return_msg' => 'IP地址非你在商户平台设置的可用IP地址',
            'result_code' => 'FAIL',
            'err_code' => 'NO_AUTH',
            'err_code_des' => 'IP地址非你在商户平台设置的可用IP地址',
            'mch_billno' => 'ty00000020190124174614286',
            'mch_id' => '1385867702',
            'wxappid' => 'wx5b54be8b46e4a769',
            're_openid' => 'oFyNdv73iU0nqkMvaaPiB-VBBLdc',
            'total_amount' => (string)$money,
        ];

        $resultMap = [
            1 => $successConf,
            2 => $failConf,
            3 => $failConf,
            4 => $failConf,
        ];

        return $resultMap[mt_rand(1, 1)];
    }
}
