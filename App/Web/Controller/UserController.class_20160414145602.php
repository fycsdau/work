<?php
namespace Web\Controller;
use Think\Controller;

class UserController extends CommonController {

	public function _initialize() {
		parent::_initialize();
		$this->dbname = "users";
		$get_user_id = I('get.user_id','user_id');

// 		//如果不是通过推荐过来的页面进入，则判断是否登录
// 		$get_user_id = I('get.user_id');
// 		if(empty($get_user_id)){
// 			$user_id = $_SESSION['user_id'];
// 			if(empty($user_id)){
// 				echo "<script>window.location.href='/index.php/Public/login';</script>";
// 				die();
// 			}
// 		}
		
	}

	public function qrcode(){
		$this->assign('page_title', '我的二维码');
		$get_user_id = I('get.user_id');
		if(!empty($get_user_id)){
			$user_id = $get_user_id;
		}else{
			$user_id = $_SESSION['user_id'];
		}

		$users = M('users')->where("user_id='$user_id'")->find();
		$qrcode = $users['qrcode'];
		$nickname = $users['nickname'];
		if(empty($nickname)){
			$nickname = '流量互联';
		}
		$headimgurl = $users['headimgurl'];
		if(empty($qrcode)){
			require_once(THINK_PATH.'../WeChat/lanewechat.php');
			$WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
			$scene_id = $user_id;
			// 拉取官方二维码
			
			$ticket = \LaneWeChat\Core\Popularize::createTicket(2, 1800, $scene_id);

			if(isset($ticket['errcode'])){
				$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
				$ticket = \LaneWeChat\Core\Popularize::createTicket(2, 1800, $scene_id);
			}

			$ticket = $ticket['ticket'];
			$qrcode = \LaneWeChat\Core\Popularize::getQrcode($ticket);

			if(isset($qrcode['errcode'])){
				$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
				$qrcode = \LaneWeChat\Core\Popularize::getQrcode($ticket);
			}

			$time = time(); 
			$ip = GetHostByName($_SERVER['SERVER_NAME']);
			$ipr = str_replace('.', '', $ip);
			$rand_name = $time . sprintf("%03d", mt_rand(1,999));
			$img_name = 'qrcode_'. $scene_id .'_'. $ipr . $rand_name;
			$dir = 'images';
			$sub_dir = date('Ym', $time);
			$img_ext = '.jpg';

			if (!is_dir(THINK_PATH.'../WeChat/'.$dir.'/qrcode_img/'.$sub_dir)){
				if (!mkdir(THINK_PATH.'../WeChat/'.$dir.'/qrcode_img/'.$sub_dir,0777,true)){
					echo "目录创建失败";
				}
			}
			$path = THINK_PATH.'../WeChat/'.$dir.'/qrcode_img/'.$sub_dir.'/'.$img_name.$img_ext;

			$local_file=fopen($path,'a');
			if(false !==$local_file){
				if(false !==fwrite($local_file,$qrcode)){
					fclose($local_file);

					//$key = $WECHAT_URL.$dir.'/qrcode_img/'.$sub_dir.'/'.$img_name.$img_ext;
					//$surl = $key;

					$key = 'QRCODE/'.$sub_dir.'/'.$img_name.$img_ext;
					$surl = "http://oss.llian.com.cn/".$key;

					require_once(THINK_PATH.'../aliyun_oss.php');
					aliossput($key, $path);

					//将生成的二维码图片的地址放到数据库中
					$users['qrcode'] = $surl;
					$ssurl = addslashes($surl);
					$data['qrcode'] = $ssurl;
					$User = M("Users");
					$User->where("user_id='$user_id'")->save($data);
				}
			}else{
				echo "文件写入失败";
			}

		}
		$this->assign('qrcode',$users['qrcode']);
		$this->assign('nickname',$users['nickname']);
		$this->assign('headimgurl',$users['headimgurl']);
		$this->display();
	}

