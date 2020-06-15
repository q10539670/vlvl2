<?php

namespace App\Models\Qwt\Dx190925;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;
use EasyWeChat\Factory;

class User extends Model
{
    //

    protected $table = 'dx190925_user';

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
     *  抽卡
     */
    public static function getCardsArr($user)
    {
        $cards = [];
        $hasCards = [];
        $noCards = [];
        for ($i = 'a'; $i <= 'f'; $i++) {
            if ($user->$i > 0) {
                $hasCards[] .= $i;
            } else {
                $noCards[] .= $i;
            }
        }
        $cards['hasCards'] = $hasCards;
        $cards['noCards'] = $noCards;
        return $cards;
    }

    /*
     * 处理抽奖 -- 开始
     * #######################################################################################################################################################
     * */
    private $zhongJiangLv = 0.15;  //设置中奖率 如果大于 1 始终会转化为0~1之间的小数
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
//            0 => ['prize_id' => 1, 'prize_level_name' => '一等奖', 'prize_name' => '100元话费', 'money' => 100, 'v' => 10, 'count' => 0, 'limit' => 0],
//            1 => ['prize_id' => 2, 'prize_level_name' => '二等奖', 'prize_name' => '50元话费', 'money' => 50, 'v' => 20, 'count' => 0, 'limit' => 0],
//            2 => ['prize_id' => 3, 'prize_level_name' => '三等奖', 'prize_name' => '30元话费', 'money' => 30, 'v' => 60, 'count' => 0, 'limit' => 0],
            3 => ['prize_id' => 3, 'prize_level_name' => '四等奖', 'prize_name' => '10元话费', 'money' => 10, 'v' => 90, 'count' => 0, 'limit' => 0],
        ],
        /*不中奖   --未中奖*/
        'notPirze' => ['prize_id' => 20, 'prize_level_name' => '未中奖', 'prize_name' => '未中奖', 'money' => 0, 'v' => 100, 'count' => 0, 'limit' => 100000],
    ];

    protected $prizeConfLimit = [
        /*测试*/
        'test' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '10' => ['money' => 10, 'limit' => 9],
        ],
        /*第一天*/
        '20190927' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '10' => ['money' => 10, 'limit' => 10],
        ],
        /*第二天*/
        '20190928' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '10' => ['money' => 10, 'limit' => 20],
        ],
        /*第三天*/
        '20190929' => [
            '0' => ['money' => 0, 'limit' => 100000],
            '10' => ['money' => 10, 'limit' => 36],
        ],
    ];

    /*
     * 随机抽奖  中奖几率始终不变
     * */
    public function fixRandomPrize($redisCountKey, $dateStr)
    {
        if (!in_array($dateStr, ['20190927', '20190928', '20190929'])) {
            $dateStr = 'test';
        }
        $zhongjianglv = $this->parseZhongJiangLv($this->zhongJiangLv); //解析中奖率 防止出错
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
        $prizeCountKey = $redisCountKey . ':' . $dateStr;
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
            $this->prizeConf['notPirze']['v'] = round($jingduSum * (1 - $zhongjianglv));  //四舍五入
            $finalConf['resNotPrize'] = $this->prizeConf['notPirze'];
        } else {    // 奖品已发完
            $jingduSum = $this->prizeConf['notPirze']['v'] = 1000;
            $finalConf['resNotPrize'] = $this->prizeConf['notPirze'];
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
        $res = $this->parseZhongJiangLv($this->zhongJiangLv);
        $res = round($res * 100, 2) . "%";
        return $res;
    }

    /*
     * 判断是否有target参数
     */
    public function isHadId($url)
    {
        $paramStr = substr(strstr($url, '?'), 1);
        $paramArr = explode('&', $paramStr);
        //从$referer中提取target_user_id
        foreach ($paramArr as $param) {
            $params = explode('=', $param);
            if ($params['0'] == 'target_user_id') {
                $targetUserId = $params['1'];
                return $targetUserId;
            }
        }
        return false;
    }
}
