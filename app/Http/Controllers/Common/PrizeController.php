<?php


namespace App\Http\Controllers\Common;

use App\Models\China;

class PrizeController
{
    protected $config = null;
    protected $itemName = null;         //项目名称
    protected $winningRate = null;      //中奖率
    protected $hotWinningRate = null;   //高峰期中奖率
    protected $prizeDate = null;        //抽奖日期
    protected $prizeArea = null;        //抽奖地区
    protected $prizeTime = null;        //高峰期时间
    protected $prize = null;            //奖品数组
    protected $notPrize = null;         //未中奖数组
    protected $address_code = null;     //地区代码
    protected $dateStr = null;          //日期字符串
    protected $redisCountKey = null;    //redisKey值

    /**
     * PrizeController constructor.
     * @param $itemName
     * @param $redisCountKey
     * @param $dateStr
     * @param null $address_code
     */
    public function __construct($itemName, $redisCountKey, $dateStr = null, $address_code = null)
    {
        $this->itemName = $itemName;
        $this->redisCountKey = $redisCountKey;
        $this->dateStr = $dateStr;
        $this->address_code = $address_code;
        $this->config = require(__DIR__ . '/PrizeConfig.php');

    }

    /**
     * 随机抽奖  中奖几率始终不变
     * @return array
     * @throws \Exception
     */
    public function fixRandomPrize()
    {
        if (!$this->validateItemName()) {
            return response()->json('没有找到该项目', 404);
        }
        $resultPrize = null;
        $prizeCountKey = $this->getPrizeCountKey();
        $prizeConf = $this->prizeConf();
        $finalConf = $this->finalConf();
        /*随机抽奖*/
        foreach ($finalConf as $key => $prize) {
            $randNum = mt_rand(1, $finalConf['precision']);
            if ($randNum <= $prize['v']) {
                $resultPrize = $prize;
                break;
            } else {
                $finalConf['precision'] -= $prize['v'];
            }
        }
        return ['resultPrize' => $resultPrize, 'finalConf' => $finalConf, 'prizeConf' => $prizeConf, 'prizeCountKey' => $prizeCountKey];
    }

    /**
     * 获取地区信息
     * @return string
     */
    private function getAreaStr()
    {
        $area = China::where('name', 'like', '%' . $this->prizeArea . '%')->first();
        $areaId = substr($area->id, 0, 2);
        if (preg_match('/^' . $areaId . '\d*/', $this->address_code)) {  //目标地区
            return $area = 'target';
        } else {   //其他地区
            return $area = 'other';
        }
    }

    /**
     * 获取redis中奖Key
     * @return string
     */
    private function getPrizeCountKey()
    {
        if ($this->prizeArea && $this->address_code != null) {
            $prizeCountKey = $this->redisCountKey . ':' . $this->getAreaStr() . ':' . $this->dateStr;
        } else {
            $prizeCountKey = $this->redisCountKey;
        }
        return $prizeCountKey;
    }

    /**
     * 从redis中获取中奖数组
     * @return mixed
     */
    private function getPrizeCountArr()
    {
        $prizeCountKey = $this->getPrizeCountKey();
        $redis = app('redis');
        $redis->select(12);
//        dd($prizeCountKey);
        if (!$prizeCountArr = $redis->hGetAll($prizeCountKey)) {
            return false;
        } else {
            return $prizeCountArr;
        }
    }

    /**
     * 解析中奖率
     * @return float|int
     * @throws \Exception
     */
    private function winningRate()
    {
        //获取初始中奖率
        $winningRate = $this->winningRate;
        //判断中奖率是否为数组
        if (is_array($winningRate)) {
            $areaStr = $this->getAreaStr();
            $winningRate = $this->parseWinningRate($winningRate[$areaStr]);
            //降低中奖率
//            dd($this->hotWinningRate);
            if (!in_array(intval(date('H')), [$this->prizeTime]) && $this->hotWinningRate) {
                if ($areaStr == 'target') {
                    $winningRate = $this->hotWinningRate['target'];       //目标地区高峰期以外的中奖率
                } else {
                    $winningRate = $this->hotWinningRate['other'];    //其他地区高峰期以外的中奖率
                }
            }
            return $winningRate = $this->parseWinningRate($winningRate); //解析中奖率 防止出错
        }
        //降低中奖率
        if (!in_array(intval(date('H')), [$this->prizeTime]) && $this->hotWinningRate) {
            $winningRate = $this->hotWinningRate;       //目标地区高峰期以外的中奖率
        }
        return $winningRate = $this->parseWinningRate($winningRate); //解析中奖率 防止出错
    }

    /**
     * 初始化中奖数组
     * @return array
     */
    private function initPrizeConf()
    {
        $prizeConf = [];
        foreach ($this->prize as $key => $value) {
            $prizeConf['prize'][$key]['prize_id'] = $key + 1;
            $prizeConf['prize'][$key]['prize_level'] = $value[0];
            $prizeConf['prize'][$key]['prize_name'] = $value[1];
            $prizeConf['prize'][$key]['cost'] = $value[2];
            $prizeConf['prize'][$key]['v'] = $value[3];
            $prizeConf['prize'][$key]['count'] = 0;
            $prizeConf['prize'][$key]['limit'] = 0;
        }
        if ($this->notPrize) {
            foreach ($this->notPrize as $k => $item) {
                $prizeConf[$item[0]] = [
                    'prize_id' => 20 + $k,
                    'prize_level' => '未中奖',
                    'prize_name' => $item[1],
                    'cost' => 0,
                    'v' => $item[2],
                    'count' => 0,
                    'limit' => $item[3]];
            }
        } else {
            $prizeConf['notPrize'] = [
                'prize_id' => 0,
                'prize_level' => '未中奖',
                'prize_name' => '未中奖',
                'cost' => 0,
                'v' => 100,
                'count' => 0,
                'limit' => 100000];
        }
        return $prizeConf;
    }