	//会员中心
	public function member(){
		$this->assign('page_title',"我的资料");
		$user_id = $_SESSION['user_id'];
		$userinfo = D("users")->where("user_id='$user_id'")->find();

		//如果用户信息不完整，则进行信息更新
		if(empty($userinfo['nickname']) || empty($userinfo['headimgurl'])){
			require_once(THINK_PATH.'../WeChat/lanewechat.php');
			$openid = $userinfo['openid'];
			$user_infos = \LaneWeChat\Core\UserManage::getUserInfo($openid);

			if(isset($user_infos['errcode'])){
				$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
				$user_infos = \LaneWeChat\Core\UserManage::getUserInfo($openid);
			}

			$data['nickname'] = $user_infos["nickname"];
			$data['sex'] = $user_infos["sex"];
			$data['language'] = $user_infos["language"];
			$data['city'] = $user_infos["city"];
			$data['province'] = $user_infos["province"];
			$data['country'] = $user_infos["country"];
			$data['headimgurl'] = $user_infos["headimgurl"];
			$data['subscribe_time'] = $user_infos["subscribe_time"];
			$data['unionid'] = $user_infos["unionid"];
			D("users")->where("user_id='$user_id'")->save($data);
			$userinfo = D("users")->where("user_id='$user_id'")->find();
		}

		$userinfo['user_money'] = sprintf("%.2f", $userinfo['user_money']);
		$this->assign('userinfo',$userinfo);
		$this->display();
	}

