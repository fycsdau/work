<?php
namespace Web\Controller;
use Think\Controller;

class RunController extends CommonController {

 	public function index(){
        $this->display();
    }
    
    public function fengzhushou(){
    	print_r("123123123");die;
    	//include 'taobao/recharge.php';//自动充值
    	import('Org.Net.HttpCurl');
    	
    	$channelNo = '577918';
    	$userID	= 'flow_shmow';
    	$userpwd_md5 = md5('E7C&1pA5JSn');
    	$KeyStr	= 'FZSFLOW';
    	$range = "2";
    	$effectStartTime = "1";
    	$effectTime = "1";
    	$retUrl = "http://m.llian.com.cn/admin.php/Web/Run/feng_callback";
    	
    	$map_up['status'] = 2;
    	$orders_up = M('Order')->where($map_up)->select();
    	if (count($orders_up) > 0 ){
    		foreach ($orders_up as $order){
    			$phone=$order['phone'];
    			$package = $order['package_name'];
    			if (strstr($package, "M") == "M"){
    				$flowValue = explode("M", $package)[0];
    			}else{
    				$flowValue = explode("G", $package)[0] * 1024;
    			}
    			$orderId = $order['order_id'];
    			
    			$txnDate = date("YmdHis",time());
		    	$str = md5($channelNo . $userID . $userpwd_md5 . $phone . $flowValue . $range . $effectStartTime . $effectTime . $orderId . $txnDate . $KeyStr);
		    	$md5str = strtoupper($str);
		    	
		    	$url = "http://flow.phone580.com/fzsFlow/api/external/flowOrderApi?channelNo=".$channelNo."&userId=".$userID."&userpws=".$userpwd_md5.
		    	"&phone=".$phone."&flowValue=".$flowValue."&range=".$range."&effectStartTime=".$effectStartTime."&effectTime=".$effectTime.
		    	"&orderId=".$orderId."&txnDate=".$txnDate."&md5Str=".$md5str."&version=1.0&retUrl=".$retUrl;
		    	
		    	$review = \HttpCurl::post($url);
		    	$xml = simplexml_load_string($review);
		    	$reqcode = $xml->reqcode;
		    	//提交成功
		    	if ((string)$reqcode == '0'){
		    		 //充值记录
		    		 $data_up['phone'] = $order['phone'];
			         $data_up['order_id'] = $order['order_id'];
			         $data_up['user_id'] = $order['user_id'];
			         $data_up['order_money'] = $order['order_money'];
			         $data_up['package_name'] = $order['package_name'];
			         $data_up['operate_name'] = $order['operate_name'];
			         $data_up['create_time'] = date("Y-m-d H:i:s",time());
			         $data_up['status'] = 3;
			         $data_up['supplier'] = "蜂助手";
			         M('Recharge')->add($data_up);
			
			         $data1_up['status'] = 5;
			         $where_up['order_id'] = $data_up['order_id'];
			         M('Order')->where($where_up)->save($data1_up);
			         print_r("success");die;
    			}else{
			         //帐户异动记录
			         $data_account_log['user_id'] = $order['user_id'];
			         $data_account_log['order_id'] = $order['order_id'];
			         $data_account_log['user_money'] = $order['order_money'];
			         $data_account_log['change_desc'] = "订单". $order['order_id'] ."充值失败， 余额退回";
			         $data_account_log['change_type'] = 4;
			         $data_account_log['change_time'] = date("Y-m-d H:i:s", time());
			         $data_account_log['create_time'] = date("Y-m-d H:i:s", time());
			         M("users_account_log")->add($data_account_log);
			
						//帐户余额
			         $map['user_id'] = $order['user_id'];
			         $user_money = D("users")->where($map)->getField('user_money');
			         $data1['user_money'] = $user_money + $order['order_money'];
			         $amountPay = M("Users")->where($map)->save($data1);
			
			         $data1_up['status'] = 4;
			         $where_up['order_id'] = $order['order_id'];
			         M('Order')->where($where_up)->save($data1_up);
			         
			         //提交失败通知推送
			         require_once(THINK_PATH.'../WeChat/lanewechat.php');
			         $user_id = $order['user_id'];
			         $order_id = $order['order_id'];
			         $openid = D("users")->where("user_id='$user_id'")->getField('openid');
			         $order_sn = D("order")->where("order_id='$order_id'")->getField('order_sn');
			         $WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
			         $nickname = M('users')->where("user_id='$user_id'")->getField('nickname');
			         $w_description = '订单编号: '.$order_sn."\r\n".'充值号码: '.$order['phone']."\r\n套餐名称: ".$order['package_name']."\r\n订单金额: ".$order['order_money']." 元\r\n";
			         $w_title = "流量充值失败～";
			         $w_description .= "充值状态: 充值失败\r\n \r\n我们很抱歉的通知您，充值失败了～\r\n在殴打程序猿的同时，我们已经把款项退回您的帐户，请在会员中心查看。";
			         //$w_picurl = $WECHAT_URL."wechat_pay_error.jpg";
			         $w_picurl = "";
			         $w_url = $WECHAT_URL."../index.php/user/order_detail/id/".$order['order_id'];
			         //订单的用户推送订单提醒
			         $tuwenList[] = array('title'=>$w_title, 'description'=>$w_description, 'pic_url'=>$w_picurl, 'url'=>$w_url);
			         $itemList = array();
			         foreach ($tuwenList as $tuwen) {
			         	$itemList[] = \LaneWeChat\Core\ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
			         }
			         $ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
			         
			         if(isset($ret['errcode'])){
			         	$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
			         	$ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
			         }
			         
			         
			      }
    			
    		}
    	}
    	
    	
    }
    
