<?php
include 'lanewechat.php';

/*
一、如果当前用户的二维码不存在，则进行请求操作
二、如果记录为空则请求当前用户二维码并保存到本地和更新到数据库
*/
session_start();
$openid = $_SESSION['openid'];
if(!empty($openid)){
	if (!file_exists("./qrcode/$openid.jpg")){

		$sql = "select uid,aff_id from `cenwor_system_members` where openid='$openid'";
		$usernow = \LaneWeChat\Core\DB::get_one($sql);
		if($usernow){
			$uid = $usernow['uid'];
			if (!file_exists("./qrcode")){
				mkdir ("./qrcode");
			}
			$ticket = LaneWeChat\Core\Popularize::createTicket(2, 1800, $uid);
			$ticket = $ticket['ticket'];
			$qrcode = LaneWeChat\Core\Popularize::getQrcode($ticket);
			file_put_contents("qrcode/$openid.jpg", $qrcode);
		}
	}
	$qrcodeimg = "qrcode/$openid.jpg";
}else{
	$qrcodeimg = "";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>我的二维码</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="qrcode.css" rel='stylesheet' type='text/css' />
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="app-location">
		<h2>快扫我吧</h2>
		<div class="line"><span></span></div>
		<div class="location">
			<img src="<?php echo $qrcodeimg; ?>">
		</div>
	</div>
</body>
</html>