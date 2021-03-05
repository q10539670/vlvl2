<?php


namespace App\Models\Jyyc\X191211;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class User extends Model
{
    protected $table = 'x191211_user';

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
        $orderNum = 'jyyc191211' . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
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
            "mch_billno": "jyyc191211184118aa9aa10",
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
            "mch_billno": "jyyc191211184118aa9aa10",
            "mch_id": "1493373832",
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
    private $zhongJiangLv = 1;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
    private $prizeKey = 'prize';  // 数据库里面的 中奖类型索引的字段 名称
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
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '188元红包', 'money' => 188, 'v' => 2, 'count' => 0, 'limit' => 2],
            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '18.8元红包', 'money' => 18.8, 'v' => 10, 'count' => 0, 'limit' => 10],
            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '8.8元红包', 'money' => 8.8, 'v' => 25, 'count' => 0, 'limit' => 25],
            3 => ['prize_id' => 4, 'prize_level_name' => '四等奖', 'prize_name' => '6.8元红包', 'money' => 6.8, 'v' => 25, 'count' => 0, 'limit' => 25],
            4 => ['prize_id' => 5, 'prize_level_name' => '五等奖', 'prize_name' => '5.8元红包', 'money' => 5.8, 'v' => 40, 'count' => 0, 'limit' => 75],
            5 => ['prize_id' => 6, 'prize_level_name' => '六等奖', 'prize_name' => '2.8元红包', 'money' => 2.8, 'v' => 60, 'count' => 0, 'limit' => 125],
            6 => ['prize_id' => 7, 'prize_level_name' => '七等奖', 'prize_name' => '1.8元红包', 'money' => 1.8, 'v' => 80, 'count' => 0, 'limit' => 300],
            7 => ['prize_id' => 8, 'prize_level_name' => '八等奖', 'prize_name' => '1元红包', 'money' => 1, 'v' => 100, 'count' => 0, 'limit' => 425],
        ],
        /*不中奖   --未中奖*/
        'notPirze' => ['prize_id' => 20, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖   --奖品已发完*/
        'afterPirze' => ['prize_id' => 21, 'prize_level_name' => '未中奖', 'prize_name' => '红包已发完', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖数组  --发红包失败*/
        'failSendpack' => ['prize_id' => 22, 'prize_level_name' => '未中奖', 'prize_name' => '红包发送失败', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        '02' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 0],
            '3' =>  ['prize_id' => 3,  'limit' => 0],
            '4' =>  ['prize_id' => 4,  'limit' => 0],
            '5' =>  ['prize_id' => 5,  'limit' => 0],
            '6' =>  ['prize_id' => 6,  'limit' => 0],
            '7' =>  ['prize_id' => 7,  'limit' => 0],
            '8' =>  ['prize_id' => 8,  'limit' => 0],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*测试*/
        'test' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 1],
            '3' =>  ['prize_id' => 3,  'limit' => 1],
            '4' =>  ['prize_id' => 4,  'limit' => 1],
            '5' =>  ['prize_id' => 5,  'limit' => 1],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '7' =>  ['prize_id' => 7,  'limit' => 1],
            '8' =>  ['prize_id' => 8,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /* 09点 */
        '09' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 0],
            '3' =>  ['prize_id' => 3,  'limit' => 0],
            '4' =>  ['prize_id' => 4,  'limit' => 0],
            '5' =>  ['prize_id' => 5,  'limit' => 0],
            '6' =>  ['prize_id' => 6,  'limit' => 0],
            '7' =>  ['prize_id' => 7,  'limit' => 0],
            '8' =>  ['prize_id' => 8,  'limit' => 0],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*10点*/
        '10' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 25],
            '7' =>  ['prize_id' => 7,  'limit' => 40],
            '8' =>  ['prize_id' => 8,  'limit' => 73],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*11点*/
        '11' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 25],
            '7' =>  ['prize_id' => 7,  'limit' => 40],
            '8' =>  ['prize_id' => 8,  'limit' => 73],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*12点*/
        '12' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 10],
            '6' =>  ['prize_id' => 6,  'limit' => 15],
            '7' =>  ['prize_id' => 7,  'limit' => 50],
            '8' =>  ['prize_id' => 8,  'limit' => 60],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*13点*/
        '13' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 10],
            '6' =>  ['prize_id' => 6,  'limit' => 15],
            '7' =>  ['prize_id' => 7,  'limit' => 50],
            '8' =>  ['prize_id' => 8,  'limit' => 60],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*14点*/
        '14' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 25],
            '7' =>  ['prize_id' => 7,  'limit' => 40],
            '8' =>  ['prize_id' => 8,  'limit' => 73],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*15点*/
        '15' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 25],
            '7' =>  ['prize_id' => 7,  'limit' => 39],
            '8' =>  ['prize_id' => 8,  'limit' => 73],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*16点*/
        '16' => [
            '1' =>  ['prize_id' => 1,  'limit' => 0],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 25],
            '7' =>  ['prize_id' => 7,  'limit' => 40],
            '8' =>  ['prize_id' => 8,  'limit' => 73],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*17点*/
        '17' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 10],
            '6' =>  ['prize_id' => 6,  'limit' => 15],
            '7' =>  ['prize_id' => 7,  'limit' => 50],
            '8' =>  ['prize_id' => 8,  'limit' => 60],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*18点*/
        '18' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 10],
            '7' =>  ['prize_id' => 7,  'limit' => 40],
            '8' =>  ['prize_id' => 8,  'limit' => 70],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        /*19点*/
        '19' => [
            '1' =>  ['prize_id' => 1,  'limit' => 1],
            '2' =>  ['prize_id' => 2,  'limit' => 2],
            '3' =>  ['prize_id' => 3,  'limit' => 5],
            '4' =>  ['prize_id' => 4,  'limit' => 5],
            '5' =>  ['prize_id' => 5,  'limit' => 15],
            '6' =>  ['prize_id' => 6,  'limit' => 15],
            '7' =>  ['prize_id' => 7,  'limit' => 50],
            '8' =>  ['prize_id' => 8,  'limit' => 60],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $timeStr)
    {
        $prizeCountKey = $redisCountKey . ':' . $timeStr;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息

        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $idStr = strval($arr['prize_id']);
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$idStr];
            $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit[$timeStr][$idStr]['limit'];
            $this->prizeConf['prize'][$k]['v'] = $arr['v'] * 10;   //中奖权重 增加除不中奖以外的权重
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
}