    //蜂助手回调
    public function feng_callback(){
    	$xml = simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA']);
    	$retcode = $xml->retcode;
    	$order_id = $xml->orderId;
    	
//     	$retcode = '1';
//     	$order_id = '451';
    	
    	//$where['status'] = 3;
    	$where['order_id'] = $order_id;
    	$where['supplier'] = "蜂助手";
    	$recharge = M('Recharge')->where($where)->find();
    	//成功
  	
	    if((string)$retcode == '1'){//成功
	         $data['status'] = 1;
	         $data['finish_time'] = date("Y-m-d H:i:s",time());
	         $data_order['status'] = 3;
	         $data_order['complete_time'] = date("Y-m-d H:i:s",time());
	
	         $user_id = $recharge['user_id'];
	         $parentid = M('users')->where("user_id='$user_id'")->getField('parentid');
	
	         //微信上三层充值成功提醒、分销
	         for ($i=0; $i < 3; $i++) {
	            //分销
	            $map1['order_id'] = $recharge['order_id'];
	            $order = M("Order")->where($map1)->find();
	            if($parentid != 0){
	               //帐户异动记录
	               if ($i == 0){
	                  $rate_money = $order['one_rate'];
	               }elseif ($i == 1){
	                  $rate_money = $order['two_rate'];
	               }else{
	                  $rate_money = $order['three_rate'];
	               }
	               $data_account_log['user_id'] = $parentid;
	               $data_account_log['order_id'] = $recharge['order_id'];
	               $data_account_log['user_money'] = $rate_money;
	               $m = $i + 1;
	               $data_account_log['change_desc'] = "订单 ".$recharge['order_id'] . ",第" .$m ." 级分成";
	               $data_account_log['change_type'] = 2;
	               $data_account_log['change_time'] = date("Y-m-d H:i:s", time());
	               $data_account_log['create_time'] = date("Y-m-d H:i:s", time());
	               D("users_account_log")->add($data_account_log);
	   				//读取推荐人，并更新该订单的分成到用户表
	               D("users")->where("user_id='$parentid'")->setInc('user_money', $rate_money);
	               
	               //订单提交用户的微信消息推送
	               require_once(THINK_PATH.'../WeChat/lanewechat.php');
	               $user_id = $recharge['user_id'];
	               $order_id = $recharge['order_id'];
	               
	               //判断当前通知推送记录
	               $openid = D("users")->where("user_id='$parentid'")->getField('openid');
	               $order_sn = D("order")->where("order_id='$order_id'")->getField('order_sn');
	               $WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
	               $nickname = M('users')->where("user_id='$user_id'")->getField('nickname');
	               $w_description = '订单编号: '.$order_sn."\r\n订单金额: ".$recharge['order_money']." 元\r\n"."赏金金额: ".$rate_money." 元\r\n";
	               $w_title = "代言费到账提醒";
	               $w_description .= "您的好友 $nickname 成功充值 $recharge[package_name]，系统奖励您 $rate_money 元.\r\n \r\n继续努力哦";
	               
	               //$w_picurl = $WECHAT_URL."wechat_pay_success.jpg";
	               $w_picurl = "";
	               $w_url = $WECHAT_URL."../index.php/user/spokes";
	               //订单的用户推送订单提醒
	               $tuwenList[] = array('title'=>$w_title, 'description'=>$w_description, 'pic_url'=>$w_picurl, 'url'=>$w_url);
	               $itemList = array();
	               foreach ($tuwenList as $tuwen) {
	               		$itemList[] = \LaneWeChat\Core\ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
	               }
	               $ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
	               if(isset($ret['errcode'])){
		               	$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
		               	$ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
	               }
	               
	               $users = D("users")->where("user_id='$parentid'")->find();
	               $user_id = $users['parentid'];
	               $parentid = $users['parentid'];
	               
	               
	            }
	         }
	      }elseif ((string)$retcode == '9' || (string)$retcode == '11' || (string)$retcode == '12'){//失败
	         $data['status'] = 2;
	         $data['finish_time'] = date("Y-m-d H:i:s",time());
	         $data_order['status'] = 4;
	         $data_order['complete_time'] = date("Y-m-d H:i:s",time());
	
	   		//帐户异动记录
	         $data_account_log['user_id'] = $recharge['user_id'];
	         $data_account_log['order_id'] = $recharge['order_id'];
	         $data_account_log['user_money'] = $recharge['order_money'];
	         $data_account_log['change_desc'] = "订单". $recharge['order_id'] ."充值失败， 余额退回";
	         $data_account_log['change_type'] = 4;
	         $data_account_log['change_time'] = date("Y-m-d H:i:s", time());
	         $data_account_log['create_time'] = date("Y-m-d H:i:s", time());
	         M("users_account_log")->add($data_account_log);
	
	   		//帐户余额
	         $user_id = $recharge['user_id'];
	        
	         D("users")->where("user_id='$user_id'")->setInc('user_money',$recharge['order_money']);
	
	         //微信推送充值失败退款提醒
	
	      }elseif ((string)$retcode == '0'){//处理中
	         $data['status'] = 3;
	         $data_order['status'] = 5;
	      }
	      
	      $map_r['order_id'] = $recharge['order_id'];
	      M('Recharge')->where($map_r)->save($data);
	      M('Order')->where($map_r)->save($data_order);
	      
	      if((string)$retcode == '1' || (string)$retcode == '9' ){
	
	     	 //订单提交用户的微信消息推送
	         require_once(THINK_PATH.'../WeChat/lanewechat.php');
	         $user_id = $recharge['user_id'];
	         $order_id = $recharge['order_id'];
	
	         //判断当前通知推送记录
	            $openid = D("users")->where("user_id='$user_id'")->getField('openid');
	            $order_sn = D("order")->where("order_id='$order_id'")->getField('order_sn');
	            $WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
	            $nickname = M('users')->where("user_id='$user_id'")->getField('nickname');
	            $w_description = '订单编号: '.$order_sn."\r\n".'充值号码: '.$recharge['phone']."\r\n套餐名称: ".$recharge['package_name']."\r\n订单金额: ".$recharge['order_money']." 元\r\n";
	            if((string)$retcode == '1'){
	               $w_title = "流量充值成功 ";
	               $w_description .= "充值状态: 充值成功\r\n \r\n推荐您的朋友使用，您可以获得推荐奖励哦~ \r\n一般人我可不告诉Ta";
	
	            }elseif ((string)$retcode == '9') {
	               $w_title = "流量充值失败～";
	               $w_description .= "充值状态: 充值失败\r\n \r\n我们很抱歉的通知您，充值失败了～\r\n 在殴打程序猿的同时，我们已经把款项退回您的帐户，请在会员中心查看。";
	            }
	         //$w_picurl = $WECHAT_URL."wechat_pay_success.jpg";
	            $w_picurl = "";
	            $w_url = $WECHAT_URL."../index.php/user/order_detail/id/".$recharge['order_id'];
	            //订单的用户推送订单提醒
	            $tuwenList[] = array('title'=>$w_title, 'description'=>$w_description, 'pic_url'=>$w_picurl, 'url'=>$w_url);
	            $itemList = array();
	            foreach ($tuwenList as $tuwen) {
	               $itemList[] = \LaneWeChat\Core\ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
	            }
	            $ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
	            if(isset($ret['errcode'])){
	            	$access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
	            	$ret = \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
	            }
	      	
	      }
    }

}