<?php


namespace App\Models\Sswh\X200615;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x200615_user';

    protected $guarded = [];


    /*
    * 处理抽奖 -- 开始
    * #######################################################################################################################################################
    * */
    private $zhongJiangLv = 0.1;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
            0 => ['prize_id' => 1, 'prize_level_name' => '幸运奖', 'prize_name' => '保温饭盒', 'v' => 100, 'count' => 0, 'limit' => 0],
            1 => ['prize_id' => 2, 'prize_level_name' => '三等奖', 'prize_name' => '飞利浦空调扇', 'v' => 3, 'count' => 0, 'limit' => 0],
//            2 => ['prize_id' => 3, 'prize_level_name' => '二等奖', 'prize_name' => '小米电视55英寸', 'v' => 0, 'count' => 0, 'limit' => 0],
//            3 => ['prize_id' => 4, 'prize_level_name' => '一等奖', 'prize_name' => '戴森TP05空气净化器', 'v' => 0, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPrize' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        /*测试*/
        'test' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 10],
            '2' => ['prize_id' => 2, 'limit' => 10],
        ],

        /*第一天*/
        '20200618' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 30],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第二天*/
        '20200619' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 30],
            '2' => ['prize_id' => 2, 'limit' => 2],
        ],
        /*第三天*/
        '20200620' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 30],
            '2' => ['prize_id' => 2, 'limit' => 1],
        ],
        /*第四天*/
        '20200621' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 10],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第五天*/
        '20200622' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第六天*/
        '20200623' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第七天*/
        '20200624' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第八天*/
        '20200625' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第九天*/
        '20200626' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第十天*/
        '20200627' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ],
        /*第十一天*/
        '20200628' => [
            '0' => ['prize_id' => 0, 'limit' => 100000],
            '1' => ['prize_id' => 1, 'limit' => 0],
            '2' => ['prize_id' => 2, 'limit' => 0],
        ]
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $dateStr)
    {
        if (!in_array($dateStr, ['20200618', '20200619', '20200620', '20200621', '20200622', '20200623', '20200624', '20200625', '20200626', '20200627', '20200628'])) {
            $dateStr = 'test';
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
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
            $moneyStr = strval($arr['prize_id']);
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
