<?php

namespace App\Models\Ticket\L191127;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class Ticket extends Model
{
    //
    protected $guarded = [];
    protected $table = 'auto_check_v3_tickets';

    public $timestamps = false;

    const PRIZE_KEY = 'money';  //数据表的 中奖类型索引

    const TRUE_SEND_REDPACK = true; //是否真实发送红包
    /*审核状态码*/
    public static $checkStatus = [
        0 => '未审核',
        10 => '审核中',
        11 => '通过 【已审核】',
        12 => '不通过 【已审核】',
        13 => '审核失败',
        20 => '红包发送中',
        21 => '红包发送 【成功】',
        22 => '红包发送 【失败】',
        23 => '抽奖失败',
        24 => '当日红包已发完'
    ];
    /**
     * v:出现的权重
     * 0.88元×不局限、1.68元×70个   1.88元×50个、2.88元×30个
     */
    protected $prizeConf = [
        [self::PRIZE_KEY => 500, 'prize' => '5 元红包', 'v' => 60, 'count' => 0, 'limit' => 60],
        [self::PRIZE_KEY => 200, 'prize' => '2 元红包', 'v' => 200, 'count' => 0, 'limit' => 200],
        [self::PRIZE_KEY => 100, 'prize' => '1 元红包', 'v' => 1000, 'count' => 0, 'limit' => 2000],
        [self::PRIZE_KEY => 0, 'prize' => '未中奖', 'v' => 0, 'count' => 0, 'limit' => 100000],
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Ticket\L191127\User', 'user_id', 'id');
    }

    /*
     * 随机抽奖
     * 以上传时间计算
     * */
    public function randomPrizeCreatedAt()
    {
        $resultPrize = $this->prizeConf[count($this->prizeConf)-1]; //未中奖
        $dateStr = substr($this->created_at, 0, 10);
        $finalConf = [];
        $jingduSum = 0;
        foreach ($this->prizeConf as $k => $arr) {
            if ($k != (count($this->prizeConf) - 1)) {
                $this->prizeConf[$k]['count'] =
                    self::where('check_status', 21)
                        ->where(self::PRIZE_KEY, $arr[self::PRIZE_KEY])
                        ->whereDate('created_at', $dateStr)
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
        return ['resultPrize' => $resultPrize, 'finalConf' => $finalConf, 'conf' => $this->prizeConf, 'dateStr' => $dateStr];
    }

    /*
     * 发送红包
     * */
    public function redpack($openid, $money, $user_id)
    {
        if (!self::TRUE_SEND_REDPACK) {
            return $this->getResultTest($money);
        }

        if (!in_array(intval($money), [100, 200, 500])) {
            $money = 100;
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
            'wishing' => '元气来袭！汤达人给你发红包啦！',
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '汤达人元气红包',
            'remark' => '测试备注',
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
            'mch_billno' => 'qwt19012420190124174614286',
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
            'mch_billno' => 'qwt19012420190124174614286',
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

        return $resultMap[mt_rand(1, 4)];
    }
}