	//代言人推广
	public function spokes(){
		$this->assign('page_title',"代言人推广");
		$user_id = $_SESSION['user_id'];
		$userinfo = D("users")->find($user_id);
		$userinfo['user_money'] = sprintf("%.2f", $userinfo['user_money']);
		$this->assign('userinfo',$userinfo);
		//大伙伴
		$big_partner = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level1_children (".$user_id."))")->count();
		$big_partner_order = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level1_order_children (".$user_id."))")->count();
		$this->assign('big_partner',$big_partner);
		$this->assign('big_partner_order',$big_partner_order);

		//中伙伴
		$partner = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level2_children (".$user_id."))")->count();
		$partner_order = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level2_order_children (".$user_id."))")->count();
		$this->assign('partner', $partner);
		$this->assign('partner_order',$partner_order);

		//小伙伴
		$small_partner = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level3_children (".$user_id."))")->count();
		$small_partner_order = D("users")->field('user_id')->where("FIND_IN_SET(user_id, get_level3_order_children (".$user_id."))")->count();
		$this->assign('small_partner',$small_partner);
		$this->assign('small_partner_order',$small_partner_order);

		$this->display();
	}

	//伙伴列表
	public function friends(){
		$friends_type = I('get.type','bigpartner');
		$user_id = $_SESSION['user_id'];
		$userinfo = D("users")->find($user_id);

		//大伙伴
		if($friends_type=='bigpartner'){
			$this->assign('page_title',"我的大伙伴");
			$count = D("users")->where("FIND_IN_SET(user_id, get_level1_children (".$user_id."))")->count();// 查询满足要求的总记录数
			$pagecount = 10;
			$page = new \Think\Page($count , $pagecount);
			$page->setConfig('first','首页');
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$page->setConfig('last','尾页');
			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
			$show = $page->show();
			$partner = D("users")->where("FIND_IN_SET(user_id, get_level1_children (".$user_id."))")->limit($page->firstRow.','.$page->listRows)->select();

		}elseif ($friends_type=='partner') {
			$this->assign('page_title',"我的中伙伴");
			$count = D("users")->where("FIND_IN_SET(user_id, get_level2_children (".$user_id."))")->count();// 查询满足要求的总记录数
			$pagecount = 10;
			$page = new \Think\Page($count , $pagecount);
			$page->setConfig('first','首页');
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$page->setConfig('last','尾页');
			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
			$show = $page->show();
			$partner = D("users")->where("FIND_IN_SET(user_id, get_level2_children (".$user_id."))")->limit($page->firstRow.','.$page->listRows)->select();

		}elseif ($friends_type=='smallpartner') {
			$this->assign('page_title',"我的小伙伴");
			$count = D("users")->where("FIND_IN_SET(user_id, get_level3_children (".$user_id."))")->count();// 查询满足要求的总记录数
			$pagecount = 10;
			$page = new \Think\Page($count , $pagecount);
			$page->setConfig('first','首页');
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$page->setConfig('last','尾页');
			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
			$show = $page->show();
			$partner = D("users")->where("FIND_IN_SET(user_id, get_level3_children (".$user_id."))")->limit($page->firstRow.','.$page->listRows)->select();
		}
		$this->assign('partner', $partner);
		if(count($partner)<=0){
			$this->assign('nopartner', 1);
		}
		
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}

	//用户信息
	public function member_info(){
		$this->assign('page_title',"我的信息");

		if(IS_POST){
			//验证验证码
			$user_id = $_SESSION['user_id'];
			$userinfo = D("fans")->field("verif_code")->find($user_id);
			$data=I('post.');
			if($data['user_phone'] != $data['user_phone_old']){
				if($data['verif_code'] != $userinfo['verif_code']){
					echo "<script>alert('验证码输入不正确，请检查。'); history.go(-1);</script>";
					die();
				}
			}
			$model = D('users');
			$model->save($data);
			redirect('/index.php/user/', 0, '页面跳转中...');
			die();
		}

		$user_id = $_SESSION['user_id'];
		$userinfo = D("users")->find($user_id);
		$userinfo['user_idd'] = $userinfo['user_id'];
		$userinfo['user_id'] = str_pad($userinfo['user_id'],8,'0',STR_PAD_LEFT);

		$this->assign('userinfo',$userinfo);
		$this->display();
	}

	//用户信息修改
	public function member_infoedit(){
		$this->assign('page_title',"修改我的信息");
		$user_id = $_SESSION['user_id'];
		$userinfo = D("users")->find($user_id);
		$userinfo['user_idd'] = $userinfo['user_id'];
		$userinfo['user_id'] = str_pad($userinfo['user_id'],8,'0',STR_PAD_LEFT);
		$this->assign('userinfo',$userinfo);
		$this->display();
	}

	//我的订单
	public function orders(){
		$this->assign('page_title',"我的订单");

		$order_status = C('ORDER_STATUS');
		$user_id = $_SESSION['user_id'];
		$map['user_id'] = $user_id;
		$map['phone'] = array('neq','null');
		$map['phone'] = array('neq','');
		
		$count      = M("Order")->where($map)->count();// 查询满足要求的总记录数
		$pagecount = 4;
		$page = new \Think\Page($count , $pagecount);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
		$show = $page->show();

		$order = D("order")->where($map)->order("order_id desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		
		for ($i=0; $i < count($order); $i++) {
			$order_id = $order[$i]['order_id'];
			$order[$i]['statusname'] = $order_status[$order[$i]['status']];

			if($order[$i]['operate_name']=='中国联通'){
				$order[$i]['operate_class'] = 'user-icon-liantong brand-liantong';
			}elseif($order[$i]['operate_name']=='中国移动'){
				$order[$i]['operate_class'] = 'user-icon-chinamobile brand-yidong';
			}elseif($order[$i]['operate_name']=='中国电信'){
				$order[$i]['operate_class'] = 'user-icon-dianxin brand-dianxin';
			}
		}
		$this->assign('order', $order);
		$this->display();
		
		
	}

	//订单详情
	public function order_detail(){
		$this->assign('page_title',"订单详情");
		$user_id = $_SESSION['user_id'];

		$order_id = I('get.id',0);
		$this->assign('last_id', $order_id);

		if(empty($order_id)){
			die('参数错误');
		}

		$order_status = C('ORDER_STATUS');
		$pay_name = C('PAY_NAME');

		$map['order_id'] = $order_id;
		$order = D("order")->where($map)->find();
		$order_id = $order['order_id'];
		$order['statusname'] = $order_status[$order['status']];
		$order['pay_name'] = $pay_name[$order['pay_code']];
		$this->assign('order', $order);

		if($order['status']==1){
			//帐户余额
			$user_money = D("users")->where("user_id='$user_id'")->getField('user_money');
			$user_money = sprintf("%.2f", $user_money);

			//微信支付
			if(is_wechat_browser()){
				$this->assign('is_wechat', 1);

				$openId = M('users')->where("user_id='$user_id'")->getField('openid');
				$WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');

				vendor('Pay.JSAPI');

				$tools = new \JsApiPay();
				$phone = $order['phone'];
				//$Out_trade_no = date('YHis').rand(100,1000);
				$Out_trade_no = $order['order_sn'];
				$Total_fee = $order['operate_name'] ." ".$order['package_name'];
				$Body = $phone .' 充值流量 '. $order['package_name'];
				$Total_fee = $order['order_money']*100;
				$input = new \WxPayUnifiedOrder();
				$input->SetBody($Body);
				$input->SetTime_start(date("YmdHis"));
				$input->SetTime_expire(date("YmdHis", time() + 6000));
				$input->SetOut_trade_no($Out_trade_no);
				$input->SetTotal_fee($Total_fee);
				$input->SetNotify_url($WECHAT_URL."../index.php/Index/wechat_pay_callback");
				$input->SetTrade_type("JSAPI");
				$input->SetOpenid($openId);
				$order = \WxPayApi::unifiedOrder($input);
				$this->jsApiParameters = $tools->GetJsApiParameters($order);
			}
		}
		$this->assign('user_money', $user_money);
		$this->display();
	}

	//银行卡管理
	public function cards(){
		$this->assign('page_title',"我的银行卡");
		$user_id = $_SESSION['user_id'];
		$bank = D("users_bank")->where("user_id='$user_id'")->select();
		for ($i=0; $i < count($bank); $i++) { 
			$bankcode = substr_replace($bank[$i]['bankcode'],'****',8,4);
			$bankcode = str_split($bankcode,4);
			$bankcode = implode(' ',$bankcode);
			$bank[$i]['bankcode'] = $bankcode;

			if($bank[$i]['bankname']=='中国建设银行'){
				$bank[$i]['bankico'] = 'user-icon-jiansheyinxing bank-logo-jianshe';
			}elseif($bank[$i]['bankname']=='中国银行'){
				$bank[$i]['bankico'] = 'user-icon-zhongguoyinxing bank-logo-zhongguo';
			}elseif($bank[$i]['bankname']=='中国农业银行'){
				$bank[$i]['bankico'] = 'user-icon-nongyeyinxing bank-logo-nongye';
			}elseif($bank[$i]['bankname']=='中国工商银行'){
				$bank[$i]['bankico'] = 'user-icon-gongshangyinxing bank-logo-gongshang';
			}
		}
		$this->assign('bank', $bank);
		$this->display();
	}

	//添加银行卡
	public function addcard(){
		$this->assign('page_title',"新增银行卡");
		if(IS_POST){
			$data=I('post.');
			$bankcode = $data['bankcode'];
			if(empty($data['bank_id'])){
				//判断当前卡号是否已经在数据库中
				$bank = D("users_bank")->where("bankcode='$bankcode'")->select();
				if(!empty($bank)){
					die('该银行卡号已存在，请检查后重新输入');
				}
				$user_id = $_SESSION['user_id'];
				$data['user_id'] = $user_id;
				if(D("users_bank")->add($data)){
					die('success');
				}else{
					die('添加成功!');
				}
			}else{
				$bank_id = $data['bank_id'];
				D("users_bank")->where("bank_id='$bank_id'")->save($data);
				die('success');
			}
		}
		$id = I('get.bank_id',0);
		if(!empty($id)){
			$user_id = $_SESSION['user_id'];
			$bank = D("users_bank")->where("bank_id='$id'")->find();

			if((int)$user_id !== (int)$bank['user_id']){
				die('Error');
			}
			$this->assign('bank',$bank);
			$this->assign('page_title',"修改银行卡信息");
			$this->assign('show_delete',1);
		}
		$this->display();
	}

	//删除银行卡
	public function delbank(){
		$id = I('get.bank_id',0);
		$user_id = $_SESSION['user_id'];
		$bank = D("users_bank")->where("bank_id='$id'")->find();

		if((int)$user_id !== (int)$bank['user_id']){
			die('Error');
		}

		if( D("users_bank")->where("bank_id='$id'")->delete() ){
			die('success');
		}else{
			die('删除失败');
		}
	}
	
	//账户变动记录
	public function account_log(){
		$this->assign('page_title',"账户变动记录");
		$user_id = $_SESSION['user_id'];
	
		$count      = M("users_account_log")->where("user_id='$user_id'")->count();// 查询满足要求的总记录数
		$pagecount = 10;
		$page = new \Think\Page($count , $pagecount);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ');
		$show = $page->show();
	
		$record = M("users_account_log")->where("user_id='$user_id'")->order("log_id desc")->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign('record',$record);
		$this->assign('page',$show);
		$this->display();
	}

	//提现记录
	public function record(){
		$this->assign('page_title',"提现记录");
		$offer_status = C('MONEY_OFFER_STATUS');
		$user_id = $_SESSION['user_id'];
		
		$count      = D("users_money_offer")->where("user_id='$user_id'")->count();// 查询满足要求的总记录数
		$pagecount = 10;
		$page = new \Think\Page($count , $pagecount);
		$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ');
		$show = $page->show();
		
		$record = D("users_money_offer")->where("user_id='$user_id'")->order("offer_id desc")->limit($page->firstRow.','.$page->listRows)->select();
		
		for ($i=0; $i < count($record); $i++) { 
			$bank_id = $record[$i]['bank_id'];
			$bank_name = D("users_bank")->where("bank_id='$bank_id'")->getField('bankname');
			$record[$i]['bankname'] = $bank_name;
			$record[$i]['statusname'] = $offer_status[$record[$i]['status']];
		}
		$this->assign('record',$record);
		$this->assign('page',$show);
		$this->display();
	}

	//提现申请
	public function withdrawal(){
		$this->assign('page_title',"提现");
		$user_id = $_SESSION['user_id'];

		if(IS_POST){
			$data=I('post.');

			//查询当前用户的可提现金额
			$user_money = D("users")->where("user_id='$user_id'")->getField('user_money');
			$frozen_money = D("users")->where("user_id='$user_id'")->getField('frozen_money');
			if( $user_money < $data['money'] ){
				die('金额不足，无法提现');
			}
			if( empty($data['bank_id']) ){
				die('提现银行信息错误');
			}

			//生成用户提现记录
			$data_withdrawal['user_id'] = $user_id;
			$data_withdrawal['bank_id'] = $data['bank_id'];
			$data_withdrawal['money'] = $data['money'];
			$data_withdrawal['creat_time'] = date("Y-m-d H:i:s", time());
			D("users_money_offer")->add($data_withdrawal);

			//生成用户帐户资金异动记录
			$data_account_log['user_id'] = $user_id;
			$data_account_log['user_id'] = $data['money'];
			$data_account_log['change_desc'] = "提现申请，金额 ".$data['money'];
			$data_account_log['change_type'] = 1;
			$data_account_log['change_time'] = date("Y-m-d H:i:s", time());
			$data_account_log['create_time'] = date("Y-m-d H:i:s", time());
			D("users_account_log")->add($data_account_log);

			//更新用户的可用余额和冻结金额
			$user_data['user_money'] = $user_money - $data['money'];
			$user_data['frozen_money'] = $frozen_money + $data['money'];
			D("users")->where("user_id='$user_id'")->save($user_data);
			die('success');
		}

		//查询用户余额和冻结金额
		$userinfo = D("users")->where("user_id='$user_id'")->find();
		$this->assign('userinfo',$userinfo);

		//查询用户设置的提现银行卡
		$bank = D("users_bank")->where("user_id='$user_id'")->select();
		for ($i=0; $i < count($bank); $i++) { 
			$bankcode = substr_replace($bank[$i]['bankcode'],'****',8,4);
			$bankcode = str_split($bankcode,4);
			$bankcode = implode(' ',$bankcode);
			$bank[$i]['bankcode'] = $bankcode;

			if($bank[$i]['bankname']=='中国建设银行'){
				$bank[$i]['bankico'] = 'user-icon-jiansheyinxing bank-logo-jianshe';
			}elseif($bank[$i]['bankname']=='中国银行'){
				$bank[$i]['bankico'] = 'user-icon-zhongguoyinxing bank-logo-zhongguo';
			}elseif($bank[$i]['bankname']=='中国农业银行'){
				$bank[$i]['bankico'] = 'user-icon-nongyeyinxing bank-logo-nongye';
			}elseif($bank[$i]['bankname']=='中国工商银行'){
				$bank[$i]['bankico'] = 'user-icon-gongshangyinxing bank-logo-gongshang';
			}
		}
		$this->assign('bank',$bank);
		$this->assign('banknum', count($bank));

		$this->display();
	}

	public function login_out(){
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_phone']);
		unset($_SESSION['user_city']);
		unset($_SESSION['user_auth']);
	}
	
}