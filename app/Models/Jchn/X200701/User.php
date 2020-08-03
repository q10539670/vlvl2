<?php


namespace App\Models\Jchn\X200701;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'l20200701_hn_user';

    protected $guarded = [];

    public function images() {
        return $this->hasMany('App\Models\Sswh\X200701\Images');
    }
    /*
    * 处理抽奖 -- 开始
    * #######################################################################################################################################################
    * */
    private $zhongJiangLv = 0.3;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
            0 => ['prize_id' => 1, 'prize_level_name' => '七等奖', 'prize_name' => '战马笔袋', 'money' => 1, 'v' => 50, 'count' => 0, 'limit' => 0],
            1 => ['prize_id' => 2, 'prize_level_name' => '六等奖', 'prize_name' => '爱奇艺月卡', 'money' => 10, 'v' => 200, 'count' => 0, 'limit' => 0],
            2 => ['prize_id' => 3, 'prize_level_name' => '五等奖', 'prize_name' => '赠饮(6听礼包装)', 'money' => 50, 'v' => 40, 'count' => 0, 'limit' => 0],
            3 => ['prize_id' => 4, 'prize_level_name' => '四等奖', 'prize_name' => '球场免费体验券', 'money' => 50, 'v' => 100, 'count' => 0, 'limit' => 0],
            4 => ['prize_id' => 5, 'prize_level_name' => '三等奖', 'prize_name' => '欢乐谷全日门票', 'money' => 50, 'v' => 30, 'count' => 0, 'limit' => 0],
            5 => ['prize_id' => 6, 'prize_level_name' => '二等奖', 'prize_name' => '玛雅全日门票', 'money' => 50, 'v' => 30, 'count' => 0, 'limit' => 0],
            6 => ['prize_id' => 7, 'prize_level_name' => '一等奖', 'prize_name' => 'adidas椰子鞋', 'money' => 50, 'v' => 5, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPrize' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        /*测试*/
        'test' => [
            '1' => ['prize_id' => 1, 'limit' => 10],
            '2' => ['prize_id' => 2, 'limit' => 5],
            '3' => ['prize_id' => 3, 'limit' => 5],
            '4' => ['prize_id' => 4, 'limit' => 5],
            '5' => ['prize_id' => 5, 'limit' => 5],
            '6' => ['prize_id' => 6, 'limit' => 5],
            '7' => ['prize_id' => 7, 'limit' => 5],
            '0' => ['prize_id' => 0, 'limit' => 100000]
        ],
        'gold' => [
            '1' => ['prize_id' => 1, 'limit' => 50],
            '2' => ['prize_id' => 2, 'limit' => 200],
            '3' => ['prize_id' => 3, 'limit' => 40],
            '4' => ['prize_id' => 4, 'limit' => 100],
            '5' => ['prize_id' => 5, 'limit' => 30],
            '6' => ['prize_id' => 6, 'limit' => 30],
            '7' => ['prize_id' => 7, 'limit' => 1],
            '0' => ['prize_id' => 0, 'limit' => 100000]
        ]
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $timeStr)
    {
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
        $quanZhong = 100;
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息
        $prizeCountKey = $redisCountKey . ':' . $timeStr;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
//            dd($prizeCountKey);
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        //配置
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $idStr = strval($arr['prize_id']);
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$idStr];
            if ($timeStr == 'test') {
                $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit['test'][$idStr]['limit'];
            } else {
                $this->prizeConf['prize'][$k]['limit'] = $this->prizeConfLimit['gold'][$idStr]['limit'];
            }
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
