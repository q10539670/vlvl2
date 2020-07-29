<?php

namespace App\Http\Controllers\Common;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserAuthorize extends Controller
{
    public function share(Request $request)
    {
        //获取参数
        $url = urldecode($request->input('url'));
        $appName = $request->input('appname') != '' ? $request->input('appname'): '33wh';
        //公众号的appid、secret
        $wxInfo = config('wxconfig');
        $appId = $wxInfo[$appName]['appId'];
        $appName = $wxInfo[$appName]['name'];
        $jsapiTicket = self::getJsApiTicket($appName);
        $timestamp = time();
        $nonceStr = self::createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string,
        );
        return Helper::Json(1, 'success', ['signPackage' => $signPackage]);
    }

    public function auth(Request $request)
    {
        $expire = time() + 60 * 60 * 24 * 7;
        $appName = $request->input('appname') != '' ? $request->input('appname'): '33wh';
        $mode = $request->input('auth');//判断授权方式 --wx:静默授权 wxauth:获取用户信息授权
        $scope = $mode == 'wx' ? 'snsapi_base' : 'snsapi_userinfo';
        if (!$openid = Cookie::get($appName)) {
            $wxInfo = config('wxconfig');
            $appId = $wxInfo[$appName]['appId'];
            $appSecret = $wxInfo[$appName]['appSecret'];
            $sendUrl = route('auth.auth');
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".urlencode($sendUrl)."&response_type=code&scope=".$scope."#wechat_redirect";
            $client = new \GuzzleHttp\Client();
            $client->get($url);
            $code = $request->input('code');
            $table = 'user_'.$appName;
            $params = http_build_query([
                'appid' => $appId,
                'secret' => $appSecret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ]);
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?'.$params;
            $accessTokenArr = json_decode(file_get_contents($url), true);
            if (!isset($accessTokenArr['errcode'])) {
                $openid = $accessTokenArr["openid"];
                Cookie::set(['key' => 'access_token', 'value' => $accessTokenArr["access_token"], 'expire' => $expire]);
                $sql = DB::table($table)->where('openid', $openid)->first();
                if ($scope == "snsapi_userinfo") {
                    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$accessTokenArr["access_token"]."&openid=".$openid."&lang=zh_CN";
                    $userInfo = json_decode(file_get_contents($url), true);
                    unset($userInfo["privilege"]);
                    if (!$sql) {
                        $userInfo["addTime"] = time();
                        $userInfo['items'] = 0;
                        $userInfo['nickname'] = self::filterEmoji($userInfo['nickname']);
                        $userInfo['nickname2'] = base64_encode($userInfo['nickname']);
                        $uid = DB::table($table)->insertGetId((array) $userInfo);
                        Cookie::set(['key' => 'uid', 'value' => $uid, 'expire' => $expire]);
                    } else {
                        $userInfo['nickname'] = self::filterEmoji($userInfo['nickname']);
                        $userInfo["updateTime"] = time();
                        DB::table($table)->where('openid', $openid)->update((array) $userInfo);
                        Cookie::set(['key' => 'uid', 'value' => $sql->id, 'expire' => $expire]);
                    }
                } else {
                    if (!$sql) {
                        DB::table($table)->insert([
                            "openid" => $openid,
                            "items" => 0,
                            "addTime" => time()
                        ]);
                    }
                }
                Cookie::set(['key' => $appName, 'value' => $openid, 'expire' => $expire]);
                /*
                 * 更新项目名称
                 */
                $item = $this->getItemName();
                $items = DB::table($table)->where('openid', $openid)->get('items');
                $itemsArr = explode(",", $items[0]->items);

                if (!in_array($item, $itemsArr)) {
                    array_push($itemsArr, $item);
                    $items = implode(',', $itemsArr);
                    DB::table($table)->where('openid', $openid)->update(['items' => $items]);
                }
            } else {
                return Helper::Json(-1, 'error', ['errcode' => $accessTokenArr['errcode']]);
            }
        }
        return Helper::Json(1, 'success', ['openid' => $openid,'$cope'=>$scope]);
    }

    /**
     * 获取ppId
     * @param  Request  $request
     * @return mixed
     */
    public function appId(Request $request)
    {
        $wxInfo = config('wxconfig');
        $appName = $request->input('appname') != '' ? $request->input('appname'): '33wh';
        $appId = $wxInfo[$appName]['appId'];
        return Helper::Json(1, 'success', ['appname' => $appName, 'appid' => $appId]);
    }

    /**
     * 获取code
     * @param  string  $wxName
     * @param $scope
     */
    public static function getCode($wxName, $scope)
    {
        $wxInfo = config('wxconfig');
        $appId = $wxInfo[$wxName]['appId'];
        $sendUrl = route('auth.auth');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".urlencode($sendUrl)."&response_type=code&scope=".$scope."#wechat_redirect";
        $client = new \GuzzleHttp\Client();
        $client->get($url);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function filterEmoji($str)
    {
        $str = preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $str);
        return $str;
    }

    /**
     * 计算项目名称
     * @return mixed|string
     */
    protected function getItemName()
    {
        $urlStr = preg_replace("/(http|https):\/\//", '', $_SERVER['HTTP_REFERER']);
        return explode('/', $urlStr)[2];
    }


    //直接获取 js ticket
    public static function getJsApiTicket($appName)
    {
        $redis = app('redis');
        $redis->select(11);
        $redisBaseKey = 'wx:'.$appName.':jsapi_ticket';
        $data = json_decode($redis->get($redisBaseKey), true);

        return $data['ticket'];
    }

    //直接获取access_token
    public static function getAccessToken($appName)
    {
        $redis = app('redis');
        $redis->select(11);
        $redisBaseKey = 'wx:'.$appName.':access_token';
        $data = $redis->get($redisBaseKey);
        return $data['access_token'];
    }

    // 创建随机数
    public static function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}