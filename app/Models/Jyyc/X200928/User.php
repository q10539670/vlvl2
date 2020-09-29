<?php


namespace App\Models\Jyyc\X200928;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class User extends Model
{
    protected $table = 'x200928_user';

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
        $orderNum = 'jyyc200928' . date('His') . 'aa' . $user_id . 'aa' . mt_rand(1, 10);
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
            'total_amount' => $money,  //单位为分，不小于100
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
            "mch_billno": "jyyc191203184118aa9aa10",
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
            "mch_billno": "jyyc191203184118aa9aa10",
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

    /*
    * 处理抽奖 -- 开始
    * #######################################################################################################################################################
    * */
    private $zhongJiangLv = 0.8;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
        'test'=>[0 => ['prize_id' => 1, 'prize_level_name' => '测试', 'prize_name' => '1元红包', 'money' => 1, 'v' => 1, 'count' => 0,  'limit' => 10]],
        /*中奖数组*/
        'prize' => [
            0 => ['prize_id' => 1, 'prize_level_name' => '六等奖', 'prize_name' => '1.88元红包', 'money' => 1.88, 'v' => 50, 'count' => 0,   'limit' => 50],
            1 => ['prize_id' => 2, 'prize_level_name' => '五等奖', 'prize_name' => '6.66元红包', 'money' => 6.66, 'v' => 30, 'count' => 0,   'limit' => 30],
            2 => ['prize_id' => 3, 'prize_level_name' => '四等奖', 'prize_name' => '8.88元红包', 'money' => 8.88, 'v' => 20, 'count' => 0,   'limit' => 20],
            3 => ['prize_id' => 4, 'prize_level_name' => '三等奖', 'prize_name' => '12.88元红包', 'money' => 12.88, 'v' => 15, 'count' => 0, 'limit' => 15],
            4 => ['prize_id' => 5, 'prize_level_name' => '二等奖', 'prize_name' => '66.60元红包', 'money' => 66.60, 'v' => 2, 'count' => 0,  'limit' => 2],
            5 => ['prize_id' => 6, 'prize_level_name' => '一等奖', 'prize_name' => '88.80元红包', 'money' => 88.80, 'v' => 1, 'count' => 0,  'limit' => 1],
        ],
        /*不中奖   --未中奖*/
        'notPrize' => ['prize_id' => 20, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖   --奖品已发完*/
        'afterPrize' => ['prize_id' => 21, 'prize_level_name' => '未中奖', 'prize_name' => '红包已发完', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
        /*不中奖数组  --发红包失败*/
        'failSendpack' => ['prize_id' => 22, 'prize_level_name' => '未中奖', 'prize_name' => '红包发送失败', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        'test'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20200930'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20201001'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20201002'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20201003'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20201004'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
        '20201005'=>[
            '1' =>  ['prize_id' => 1,  'limit' => 50],
            '2' =>  ['prize_id' => 2,  'limit' => 30],
            '3' =>  ['prize_id' => 3,  'limit' => 20],
            '4' =>  ['prize_id' => 4,  'limit' => 15],
            '5' =>  ['prize_id' => 5,  'limit' => 2],
            '6' =>  ['prize_id' => 6,  'limit' => 1],
            '20' => ['prize_id' => 20, 'limit' => 100000],
            '21' => ['prize_id' => 21, 'limit' => 100000],
            '22' => ['prize_id' => 22, 'limit' => 100000],
        ],
    ];
    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey)
    {
        $prizeCountKey = $redisCountKey;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
        //降低中奖率
//        if ($redis->get($redisCountKey . ':prizeNum') >= 15) {
//            $zhongjianglv = 0.07;       //奖品发放超过一半后
//        }
        $attr = 'prize';
        if (time()<strtotime('2020-09-30 08:00:00')) {
            $attr = 'test';
        }
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息

        foreach ($this->prizeConf[$attr] as $k => $arr) {
            $prize_id = $this->prizeConf[$attr][$k]['prize_id'];
            $this->prizeConf[$attr][$k]['count'] = $prizeCountArr[$prize_id];
            $this->prizeConf[$attr][$k]['v'] = $arr['v'] * 10;   //中奖权重 增加除不中奖以外的权重
        }
        /*去除奖品发完的奖项*/
        foreach ($this->prizeConf[$attr] as $k => $arr) {
            if ($arr['count'] < $arr['limit']) {
                $finalConf[] = $arr;
                $jingduSum += $arr['v'];
            }
        }
        if (count($finalConf) > 0) {  //奖品还没发完
            $jingduSum = ceil($jingduSum / $zhongjianglv);
            $this->prizeConf['notPrize']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
            $finalConf['resNotPrize'] = $this->prizeConf['notPrize'];
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['afterPrize']['v'] = 1000;
            $finalConf['resNotPrize'] = $this->prizeConf['afterPrize'];
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