    /**
     * 获取中奖数量
     * @return array
     */
    private function prizeConfLimit()
    {

        $limit = [];
        //如果有多个日期
        if ($this->prizeDate) {
            //如果有多个地区
            if ($this->prizeArea) {
                foreach ($this->prize as $key => $value) {
                    foreach ($value[4] as $k => $v) {
                        $limit['target'][$this->prizeDate[$k]][$value[2]] = ['limit' => $v];
                    }
                    foreach ($value[5] as $k => $v) {
                        $limit['other'][$this->prizeDate[$k]][$value[2]] = ['limit' => $v];
                    }
                }
            } else {
                foreach ($this->prize as $value) {
                    foreach ($value[4] as $k => $v) {
                        $limit[$this->prizeDate[$k]][$value[2]] = ['limit' => $v];
                    }
                }
            }
        } else {
            foreach ($this->prize as $key => $value) {
                $limit[$value[2]] = ['limit' => $value[4]];
            }
        }
        return $limit;
    }

    /**
     * 配置中奖统计信息并且去除奖品已发完的奖项
     * @return array
     */
    private function prizeConf()
    {
        $weight = 100;   //权重比例
        $prizeConf = $this->initPrizeConf();
        $prizeCountArr = $this->getPrizeCountArr();
        $limit = $this->prizeConfLimit();

        if (!$prizeCountArr) {
            return response()->json(['error' => '缓存获取中奖统计失败'], -2);
        }
        /*配置中奖统计*/
//        dd($limit);
//        dd($prizeConf['prize']);
        foreach ($prizeConf['prize'] as $k => $arr) {
            $prizeStr = strval($arr['cost']);
            $prizeConf['prize'][$k]['count'] = $prizeCountArr[$prizeStr];
            if ($this->prizeDate) {
                $prizeConf['prize'][$k]['limit'] = $limit[$this->getAreaStr()][$this->dateStr][$prizeStr]['limit'];
            } else {
                $prizeConf['prize'][$k]['limit'] = $limit[$prizeStr]['limit'];
            }
            $prizeConf['prize'][$k]['v'] = $arr['v'] * $weight;   //中奖权重 增加除不中奖以外的权重
        }
        return $prizeConf;
    }

    /**
     * 最终抽奖数组
     * @return array
     * @throws \Exception
     */
    private function finalConf()
    {
        $prizeConf = $this->prizeConf();
        $precision = 0; //精度计数
        $winningRate = $this->winningRate();
        $finalConf = []; //最终生成的配置数组
        /*去除奖品发完的奖项*/
        foreach ($prizeConf['prize'] as $k => $arr) {
            if ($arr['count'] < $arr['limit']) {
                $finalConf[] = $arr;
                $precision += $arr['v'];
            }
        }
        if (count($finalConf) > 0) {  //奖品还没发完
            $precision = ceil($precision / $winningRate);
            $prizeConf['notPrize']['v'] = round($precision * (1 - $winningRate));  //四舍五入
            $finalConf['resNotPrize'] = $prizeConf['notPrize'];
        } else {    // 奖品已发完
            $precision = $prizeConf['notPrize']['v'] = 1000;
            if ($this->notPrize) {
                $finalConf['resNotPrize'] = $prizeConf['afterPrize'];
            } else {
                $finalConf['resNotPrize'] = $prizeConf['notPrize'];
            }
        }
        /*计算百分比*/
        foreach ($finalConf as $k => $arr) {
            $finalConf[$k]['v100'] = round($arr['v'] * 100 / $precision, 2) . '%';
        }
        $finalConf['precision'] = $precision;
//        dd($finalConf);
        return $finalConf;
    }

    /**
     * 验证项目,初始化配置数据
     * @param $itemName
     * @return bool
     */
    private function validateItemName()
    {
        if (!isset($this->config[$this->itemName])) {
            return false;
        }
        $this->winningRate = $this->config[$this->itemName]['winningRate'];
        $this->hotWinningRate = $this->config[$this->itemName]['hotWinningRate'] ?? null;
        $this->prizeDate = $this->config[$this->itemName]['prizeDate'] ?? null;
        $this->prizeArea = $this->config[$this->itemName]['prizeArea'] ?? null;
        $this->prizeTime = $this->config[$this->itemName]['prizeTime'] ?? null;
        $this->prize = $this->config[$this->itemName]['prize'];
        $this->notPrize = $this->config[$this->itemName]['notPrize'] ?? null;
        return true;
    }

    /**
     * 解析中奖率
     * @param $winningRate
     * @return float|int
     * @throws \Exception
     */
    private function parseWinningRate($winningRate)
    {
        if (!is_numeric($winningRate)) {
            throw new \Exception('中奖率参数不正确', -1);
        }
        while ($winningRate > 1) {
            $winningRate /= 10;
        }
        return $winningRate * 1;
    }
}
