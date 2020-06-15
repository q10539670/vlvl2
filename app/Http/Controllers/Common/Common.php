<?php


namespace App\Http\Controllers\Common;

use App\Helpers\Helper;
use App\Models\Jctj\JctjUsers as Jctj;
use App\Models\Qwt\QwtUsers as Qwt;
use App\Models\Sswh\SswhUsers as Sswh;
use App\Models\Jyyc\JyycUsers as Jyyc;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\BaseV1Controller as Controller;

class Common extends Controller
{

    /**
     * 查询三山文化用户
     * @param Request $request
     * @return
     */
    public function searchSswhUser(Request $request)
    {
        $user = Sswh::select('nickname', 'headimgurl')
            ->where('openid', $request->openid)
            ->first();
        return $user;
    }

    /**
     * 查询全网通文化用户
     * @param Request $request
     * @return array
     */
    public function searchQwtUser(Request $request)
    {
        $user = Qwt::select('nickname', 'headimgurl')
            ->where('openid', $request->openid)
            ->first();
        return $user;
    }

    /**
     * 查询江宸天街文化用户
     * @param Request $request
     * @return
     */
    public function searchJctjUser(Request $request)
    {
        $user = Jctj::select('nickname', 'headimgurl')
            ->where('openid', $request->openid)
            ->first();
        return $user;
    }

    /**
     * 查询江宸天街文化用户
     * @param Request $request
     * @return
     */
    public function searchJyycUser(Request $request)
    {
        $user = Jyyc::select('nickname', 'headimgurl')
            ->where('openid', $request->openid)
            ->first();
        return $user;
    }

    /**
     * 拼接用户信息
     * @param Request $request
     * @param $user
     * @param array $field
     * @return array
     */
    public function userInfo(Request $request, $user, $field = [])
    {
        $userInfo = [
            'openid' => $request->openid,
            'nickname' => $user['nickname']??'',
            'avatar' => $user['headimgurl']??'',
        ];
        return $userInfo + $field;
    }

    /**
     * 获取目标ID
     * @param $url
     * @return int
     */
    public function getTargetUserId($url)
    {

        $paramStr = substr(strstr($url, '?'), 1);
        if ($paramStr) {
            $paramArr = explode('&', $paramStr);
            foreach ($paramArr as $param) {
                $params = explode('=', $param);
                if ($params['0'] == 'target_user_id') {
                    $targetUserId = $params['1'];
                } else {
                    $targetUserId = 0;
                }
            }
        } else {
            $targetUserId = 0;
        }
        return $targetUserId;
    }

    /**
     * 获取今日时间
     */
    public function getToday()
    {
        $todayStart = date('Y-m-d') . ' 00:00:00';
        $todayend = date('Y-m-d') . ' 23:59:59';
        return [$todayStart, $todayend];
    }

    /**
     * 判断是否过期
     * @param $time
     * @return bool
     */
    public function isTimeout($time)
    {
        return time() > strtotime($time);
    }

    /**
     * 隐藏手机号码
     * @param $lists
     * @param $hideName
     * @return array
     */
    public function hideTel($lists, $hideName)
    {
        foreach ($lists as $list) {
            $phone = $list->phone;
            $list[$hideName] = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
        }
        return $lists;
    }

    /**
     * 检测是否关注公众号
     * @param $user
     * @param $account
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function isSubscribe($user, $account)
    {
        if ($user->subscribe != 1 && Helper::stopResubmit($this->itemName . ':userInfo', $user->id, 1800)) {
            switch ($account) {
                case 'Sswh':
                    $token = Helper::getSswhAccessToken();
                    break;
                case 'Qwt':
                    $token = Helper::getQwtAccessToken();
                    break;
                case 'Jctj':
                    $token = Helper::getJctjAccessToken();
                    break;
                default:
                    $token = Helper::getSswhAccessToken();
            }
//            $token = Helper::getQwtAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $user->openid . '&lang=zh_CN';
            $client = new \GuzzleHttp\Client();
            $resClient = $client->request('GET', $url);
            $result = json_decode($resClient->getBody()->getContents(), true);
            if (isset($result['subscribe']) && $result['subscribe'] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

}
