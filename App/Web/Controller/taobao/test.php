<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

//     $c = new TopClient;
//    	$c->appkey = '23321495';
// 	$c->secretKey = '5eeaf5598d6d786b1c20b7af1f117c43';
//     $req = new AlibabaAliqinFlowWalletGradeRequest;
//     $req->setPhoneNum("18701945568");
//     $resp = $c->execute($req, '6100d21ef32000c381f966876c17619569ea8c735730fcf2594758389');
   	$out_recharge_id = "llhl_".time().rand(1000,9999);//流水号
   	$map['order_id'] = $_SESSION['order_id'];
   	$order = M('Order')->where($map)->find();
   	if (!empty($order)){
	   	$data['phone'] = $order['phone'];
	   	$data['order_id'] = $order['order_id'];
	   	$data['create_time'] = date("Y-m-d H:i:s",time());
	   	$data['status'] = 0;
	   	$data['out_recharge_id'] = $out_recharge_id;
	   	print_r(M('Recharge')->add($data));die;
	    $c = new TopClient;
		$c->appkey = '23321495';
		$c->secretKey = '5eeaf5598d6d786b1c20b7af1f117c43';
		$req = new AlibabaAliqinFlowWalletChargeRequest;
		$req->setPhoneNum($order['phone']);
		$req->setReason("充值");
		$req->setGradeId($order['package_code']);
		$req->setOutRechargeId($out_recharge_id);
		$req->setChannelId("0000061_GJBV_0B91E2CE2CBFA042");
		$resp = $c->execute($req, '6100d21ef32000c381f966876c17619569ea8c735730fcf2594758389');
		$error = explode(",",explode("=", $resp->charge)[1])[0];//提交状态
		if ($error == 'false'){
			$data['phone'] = $order['phone'];
			$data['order_id'] = $order['order_id'];
			$data['create_time'] = date("Y-m-d H:i:s",time());
			$data['status'] = 0;
			$data['out_recharge_id'] = $out_recharge_id;
			M('Recharge')->add($data);
			print_r("提交成功");die;
		}else{
			print_r("提交失败");die;
		}
   	}
?>