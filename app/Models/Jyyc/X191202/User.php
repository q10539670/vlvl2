<?php


namespace App\Models\Jyyc\X191202;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'x191202_user';

    protected $guarded = [];

    protected $prize = [
        1 => ['prize_id' => 1, 'prize_name' => 'LA MER/海蓝之谜 经典传奇精华面霜 30ml', 'count' => 0, 'limit' => 40],
        2 => ['prize_id' => 2, 'prize_name' => '雅诗兰黛小棕瓶精华两件套装（精华50ml+眼霜15ml)', 'count' => 0, 'limit' => 50],
        3 => ['prize_id' => 3, 'prize_name' => 'SK-II经典大红瓶面霜80g(滋润版）', 'count' => 0, 'limit' => 50],
        4 => ['prize_id' => 4, 'prize_name' => '娇韵诗 双萃焕活修护精华露 50ml', 'count' => 0, 'limit' => 40],
        5 => ['prize_id' => 5, 'prize_name' => '迪奥999经典口红套装（滋润-哑光-金属）', 'count' => 0, 'limit' => 30]
    ];

    /**
     * 获取奖品信息
     */
    public function getPrize($redisBaseKey)
    {
        $redis = app('redis');
        $redis->select(12);
        if (!$prizeCountArr = $redis->hGetAll($redisBaseKey)) {
            throw new \Exception('缓存获取奖品数量统计失败', -2);
        }
        $finalPrize = [];
        foreach ($this->prize as $k => $arr) {
            $prize_id = $this->prize[$k]['prize_id'];
            $this->prize[$k]['count'] = $prizeCountArr[$prize_id];
            $finalPrize[] = $this->prize[$k];
        }
        return $finalPrize;
    }

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
    private $zhongJiangLv = 1;//设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '专属折扣', 'v' => 1, 'count' => 0, 'limit' => 20],
        ],
        /*不中奖数组  该数组权重只对 中奖几率先大后小方式抽奖有效*/
        'NotPirze' => ['prize_id' => 0, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'v' => 100, 'count' => 0, 'limit' => 100000],
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
        $finalConf = []; //最终生成的配置数组
        $jingduSum = 0; //精度计数
        $resultPrize = null; // 最终的中奖的信息

        foreach ($this->prizeConf['prize'] as $k => $arr) {
            $prize_id = $this->prizeConf['prize'][$k]['prize_id'];
            $this->prizeConf['prize'][$k]['count'] = $prizeCountArr[$prize_id];
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
