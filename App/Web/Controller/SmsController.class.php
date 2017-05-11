<?php
namespace Web\Controller;
use Think\Controller;

class SmsController extends CommonController{

	public function sendverifsms()
	{
		//判断当前用户的手机号码是否已经验证
		$user_id = $_SESSION['user_id'];
		$verifed = D("fans")->field('phone_verifed')->find($user_id);
		if($verifed['phone_verifed']){
			die("您的手机已验证，请勿操作");
		}

		$SignName = I('get.SignName');
		if(empty($SignName)){
			$SignName='身份验证';
		}
		$RecNum = I('get.RecNum');
		if(empty($RecNum) || strlen($RecNum)!=11){
			die("手机号码错误");
		}

		//同一人手机号码，一天内只允许发送3次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and phone='$RecNum'")->count();
		if($phone_sendnum>=3){
			die('此号码请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		//同一个用户，一天内只允许发送十次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and user_id='$user_id'")->count();
		if($phone_sendnum>=10){
			die('您请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		$vcode = $this->get_mobile_code();
		//生成随机验证码并更新到会员表
		$data = array();
		$data['id'] = $user_id;
		$data['user_phone'] = $RecNum;
		$data['verif_code'] = $vcode;
		D("fans")->save($data);

		$appkey = "23307774";
		$secret = "55a24d111bee77cc340809eaea208d4d";
		import('Org.Taobao.top.TopClient');
		import('Org.Taobao.top.ResultSet');
		import('Org.Taobao.top.RequestCheckUtil');
		import('Org.Taobao.top.TopLogger');
		import('Org.Taobao.top.request.AlibabaAliqinFcSmsNumSendRequest');
		$c = new \TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("");
		$req->setSmsType("normal");
		/*
		进入阿里大鱼的管理中心找到短信签名管理，输入已存在签名的名称，这里是身份验证。
		*/
		$req->setSmsFreeSignName($SignName);
		$req->setSmsParam("{'code':'". $vcode ."','product':'【乐透河南微信公众平台】'}"); 
		//这里设定的是发送的短信内容：验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”
		$req->setRecNum($RecNum);
		$req->setSmsTemplateCode("SMS_5070683");
		$resp = $c->execute($req);
		$resp = json_encode($resp);
		//写入短信日志
		$data = array();
		if(strpos($resp, "result")){
			$data['status'] = "成功";
		}else{
			$data['status'] = "失败";
		}
		$data['addtime'] = date("Y-m-d H:i:s");
		$data['phone'] = $RecNum;
		$data['content'] = $vcode;
		$data['message'] = $resp;
		$data['ip'] = get_client_ip();
		$data['type'] = $SignName;
		$data['user_id'] = $user_id;
		D("sms_log")->add($data);
		
		die('验证码已发送，请注意查收');
	}

	//微信公众号请求验证码
	public function wechatsendverifsms()
	{
		//判断当前用户的手机号码是否已经验证
		$user_id = I('get.user_id');
		if(empty($user_id)){
			$user_id = $_SESSION['user_id'];
		}
		$verifed = D("fans")->field('phone_verifed')->find($user_id);
		if($verifed['phone_verifed']){
			die("您的手机已验证，请勿操作");
		}

		$SignName = I('get.SignName');
		if(empty($SignName)){
			$SignName='身份验证';
		}
		$RecNum = I('get.RecNum');
		if(empty($RecNum) || strlen($RecNum)!=11){
			die("手机号码错误");
		}

		//同一人手机号码，一天内只允许发送3次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and phone='$RecNum'")->count();
		if($phone_sendnum>=3){
			die('此号码请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		//同一个用户，一天内只允许发送十次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and user_id='$user_id'")->count();
		if($phone_sendnum>=10){
			die('您请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		$vcode = $this->get_mobile_code();
		//生成随机验证码并更新到会员表
		$data = array();
		$data['id'] = $user_id;
		$data['user_phone'] = $RecNum;
		$data['verif_code'] = $vcode;
		D("fans")->save($data);

		$appkey = "23307774";
		$secret = "55a24d111bee77cc340809eaea208d4d";
		import('Org.Taobao.top.TopClient');
		import('Org.Taobao.top.ResultSet');
		import('Org.Taobao.top.RequestCheckUtil');
		import('Org.Taobao.top.TopLogger');
		import('Org.Taobao.top.request.AlibabaAliqinFcSmsNumSendRequest');
		$c = new \TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("");
		$req->setSmsType("normal");
		/*
		进入阿里大鱼的管理中心找到短信签名管理，输入已存在签名的名称，这里是身份验证。
		*/
		$req->setSmsFreeSignName($SignName);
		$req->setSmsParam("{'code':'". $vcode ."','product':'【乐透河南微信公众平台】'}"); 
		//这里设定的是发送的短信内容：验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”
		$req->setRecNum($RecNum);
		$req->setSmsTemplateCode("SMS_5070683");
		$resp = $c->execute($req);
		$resp = json_encode($resp);
		//写入短信日志
		$data = array();
		if(strpos($resp, "result")){
			$data['status'] = "成功";
		}else{
			$data['status'] = "失败";
		}
		$data['addtime'] = date("Y-m-d H:i:s");
		$data['phone'] = $RecNum;
		$data['content'] = $vcode;
		$data['message'] = $resp;
		$data['ip'] = get_client_ip();
		$data['type'] = $SignName;
		$data['user_id'] = $user_id;
		D("sms_log")->add($data);
		
		die('验证码已发送，请注意查收');
	}



	//登陆验证
	public function loginverifsms()
	{
		//判断当前用户的手机号码是否已经验证
		$user_id = $_SESSION['user_id'];

		$SignName = I('get.SignName');
		if(empty($SignName)){
			$SignName='身份验证';
		}
		$RecNum = I('get.RecNum');
		if(empty($RecNum) || strlen($RecNum)!=11){
			die("手机号码错误");
		}

		//查询当前手机号码是否存在记录
		$phone_sendnum = D("fans")->where("user_phone='$RecNum'")->find();
		if(empty($phone_sendnum)){
			die('此号码未绑定，请检查后重试');
		}else{
			$user_id = $phone_sendnum['id'];
		}

		//同一人手机号码，一天内只允许发送3次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and phone='$RecNum'")->count();
		if($phone_sendnum>=3){
			die('此号码请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		//同一个用户，一天内只允许发送十次
		$phone_sendnum = D("sms_log")->where("DATE_FORMAT(NOW(),'%Y-%m-%d') = DATE_FORMAT(addtime,'%Y-%m-%d') and user_id='$user_id'")->count();
		if($phone_sendnum>=10){
			die('您请求验证码次数过于频繁，请更换手机号码或明日重试。');
		}

		$vcode = $this->get_mobile_code();
		//生成随机验证码并更新到会员表
		$data = array();
		$data['id'] = $user_id;
		$data['user_phone'] = $RecNum;
		$data['verif_code'] = $vcode;
		D("fans")->save($data);

		$appkey = "23307774";
		$secret = "55a24d111bee77cc340809eaea208d4d";
		import('Org.Taobao.top.TopClient');
		import('Org.Taobao.top.ResultSet');
		import('Org.Taobao.top.RequestCheckUtil');
		import('Org.Taobao.top.TopLogger');
		import('Org.Taobao.top.request.AlibabaAliqinFcSmsNumSendRequest');
		$c = new \TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("");
		$req->setSmsType("normal");
		/*
		进入阿里大鱼的管理中心找到短信签名管理，输入已存在签名的名称，这里是身份验证。
		*/
		$req->setSmsFreeSignName($SignName);
		$req->setSmsParam("{'code':'". $vcode ."','product':'【乐透河南微信公众平台】'}"); 
		//这里设定的是发送的短信内容：验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”
		$req->setRecNum($RecNum);
		$req->setSmsTemplateCode("SMS_5070683");
		$resp = $c->execute($req);
		$resp = json_encode($resp);
		//写入短信日志
		$data = array();
		if(strpos($resp, "result")){
			$data['status'] = "成功";
		}else{
			$data['status'] = "失败";
		}
		$data['addtime'] = date("Y-m-d H:i:s");
		$data['phone'] = $RecNum;
		$data['content'] = $vcode;
		$data['message'] = $resp;
		$data['ip'] = get_client_ip();
		$data['type'] = $SignName;
		$data['user_id'] = $user_id;
		D("sms_log")->add($data);
		
		die('验证码已发送，请注意查收');
	}

	/* 手机验证码生成函数*/
	public function get_mobile_code()
	{
		$forbidden_num = "1989:10086:12590:1259:10010:10001:10000:";
		do
		{
			$mobile_code = substr(microtime(), 2, 6);
		}
		while (preg_match($mobile_code.':', $forbidden_num));
		return $mobile_code;
	}

	public function config(){
		$model = D("sms_config");
		$config = $model->find('1');
		$this->assign('config',$config);
		$this->assign('id',$config['id']);
		$this->display();
	}

}