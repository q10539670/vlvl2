<?php


namespace App\Models\Sswh\X190929;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x190929_user';

    protected $guarded = [];
    /*
    * 处理抽奖 -- 开始
    * #######################################################################################################################################################
    * */
    private $zhongJiangLv = 100;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '挂烫机', 'v' => 2, 'count' => 0, 'limit' => 5],
            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '加湿器', 'v' => 2, 'count' => 0, 'limit' => 5],
            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '吹风机', 'v' => 2, 'count' => 0, 'limit' => 5],
            3 => ['prize_id' => 4, 'prize_level_name' => '四等奖', 'prize_name' => '购物卡', 'v' => 4, 'count' => 0, 'limit' => 10],
            4 => ['prize_id' => 5, 'prize_level_name' => '五等奖', 'prize_name' => '保温杯', 'v' => 4, 'count' => 0, 'limit' => 10],
            5 => ['prize_id' => 6, 'prize_level_name' => '六等奖', 'prize_name' => '免费参观军运村', 'v' => 100, 'count' => 0, 'limit' => 100000],
            6 => ['prize_id' => 7, 'prize_level_name' => '七等奖', 'prize_name' => '军运会玩偶', 'v' => 1, 'count' => 0, 'limit' => 2],
            7 => ['prize_id' => 8, 'prize_level_name' => '八等奖', 'prize_name' => '雨伞', 'v' => 4, 'count' => 0, 'limit' => 10],
            8 => ['prize_id' => 9, 'prize_level_name' => '九等奖', 'prize_name' => '马克杯', 'v' => 4, 'count' => 0, 'limit' => 10],
        ],
        /*不中奖数组  该数组权重只对 中奖几率先大后小方式抽奖有效*/
        'NotPirze' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'v' => 100, 'count' => 0, 'limit' => 100000],

    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey)
    {
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息
        $prizeCountKey = $redisCountKey;
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
            throw new \Exception('缓存获取中奖统计失败', -2);
        }
        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $prize_id = $this->prizeConf['prize'][$k]['prize_id'];
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$prize_id];
            $this->prizeConf['prize'][$k]['v'] = $arr['v'] * 1000;   //中奖权重 增加除不中奖以外的权重
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
            $this->prizeConf['NotPirze']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['NotPirze']['v'] = 1000;
        }
        $finalConf[] = $this->prizeConf['NotPirze'];
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
