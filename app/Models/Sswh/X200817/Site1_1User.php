<?php


namespace App\Models\Sswh\X200817;

use Illuminate\Database\Eloquent\Model;

class Site1_1User extends Model
{
    protected $table = 'x200817_site1-1_user';

    protected $guarded = [];

    const ITEM = 'x200817_site1';

    /*
     * 获取当前抽奖轮数
     */
    public static function getRound()
    {
        $redis = app('redis');
        $redis->select(12);
        $redisKey = 'wx:'.self::ITEM.':round';
        return $redis->get($redisKey);
    }

    /*
     * 设置抽奖轮数
     */
    public static function setRound($round)
    {
        $redis = app('redis');
        $redis->select(12);
        $redisKey = 'wx:'.self::ITEM.':round';
        if ($round < 0 && $round > 5) {
            $round = 0;
        }
        return $redis->set($redisKey, $round);
    }

    /*
     * 格式化电话号码
     */
    public static function formatPhone($phone)
    {
        return str_replace(substr($phone, 0, 7), '*******', $phone);
    }

    public static function getFormatUser($users)
    {
        foreach ($users as $key =>$user) {
            $users[$key]['hide_phone'] = self::formatPhone($user['phone']);
        }
        return $users;
    }
}
