<?php
include 'lanewechat.php';
$id = $_GET['oid'];
if(!empty($id)){
	$code = $_GET['code'];
	if(!empty($code)){
		$wechat_info = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
		$openid = $wechat_info['openid'];
		$_SESSION['openid'] = $openid;
		setcookie("openid", $openid, time()+3600);
		$sql = "select oid,name,contents from `lthn_wechat_oauth` where oid='$id' limit 0,1";
		$oauth = \LaneWeChat\Core\DB::get_one($sql);
		if($oauth){
			$oid = $oauth['oid'];
			$name = $oauth['name'];
			$contents = $oauth['contents'];
			header("Location:".$contents); 
		}else{
			echo "Oauth Error";
		}
	}else{
		$redirect_uri = WECHAT_URL."oauth.php?oid=".$id;
		\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_base');
	}
}
?>