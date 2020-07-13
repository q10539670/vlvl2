<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class Helper
{
    /*
     * 测试
     * */
    public static function test()
    {
        return 55556665;
    }

    /*
     * 缓存数据
     * */
    public static function redisCacheStore($cacheKey, \Closure $getDataFunc, $expireSecond = 5)
    {
        $redis = app('redis');
        $redis->select(12);
        $cacheKeyExpireAt = $cacheKey . 'ExpireAt';
        $nowTime = now()->timestamp;
        /*不存在或者过期*/
        if ((!$redis->exists($cacheKeyExpireAt)) || $redis->get($cacheKeyExpireAt) < $nowTime) {
            $cacheData = $getDataFunc();
            $redis->set($cacheKey, json_encode($cacheData, JSON_UNESCAPED_UNICODE));
            $redis->set($cacheKeyExpireAt, time() + $expireSecond);
            $result = $cacheData;
        } else {
            $result = json_decode($redis->get($cacheKey), true);
        }
        return $result;
    }

    public static function stopResubmit($itemName, $userId, $limitSecond = 5)
    {
        $redis = app('redis');
        $redis->select(12);
        $rKey = 'wx:' . $itemName . ':stopResubmit:' . $userId;
        $rVal = $redis->incr($rKey);
        if ($rVal > 1) {
            return false;
        }
        $redis->setex($rKey, $limitSecond, $rVal);
        return true;
    }

    /**
     * 保存微信头像
     * @param $avatar //微信头像地址
     * @param $controllerName //前控制器名称
     * @return string   返回存储的路径
     */
    public static function generateAvatar($avatar, $controllerName)
    {
        $client = new Client();
        $res = $client->request('GET', $avatar);
        $avatarPath = 'upload/items/' . $controllerName . '/avatar/' . md5(date('YmdHis') . str_random(8)) . '.png';
        $storage = Storage::disk('public');
        $storage->put($avatarPath, $res->getBody());
        return $avatarPath;
    }

    /**
     * 封装常规返回方法
     * @param int $code
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function Json($code = 1, $message = '', $data = [], $type = false)
    {
        if ($type) {
            return json_encode(['code' => $code, 'message' => $message, 'data' => $data], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    /**
     * 递归创建文件夹
     * @param $dir
     * @return bool
     */
    public static function makeDirectory($dir)
    {
        return is_dir($dir) or self::makeDirectory(dirname($dir)) and mkdir($dir, 0777);
    }

    /**
     *  获取三山文化 access_token
     * */
    public static function getSswhAccessToken()
    {
//        Redis::connection()->select('11');
//        return json_decode(Redis::get('wx:sanshan:access_token'),true)['access_token'];
        $redis = env('APP_ENV') == 'local' ? Redis::connection('sswh') : Redis::connection();
        $redis->select('11');
        return json_decode($redis->get('wx:sanshan:access_token'), true)['access_token'];
    }

    /**
     *  获取全网通文化 access_token
     * */
    public static function getQwtAccessToken()
    {
        $redis = env('APP_ENV') == 'local' ? Redis::connection('sswh') : Redis::connection('default');
        $redis->select('11');
        return json_decode($redis->get('wx:qwt:access_token'), true)['access_token'];
    }

    /**
     *  获取幸福湾 access_token
     * */
    public static function getXfwAccessToken()
    {
        $redis = env('APP_ENV') == 'local' ? Redis::connection('sswh') : Redis::connection('default');
        $redis->select('11');
        return json_decode($redis->get('wx:xingfuwan:access_token'), true)['access_token'];
    }

    /**
     * 获取江宸天街 access_token
     */
    public static function getJctjAccessToken()
    {
        $redis = env('APP_ENV') == 'local' ? Redis::connection('sswh') : Redis::connection('default');
        $redis->select('11');
        return json_decode($redis->get('wx:whjctj:access_token'), true)['access_token'];
    }

    /**
     * 获取荆楚红牛 access_token
     */
    public static function getJchnAccessToken()
    {
        $redis = env('APP_ENV') == 'local' ? Redis::connection('sswh') : Redis::connection('default');
        $redis->select('11');
        return json_decode($redis->get('wx:jchnsj:access_token'), true)['access_token'];
    }

    /*
     * 获取当前类名 小写
     * */
    public static function getControllerName($class)
    {
        $arr = explode('\\', $class);
        return strtolower(str_replace('Controller', '', $arr[count($arr) - 1]));
    }

    /*获取分享数据*/
    public static function getRedisShareData($itemName)
    {
        Redis::connection()->select(0);
        $res = Redis::hgetall('wx:view:' . $itemName);
        if (!isset($res['tl'])) $res['tl'] = 0;
        if (!isset($res['firend'])) $res['firend'] = 0;
        return $res;
    }

    /*
     * 导出excel 过滤微信昵称
     * */
    public static function filterEmoji($emojiStr)
    {
        $emojiStr = preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $emojiStr);
        return '`' . $emojiStr;    //昵称前面加半角符号 防止 EXCEL报错
    }

}
