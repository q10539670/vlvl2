<?php


namespace App\Models\Sswh\X191220;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x191220_user';

    protected $guarded = [];

    /*
     * 生成唯一随机码
     * */
    public static function getUniqueCode($len)
    {
        $randomCode = self::getRandomCode($len);
        if (!self::where('verification_code', $randomCode)->first()) {
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
    * 处理抽奖 -- 开始
    * #######################################################################################################################################################
    * */
    private $zhongJiangLv = [
        'yuelu' => 0.7,
        'other' => 0.3,
    ];  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => 'MOSH保温杯', 'money' => 1, 'v' => 2, 'count' => 0, 'limit' => 0],
            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '完美日记口红', 'money' => 2, 'v' => 3, 'count' => 0, 'limit' => 0],
            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '完美日记睫毛膏', 'money' => 3, 'v' => 5, 'count' => 0, 'limit' => 0],
            3 => ['prize_id' => 4, 'prize_level_name' => '四等奖', 'prize_name' => '故宫日历', 'money' => 4, 'v' => 10, 'count' => 0, 'limit' => 0],
            4 => ['prize_id' => 5, 'prize_level_name' => '五等奖', 'prize_name' => '十点日历', 'money' => 5, 'v' => 10, 'count' => 0, 'limit' => 0],
            5 => ['prize_id' => 6, 'prize_level_name' => '六等奖', 'prize_name' => '龙小湖雨伞', 'money' => 6, 'v' => 20, 'count' => 0, 'limit' => 0],
            6 => ['prize_id' => 7, 'prize_level_name' => '七等奖', 'prize_name' => '龙小湖积木', 'money' => 7, 'v' => 20, 'count' => 0, 'limit' => 0],
            7 => ['prize_id' => 8, 'prize_level_name' => '八等奖', 'prize_name' => '茶颜悦色-声声乌龙', 'money' => 8, 'v' => 60, 'count' => 0, 'limit' => 0],
            8 => ['prize_id' => 9, 'prize_level_name' => '九等奖', 'prize_name' => '茶颜悦色-幽兰拿铁', 'money' => 9, 'v' => 80, 'count' => 0, 'limit' => 0],
            9 => ['prize_id' => 10, 'prize_level_name' => '十等奖', 'prize_name' => '果呀呀-28元优惠券', 'money' => 10, 'v' => 60, 'count' => 0, 'limit' => 0],
            10 => ['prize_id' => 11, 'prize_level_name' => '十一等奖', 'prize_name' => '果呀呀-10元优惠券', 'money' => 11, 'v' => 80, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPrize' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        /*测试*/
        'test' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 1],
            '2' => ['money' => 2, 'limit' => 1],
            '3' => ['money' => 3, 'limit' => 1],
            '4' => ['money' => 4, 'limit' => 1],
            '5' => ['money' => 5, 'limit' => 1],
            '6' => ['money' => 6, 'limit' => 1],
            '7' => ['money' => 7, 'limit' => 1],
            '8' => ['money' => 8, 'limit' => 1],
            '9' => ['money' => 9, 'limit' => 1],
            '10' => ['money' => 10, 'limit' => 1],
            '11' => ['money' => 11, 'limit' => 1],
        ],
        /*第一天*/
        '20191224' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 1],
            '2' => ['money' => 2, 'limit' => 3],
            '3' => ['money' => 3, 'limit' => 1],
            '4' => ['money' => 4, 'limit' => 2],
            '5' => ['money' => 5, 'limit' => 1],
            '6' => ['money' => 6, 'limit' => 1],
            '7' => ['money' => 7, 'limit' => 1],
            '8' => ['money' => 8, 'limit' => 8],
            '9' => ['money' => 9, 'limit' => 8],
            '10' => ['money' => 10, 'limit' => 7],
            '11' => ['money' => 11, 'limit' => 12],
        ],
        /*第二天*/
        '20191225' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 3],
            '3' => ['money' => 3, 'limit' => 1],
            '4' => ['money' => 4, 'limit' => 3],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 1],
            '7' => ['money' => 7, 'limit' => 1],
            '8' => ['money' => 8, 'limit' => 8],
            '9' => ['money' => 9, 'limit' => 8],
            '10' => ['money' => 10, 'limit' => 7],
            '11' => ['money' => 11, 'limit' => 12],
        ],
        /*第三天*/
        '20191226' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 0],
            '3' => ['money' => 3, 'limit' => 0],
            '4' => ['money' => 4, 'limit' => 0],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 0],
            '7' => ['money' => 7, 'limit' => 0],
            '8' => ['money' => 8, 'limit' => 0],
            '9' => ['money' => 9, 'limit' => 0],
            '10' => ['money' => 10, 'limit' => 0],
            '11' => ['money' => 11, 'limit' => 0],
        ],
        /*第四天*/
        '20191227' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 0],
            '3' => ['money' => 3, 'limit' => 0],
            '4' => ['money' => 4, 'limit' => 0],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 0],
            '7' => ['money' => 7, 'limit' => 0],
            '8' => ['money' => 8, 'limit' => 0],
            '9' => ['money' => 9, 'limit' => 0],
            '10' => ['money' => 10, 'limit' => 0],
            '11' => ['money' => 11, 'limit' => 0],
        ],
        /*第五天*/
        '20191228' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 0],
            '3' => ['money' => 3, 'limit' => 0],
            '4' => ['money' => 4, 'limit' => 0],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 0],
            '7' => ['money' => 7, 'limit' => 0],
            '8' => ['money' => 8, 'limit' => 0],
            '9' => ['money' => 9, 'limit' => 0],
            '10' => ['money' => 10, 'limit' => 0],
            '11' => ['money' => 11, 'limit' => 0],
        ],
        /*第六天*/
        '20191229' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 0],
            '3' => ['money' => 3, 'limit' => 0],
            '4' => ['money' => 4, 'limit' => 0],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 0],
            '7' => ['money' => 7, 'limit' => 0],
            '8' => ['money' => 8, 'limit' => 0],
            '9' => ['money' => 9, 'limit' => 0],
            '10' => ['money' => 10, 'limit' => 0],
            '11' => ['money' => 11, 'limit' => 0],
        ],
        /*第七天*/
        '20191230' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 1],
            '2' => ['money' => 2, 'limit' => 3],
            '3' => ['money' => 3, 'limit' => 2],
            '4' => ['money' => 4, 'limit' => 2],
            '5' => ['money' => 5, 'limit' => 1],
            '6' => ['money' => 6, 'limit' => 2],
            '7' => ['money' => 7, 'limit' => 1],
            '8' => ['money' => 8, 'limit' => 10],
            '9' => ['money' => 9, 'limit' => 10],
            '10' => ['money' => 10, 'limit' => 7],
            '11' => ['money' => 11, 'limit' => 13],
        ],
        /*第八天*/
        '20191231' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 0],
            '3' => ['money' => 3, 'limit' => 0],
            '4' => ['money' => 4, 'limit' => 0],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 0],
            '7' => ['money' => 7, 'limit' => 0],
            '8' => ['money' => 8, 'limit' => 0],
            '9' => ['money' => 9, 'limit' => 0],
            '10' => ['money' => 10, 'limit' => 0],
            '11' => ['money' => 11, 'limit' => 0],
        ],
        /*第九天*/
        '20200101' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '1' => ['money' => 1, 'limit' => 0],
            '2' => ['money' => 2, 'limit' => 3],
            '3' => ['money' => 3, 'limit' => 1],
            '4' => ['money' => 4, 'limit' => 3],
            '5' => ['money' => 5, 'limit' => 0],
            '6' => ['money' => 6, 'limit' => 1],
            '7' => ['money' => 7, 'limit' => 2],
            '8' => ['money' => 8, 'limit' => 9],
            '9' => ['money' => 9, 'limit' => 8],
            '10' => ['money' => 10, 'limit' => 7],
            '11' => ['money' => 11, 'limit' => 13],
        ],
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $dateStr, $address_code)
    {
        if (!in_array($dateStr, ['20191224','20191225', '20191226', '20191227', '20191228', '20191229', '20191230', '20191231', '20200101'])) {
            $dateStr = 'test';
        }
        $yueluOrChangsha = null;
        if (430104 == $address_code) {  //岳麓区
            $yueluOrChangsha = 'yuelu';
        } else {   //长沙除岳麓以外
            $yueluOrChangsha = 'other';
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv[$yueluOrChangsha]); //解析中奖率 防止出错
        $quanZhong = 100;
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息
        $prizeCountKey = $redisCountKey . ':' . $dateStr;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
//            dd($prizeCountKey);
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        //配置
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $moneyStr = strval($arr['money']);
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$moneyStr];
            $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit[$dateStr][$moneyStr]['limit'];
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
            $this->prizeConf['notPrize']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
            $finalConf['resNotPrize'] = $this->prizeConf['notPrize'];
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['notPrize']['v'] = 1000;
            $finalConf['resNotPrize'] = $this->prizeConf['notPrize'];
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
