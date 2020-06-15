<?php

namespace App\Models\Qwt\X191022;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use EasyWeChat\Factory;

class UserZzz extends Model
{
    //

    protected $table = 'x191022_user_zzz';

    protected $guarded = [];

    /*
     * 生成唯一随机码
     * */
    public static function getUniqueCode($len)
    {
        $randomCode = self::getRandomCode($len);
        if (!self::where('share_code', $randomCode)->first()) {
            return $randomCode;
        }
        return self::getUniqueCode($len);
    }

    /*
     * 生成随机码
     * */
    public static function getRandomCode($len)
    {
        $str = '123456789';
        $res = '';
        $strLen = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $res .= substr($str, mt_rand(0, $strLen - 1), 1);
        }
        return $res;
    }

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
        $orderNum = 'qwt191022' . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
        $config = [
            'app_id' => env('QWT_WECHAT_APP_ID'),
            'mch_id' => env('QWT_WECHAT_MCH_ID'),
            'key' => env('QWT_WECHAT_MCH_KEY'),
            'cert_path' => env('QWT_WECHAT_MCH_CERT_PATH'),
            'key_path' => env('QWT_WECHAT_MCH_KEY_PATH'),
            'response_type' => 'array',
        ];

        $payment = Factory::payment($config);

        $redpack = $payment->redpack;

        $redpackData = [
            'mch_billno' => $orderNum,   //订单号
            'send_name' => '全网通手机汇',  //红包名称
            're_openid' => $openid,   //发送到指定的 openid
            'total_num' => 1,  //固定为1，可不传
            'total_amount' => $money * 100,  //单位为分，不小于100
            'wishing' => '恭喜您，获得iPhone 11游戏红包',     //祝福语
//            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name' => '迈进5G时代 赢取红包',    //测试活动
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
            "mch_billno": "qwt19012420190124174614286",
            "mch_id": "1385867702",
            "wxappid": "wx5b54be8b46e4a769",
            "re_openid": "oFyNdv73iU0nqkMvaaPiB-VBBLdc",
            "total_amount": "100",
            "send_listid": "1000041701201901243000168698346"
        }';
        $failStr = '{
            "return_code": "SUCCESS",
            "return_msg": "IP地址非你在商户平台设置的可用IP地址",
            "result_code": "FAIL",
            "err_code": "NO_AUTH",
            "err_code_des": "IP地址非你在商户平台设置的可用IP地址",
            "mch_billno": "qwt19012420190124174040247",
            "mch_id": "1385867702",
            "wxappid": "wx5b54be8b46e4a769",
            "re_openid": "oFyNdv73iU0nqkMvaaPiB-VBBLdc",
            "total_amount": "100"
        }';
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

    /*
     * 处理抽奖 -- 开始
     * #######################################################################################################################################################
     * */
    private $zhongJiangLv = [
        'hubei' =>8,
        'other' => 2,
    ];  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
    private $prizeKey = 'prize_id';  // 数据库里面的 中奖类型索引的字段 名称
    /*
     * 中奖配置数组
     *  v:出现的权重
     * prize_id 为数据表里面的中奖类型 ID值
     * prize_level_name 中奖类型 如：一等奖，二等奖。。。
     * prize_name 奖品或奖品物品名称
     *  v     奖品的权重
     * count  当前中奖数量
     * limit  预先准备的该奖品个数限制
     * */
    protected $prizeConf = [
        /*中奖数组*/
        'prize' => [
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '三元红包', 'money' => 3, 'v' => 10, 'count' => 0, 'limit' => 0],
            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '二元红包', 'money' => 2, 'v' => 20, 'count' => 0, 'limit' => 0],
            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '一元红包', 'money' => 1, 'v' => 60, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPirze' => ['prize_id' => 20, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖   --奖品已发完*/
        'afterPirze' => ['prize_id' => 21, 'prize_level_name' => '未中奖', 'prize_name' => '红包已发完', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖数组  --发红包失败*/
        'failSendpack' => ['prize_id' => 22, 'prize_level_name' => '未中奖', 'prize_name' => '红包发送失败', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        'hubei' => [
            /*测试*/
            'test' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 20],
                '2' => ['money' => 2, 'limit' => 10],
                '3' => ['money' => 3, 'limit' => 10],
            ],
            /*湖北 第一天*/
            '20191025' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 600],
                '2' => ['money' => 2, 'limit' => 100],
                '3' => ['money' => 3, 'limit' => 30],
            ],
            /*湖北 第二天*/
            '20191026' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 400],
                '2' => ['money' => 2, 'limit' => 50],
                '3' => ['money' => 3, 'limit' => 10],
            ],
            /*湖北 第三天*/
            '20191027' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 250],
                '2' => ['money' => 2, 'limit' => 25],
                '3' => ['money' => 3, 'limit' => 10],
            ],
        ],
        'other' => [
            /*测试*/
            'test' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 2],
                '2' => ['money' => 2, 'limit' => 1],
                '3' => ['money' => 3, 'limit' => 1],
            ],
            /*外地 第一天*/
            '20191025' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 600],
                '2' => ['money' => 2, 'limit' => 100],
                '3' => ['money' => 3, 'limit' => 30],
            ],
            /*外地 第二天*/
            '20191026' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 400],
                '2' => ['money' => 2, 'limit' => 50],
                '3' => ['money' => 3, 'limit' => 10],
            ],
            /*过期 不中奖*/
            '20191027' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 250],
                '2' => ['money' => 2, 'limit' => 25],
                '3' => ['money' => 3, 'limit' => 10],
            ],
        ],
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $dateStr, $address_code)
    {
        if (!in_array($dateStr, ['20191025', '20191026', '20191027'])) {
            $dateStr = 'test';
        }
        $hubeiOrQuanguo = null;
        if (preg_match('/^42\d*/', $address_code)) {  //湖北地区
            $hubeiOrQuanguo = 'hubei';
        } else {   //非湖北地区
            $hubeiOrQuanguo = 'other';
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv[$hubeiOrQuanguo]); //解析中奖率 防止出错
//        //降低中奖率
//        if (!in_array(intval(date('H')), [10])) {
//            if ($hubeiOrQuanguo == 'hubei') {
//                $zhongjianglv = 0.60;       //湖北高峰期以外的中奖率
//            }
//            else{
//                $zhongjianglv = 0.30;    //外地 高峰期以外的中奖率
//            }
//        }
        $quanZhong = 100;
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息
        $prizeCountKey = $redisCountKey . ':' . $hubeiOrQuanguo . ':' . $dateStr;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
//            dd($prizeCountKey);
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        /*配置中奖统计*/
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $moneyStr = strval($arr['money']);
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$moneyStr];
            $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit[$hubeiOrQuanguo][$dateStr][$moneyStr]['limit'];
            $this->prizeConf['prize'][$k]['v'] = $arr['v'] * $quanZhong;   //中奖权重 增加除不中奖以外的权重
        }
        /*去除奖品发完的奖项*/
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            if ($arr['count'] < $arr['limit']) {
                $finalConf[] = $arr;
                $jingduSum += $arr['v'];
            }
        }
        if (count($finalConf) > 0) {  //奖品还没发完
            $jingduSum = ceil($jingduSum / $zhongjianglv);
            $this->prizeConf['notPirze']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
            $finalConf['resNotPrize'] = $this->prizeConf['notPirze'];
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['afterPirze']['v'] = 1000;
            $finalConf['resNotPrize'] = $this->prizeConf['afterPirze'];
        }
        /*计算百分比*/
        foreach ($finalConf as $k => $arr) {
            $finalConf[$k]['v100'] = round($arr['v'] * 100 / $jingduSum, 2) . '%';
        }
        /*随机抽奖*/
        foreach ($finalConf as $key => $prize) {
            $randNum = mt_rand(1, $jingduSum);
            if ($randNum <= $prize['v']) {
                $resultPrize = $prize;
                break;
            } else {
                $jingduSum -= $prize['v'];
            }
        }
        return ['resultPrize' => $resultPrize, 'finalConf' => $finalConf, 'prizeConf' => $this->prizeConf, 'prizeCountKey' => $prizeCountKey];
    }

    /*
     * 转换中奖率
     * */
    public function parseZhongJiangLv($numberic)
    {
        if (!is_numeric($numberic)) {
            throw new \Exception('中奖率参数不正确', -1);
        }
        while ($numberic > 1) {
            $numberic /= 10;
        }
        return $numberic * 1;
    }

    /*
     * 处理抽奖 -- 结束
     * #######################################################################################################################################################
     * */
    public function getZJLv()
    {
        $res = [];
        $res['hubei'] = $this->parseZhongJiangLv($this->zhongJiangLv['hubei']);
        $res['other'] = $this->parseZhongJiangLv($this->zhongJiangLv['other']);
//        $res['other'] = 0.3;
        //降低中奖率
//        if (!in_array(intval(date('H')), [10])) {
//            $res['hubei'] = 0.60;       //湖北高峰期以外的中奖率
//        }
        $res['hubei_str'] = round($res['hubei'] * 100, 2) . "%";
        $res['other_str'] = round($res['other'] * 100, 2) . "%";
        return $res;
    }
}
