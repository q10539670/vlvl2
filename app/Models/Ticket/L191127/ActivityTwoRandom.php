<?php

namespace App\Models\Ticket\L191127;

use Illuminate\Database\Eloquent\Model;

class ActivityTwoRandom extends Model
{
    //
    protected $guarded = [];
    protected $table = 'auto_check_v3_act2';

    public $timestamps = false;

    const PRIZE_KEY = 'money';  //数据表的 中奖类型索引

    protected $prizeConf = [
        0 => [self::PRIZE_KEY => 500, 'prize' => '5 元红包', 'v' => 15, 'count' => 0, 'limit' => 150],
        1 => [self::PRIZE_KEY => 400, 'prize' => '4 元红包', 'v' => 20, 'count' => 0, 'limit' => 201],
        2 => [self::PRIZE_KEY => 300, 'prize' => '3 元红包', 'v' => 25, 'count' => 0, 'limit' => 230],
        3 => [self::PRIZE_KEY => 200, 'prize' => '2 元红包', 'v' => 36, 'count' => 0, 'limit' => 285],
        4 => [self::PRIZE_KEY => 100, 'prize' => '1 元红包', 'v' => 141, 'count' => 0, 'limit' => 1634],
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Ticket\L191127\User', 'user_id', 'id');
    }

    /*
     * 随机抽奖
     * 以上传时间计算
     * */
    public function randomPrize()
    {
        $finalConf = [];
        $jingduSum = 0;
        foreach ($this->prizeConf as $k => $arr) {
            if ($k != (count($this->prizeConf))) {
                $this->prizeConf[$k]['count'] =
                    self::where('status', 10)
                        ->where(self::PRIZE_KEY, $arr[self::PRIZE_KEY])
                        ->count();
                $this->prizeConf[$k]['v'] = $arr['v'] * 100;   //中奖权重 增加除不中奖以外的权重
            }
        }
        // 只取还有奖品和还有中奖率的选项
        foreach ($this->prizeConf as $k => $arr) {
            if (($arr['count'] < $arr['limit']) && ($arr['v'] > 0)) {
                $finalConf[] = $arr;
                $jingduSum += $arr['v'];
            }
        }
        //计算中奖率
        foreach ($finalConf as $k => $arr) {
            $finalConf[$k]['v100'] = round($arr['v'] * 100 / $jingduSum, 2) . '%';
        }
        //计算中奖
        foreach ($finalConf as $key => $prize) {
            $randNum = mt_rand(1, $jingduSum);
            if ($randNum <= $prize['v']) {
                $resultPrize = $prize;
                break;
            } else {
                $jingduSum -= $prize['v'];
            }
        }
        return ['resultPrize' => $resultPrize, 'finalConf' => $finalConf, 'conf' => $this->prizeConf];
    }
}
