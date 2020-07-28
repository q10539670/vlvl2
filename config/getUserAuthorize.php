<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/z/33wh/common.config.php';
include CONFIG_ROOT.'/comm.funs.php';
include CONFIG_ROOT.'/mysqli.php';


$db = new MySqliByFwx();
// $db->connect($DbConfig["host"],$DbConfig["dbUser"],$DbConfig["dbPwd"],$DbConfig["dbName"]);

$scope = $_POST["scope"];
$sendUrl = $_POST["sendUrl"];
$code = $_POST["code"];
$sessionName = setSession($sendUrl);

$arr = array();

$table = 'user_33wh';
$systemConfig = require_once $_SERVER['DOCUMENT_ROOT'].'/configs/system_config.php';

// $redis = new Redis();
// //选择指定的redis数据库连接，默认端口号为6379
// $redis->connect('127.0.0.1', 6379);
// $redis->select(0);
if(!isset($_SESSION[$sessionName])){	

	if($code == 0){
		if($systemConfig['master']){   //主服务器
			$arr["url"] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$C["AppId"]."&redirect_uri=".urlencode($sendUrl)."&response_type=code&scope=".$scope."#wechat_redirect";
		}else{   //从服务器
			$itemName = explode('/', $sendUrl);
			$sendUrl1 = 'http://wx.sanshanwenhua.com/linux.php';
			$arr["url"] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$C["AppId"]."&redirect_uri=".urlencode($sendUrl1)."&state=".$itemName[4].'/'.$itemName[5]."&response_type=code&scope=".$scope."#wechat_redirect";
		}
		$arr["state"] = 0;
	}else{
		// $arr["s"] = $_SERVER;
		$arr["s2"] = getSession();
		
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$C["AppId"]."&secret=".$C["AppSecret"]."&code=".$code."&grant_type=authorization_code";
		$getAccessTokenArr = json_decode(httpGet($url),true);
		
		// $redis->set('access_token',$getAccessTokenArr["access_token"],7000);
		// $redis->set('refresh_token',$getAccessTokenArr["refresh_token"]);
		
		if(!isset($getAccessTokenArr["errcode"])){
			$openid = $getAccessTokenArr["openid"];
			$arr["state"] = 1;
			if($scope == "snsapi_userinfo"){
				$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$getAccessTokenArr["access_token"]."&openid=".$openid."&lang=zh_CN";
				$userInfo = json_decode(httpGet($url),true);

				/*
				*	更新用户数据
				*/

				unset($userInfo["privilege"]);

				$sql = "select id from ".$table." where openid = '{$openid}' limit 1";
				if(!$db->getRowsNum($sql)){
					$userInfo["addTime"] = time();
					$userInfo['items'] = 0;
					$userInfo['nickname'] = filterEmoji($userInfo['nickname']);
					$userInfo['nickname2'] = base64_encode($userInfo['nickname']);
					$uid = $db->save($table,$userInfo);					
					$_SESSION['uid'] = $uid;
				}else{
					$userInfo['nickname'] = filterEmoji($userInfo['nickname']);
					$userInfo["updateTime"] = time();
					$db->update($table,$userInfo,"openid = '{$openid}'");					
					$userArr = $db->find($sql);
					$_SESSION['uid'] = $userArr['id'];
				}
			}else{
				$sql = "select id from ".$table." where openid = '{$openid}' limit 1";
				if($db->getRowsNum($sql) == 0){
					$db->save($table,array(
						"openid"=>$openid,
						"items"=>0,
						"addTime"=>time()
					));			
				}
			}
			$_SESSION[$sessionName] = $openid;

			/*
			*	更新项目名称
			*/
			$item = getSession();
			$sql = "select items from ".$table." where openid = '{$openid}' limit 1";
			$itemsArr = explode(",", $db->find($sql)["items"]);
			if(!in_array($item, $itemsArr)){
				$sql = "update ".$table." set items = CONCAT(items,',".$item."') where openid = '{$openid}'";
				$db->query($sql);				
			}
		}else{
			$arr["state"] = -1;
		}
	}
}else{
	$arr["state"] = 1;
}
echo json_encode($arr);
exit();
?>