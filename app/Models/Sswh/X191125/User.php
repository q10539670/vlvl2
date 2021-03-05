<?php


namespace App\Models\Sswh\X191125;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x191125_user';

    protected $guarded = [];

    /**
     * 生成唯一随机码
     * @param User $user
     * @param $len
     * @param $filed
     * @return string
     */
    public static function getUniqueCode($len)
    {
        $randomCode = self::getRandomCode($len);
        if (!self::where('prize_code', $randomCode)->first()) {
            return $randomCode;
        }
        return self::getUniqueCode($len);
    }

    /**
     * 生成随机码
     * @param $len
     * @return string
     */
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
        'yuelu' => 0.9,
        'other' => 0.25,
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
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '龙小湖雨伞一把', 'money' => 1, 'v' => 2, 'count' => 0, 'limit' => 0],
            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '茶颜悦色-声声乌龙', 'money' => 2, 'v' =>5, 'count' => 0, 'limit' => 0],
            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '茶颜悦色-幽兰拿铁', 'money' => 3, 'v' => 10, 'count' => 0, 'limit' => 0],
            3 => ['prize_id' => 4, 'prize_level_name' => '四等奖', 'prize_name' => '果呀呀-28元优惠券', 'money' => 4, 'v' => 20, 'count' => 0, 'limit' => 0],
            4 => ['prize_id' => 5, 'prize_level_name' => '五等奖', 'prize_name' => '果呀呀-10元优惠券', 'money' => 5, 'v' => 30, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPrize' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        'yuelu' => [

            /*测试*/
            'test' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 3],
                '2' => ['money' => 2, 'limit' => 13],
                '3' => ['money' => 3, 'limit' => 25],
                '4' => ['money' => 4, 'limit' => 50],
                '5' => ['money' => 5, 'limit' => 0]
            ],
            /*第一天*/
            '20191128' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 3],
                '2' => ['money' => 2, 'limit' => 2],
                '3' => ['money' => 3, 'limit' => 1],
                '4' => ['money' => 4, 'limit' => 1],
                '5' => ['money' => 5, 'limit' => 0]
            ],
            /*第二天*/
            '20191129' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 1],
                '2' => ['money' => 2, 'limit' => 11],
                '3' => ['money' => 3, 'limit' => 18],
                '4' => ['money' => 4, 'limit' => 18],
                '5' => ['money' => 5, 'limit' => 41]
            ],
            /*第三天*/
            '20191130' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 1],
                '2' => ['money' => 2, 'limit' => 2],
                '3' => ['money' => 3, 'limit' => 2],
                '4' => ['money' => 4, 'limit' => 6],
                '5' => ['money' => 5, 'limit' => 2]
            ],
        ],
        'other'=>[
            /*测试*/
            'test' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 0],
                '2' => ['money' => 2, 'limit' => 0],
                '3' => ['money' => 3, 'limit' => 0],
                '4' => ['money' => 4, 'limit' => 2],
                '5' => ['money' => 5, 'limit' => 2]
            ],
            /*第一天*/
            '20191128' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 0],
                '2' => ['money' => 2, 'limit' => 0],
                '3' => ['money' => 3, 'limit' => 0],
                '4' => ['money' => 4, 'limit' => 4],
                '5' => ['money' => 5, 'limit' => 4]
            ],
            /*第二天*/
            '20191129' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 0],
                '2' => ['money' => 2, 'limit' => 0],
                '3' => ['money' => 3, 'limit' => 0],
                '4' => ['money' => 4, 'limit' => 2],
                '5' => ['money' => 5, 'limit' => 2]
            ],
            /*第三天*/
            '20191130' => [
                '0' => ['money' => 0, 'limit' => 100000],
                '1' => ['money' => 1, 'limit' => 0],
                '2' => ['money' => 2, 'limit' => 0],
                '3' => ['money' => 3, 'limit' => 0],
                '4' => ['money' => 4, 'limit' => 2],
                '5' => ['money' => 5, 'limit' => 2]
            ]
        ]
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $dateStr, $address_code)
    {
        if (!in_array($dateStr, ['20191128', '20191129', '20191130'])) {
            $dateStr = 'test';
        }
        $yueluOrChangsha = null;
        if (430104 == $address_code) {  //岳麓区
            $yueluOrChangsha = 'yuelu';
        } else {   //长沙除岳麓以外
            $yueluOrChangsha = 'other';
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv[$yueluOrChangsha]); //解析中奖率 防止出错
        $quanZhong = 10;
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息
        $prizeCountKey = $redisCountKey . ':' . $yueluOrChangsha.':'. $dateStr;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
//            dd($prizeCountKey);
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
//        dd($prizeCountArr);
        /*配置中奖统计*/
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $moneyStr = strval($arr['money']);
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$moneyStr];
            $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit[$yueluOrChangsha][$dateStr][$moneyStr]['limit'];
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
            $jingduSum = $jingduSum / $zhongjianglv;
            $this->prizeConf['notPrize']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['notPrize']['v'] = 1000;
        }

        $finalConf[] = $this->prizeConf['notPrize'];

        /*计算百分比*/
        foreach ($finalConf as $k => $arr) {
            $finalConf[$k]['v100'] = round($arr['v'] * 100 / $jingduSum, 2) . '%';
        }
//        dd($finalConf,$jingduSum);
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
