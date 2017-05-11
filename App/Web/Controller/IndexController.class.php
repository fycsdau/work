<?php
namespace Web\Controller;
use Think\Controller;
use Home\Controller\PublicController;

vendor('Pay.JSAPI');

class IndexController extends CommonController {
	public function test(){
		print_r($_SESSION);
		parent::_initialize();
		print_r($_SESSION);
	}

    public function index(){
//     	if(!is_wechat_browser()){
//     		die('请在微信中打开');
//     	}
    	parent::_initialize();
        $this->assign('page_title', '充值');
        $user_id = $_SESSION['user_id'];

        $phone = I('get.phone');
        if(!empty($phone)){
            $mobile_info = D("users_phonenumber")->where("phone='$phone'")->getField("mobile_info");
        }else{
            $phones = D("users_phonenumber")->where("user_id='$user_id'")->order("id desc")->find();
            if(!empty($phones)){
                $phone = $phones['phone'];
                $mobile_info = $phones['mobile_info'];
            }
        }
        $this->assign('mobile_info', $mobile_info);
        $this->assign('phone', $phone);

        $map['operate_name'] = '中国移动';
        $package = M('SupplierPackage')->where($map)->select();
        $mobile_package = array();
        foreach ($package as $item){
            $where1['package_id'] = $item['package_id'];
            $goods = M('Goods')->where($where1)->select();
            if (count($goods) > 1){
                $province = 0;
                foreach ($goods as $good){
                    if ($province == 0 && $good['provice'] == '全国'){
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $province = 1;
                    }else{
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $province = 1;
                    }
                }

            }elseif (count($goods) == 1){
                $item['shop_point'] = $goods[0]['shop_point'];
                $item['goods_id'] = $goods[0]['id'];
            }
            array_push($mobile_package, $item);
        }
    	//print_r($mobile_package);die;
        $this->assign('mobile_package',$mobile_package);
        $this->display();
    }

    public function ajaxGetMobileBelong(){
        $mobile_belong = "";
        $where['phone'] = substr(I('post.mobile') , 0 , 7);
        $mobile_belong = M('MobileBelong')->where($where)->find();
        $mobile_package = array();
        $map['operate_name'] = $mobile_belong['corp'];
        $package = M('SupplierPackage')->where($map)->select();
        foreach ($package as $item){
            $where1['package_id'] = $item['package_id'];
            $goods = M('Goods')->where($where1)->select();
            $province = 0;
            if (count($goods) > 1){
                foreach ($goods as $good){
                    if ($good['provice'] == $mobile_belong['province']){
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $item['count'] = $good['count'];
                        $province = 1;
                    }elseif ($province == 0 && $good['provice'] == '全国'){
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $item['count'] = $good['count'];
                        $province = 1;
                    }
                }

            }elseif (count($goods) == 1){
                $item['shop_point'] = $goods[0]['shop_point'];
                $item['goods_id'] = $goods[0]['id'];
                $item['count'] = $goods[0]['count'];
            }
            array_push($mobile_package, $item);
        }
        $mobile_belong['goods'] = json_encode($mobile_package);
        $data = json_encode($mobile_belong);
        echo $data;
    }
    
    public function ajaxCancelOrder(){
    	$where['order_id'] = I("post.order_id");
    	$data['status'] = 0;
    	$result = M('Order')->where($where)->save($data);
    	echo $result;
    }

    public function ajaxGetMobileNull(){
        $mobile_belong = "";
        $map['operate_name'] = '中国移动';
        $package = M('SupplierPackage')->where($map)->select();
        $mobile_package = array();
        foreach ($package as $item){
            $where1['package_id'] = $item['package_id'];
            $goods = M('Goods')->where($where1)->select();
            $province = 0;
            if (count($goods) > 1){
                foreach ($goods as $good){
                    if ($province == 0 && $good['provice'] == '全国'){
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $item['count'] = $good['count'];
                        $province = 1;
                    }else{
                        $item['shop_point'] = $good['shop_point'];
                        $item['goods_id'] = $good['id'];
                        $item['count'] = $good['count'];
                        $province = 1;
                    }
                }

            }elseif (count($goods) == 1){
                $item['shop_point'] = $goods[0]['shop_point'];
                $item['goods_id'] = $goods[0]['id'];
                $item['count'] = $goods[0]['count'];
            }
            array_push($mobile_package, $item);
        }
        $data = json_encode($mobile_package);
        echo $data;
    }

    //历史充值号码列表
    public function phone_list(){
        $this->assign('page_title', '号码列表');
        $user_id = $_SESSION['user_id'];
        $phonelist = M('users_phonenumber')->where("user_id='$user_id'")->select();
        $this->assign('phonelist',$phonelist);
        $this->display();
    }

    //创建订单
    public function create_order(){
        $this->assign('page_title', '支付');
        $user_id = $_SESSION['user_id'];
        $result = array();
        if(empty(I('get.mobile'))){
            $result['success'] = 0;
            $result['info'] = '手机号码不能为空';
            exit(json_encode($result));
        }
        if(empty(I('get.select_good_id'))){
            $result['success'] = 0;
            $result['info'] = '套餐未选择';
            exit(json_encode($result));
        }

        //判断当前用户是否有未处理订单
        /*$lost_order = D("order")->where("user_id='$user_id' and status=1")->select();
        if(!empty($lost_order)){
            $lost_order_id = $lost_order[0]['order_id'];
            redirect('/index.php/user/order_detail/id/'.$lost_order_id, 0, '有历史订单待处理');
            die();
        }*/

        $map['id'] = I('get.select_good_id');
        $goods = M('Goods')->join('as goods left join llhl_supplier_package as package on goods.package_id = package.package_id')->where($map)->find();
        $goods['good_id'] = $goods['id'];
        M('Goods')->where($map)->setInc('count',1);
        unset($goods['id']);
        $goods['order_sn'] = build_order_no();
        $goods['create_time'] = date("Y-m-d H:i:s",time());
        $goods['order_money'] = $goods['market_price'] * $goods['shop_point'];
        $goods['phone'] = I('get.mobile');
        $goods['status'] = 1;
        $goods['user_id'] = $user_id;

        $goods_id = M('Order')->add($goods);
        $last_id = M('Order')->getLastInsID();
        $this->assign('last_id', $last_id);

        $order_money = $goods['order_money']*100;
        $operate_name = $goods['operate_name'];
        $package_name = $goods['package_name'];
        $phone = str_replace(' ', '', $goods['phone']);
        $mobile_info = I('get.mobile_info');

        //判断当前充值号码是否在历史号码表
        $phone = I('get.mobile');
        $phonelist = M('users_phonenumber')->where("user_id='$user_id' and phone='$phone'")->find();
        if(empty($phonelist)){
            $data_p['user_id'] = $user_id;
            $data_p['mobile_info'] = $mobile_info;
            $data_p['phone'] = $phone;
            M('users_phonenumber')->add($data_p);
        }

        $this->assign('order_money',$goods['order_money']);

        $result['success'] = 1;
        $result['info'] = '订单创建成功';
        $result['order_id'] = $last_id;
        exit(json_encode($result));
        //redirect('/index.php/user/order_detail/id/'.$last_id, 0, '正在跳转到订单详情');
        die();

        //帐户余额
        $user_money = D("users")->where("user_id='$user_id'")->getField('user_money');
        $this->assign('user_money', $user_money);

        $this->display('pay');
    }

    //微信支付回调
    public function wechat_pay_callback(){
        $data = $GLOBALS['HTTP_RAW_POST_DATA'];

        $xml = json_encode($data);
        $xml = str_replace("\n", "", $xml);
        $xml = str_replace("\/", "/", $xml);

        preg_match_all("/\<return_code\>(.*?)\<\/return_code\>/",$xml,$return_code);
        $return_code = str_replace("<![CDATA[", "", $return_code[1]);
        $return_code = str_replace("]]>", "", $return_code);
        $return_code = $return_code[0];

        preg_match_all("/\<result_code\>(.*?)\<\/result_code\>/",$xml,$result_code);
        $result_code = str_replace("<![CDATA[", "", $result_code[1]);
        $result_code = str_replace("]]>", "", $result_code);
        $result_code = $result_code[0];

        preg_match_all("/\<out_trade_no\>(.*?)\<\/out_trade_no\>/",$xml,$out_trade_no);
        $out_trade_no = str_replace("<![CDATA[", "", $out_trade_no[1]);
        $out_trade_no = str_replace("]]>", "", $out_trade_no);
        $out_trade_no = $out_trade_no[0];

        preg_match_all("/\<openid\>(.*?)\<\/openid\>/",$xml,$openid);
        $openid = str_replace("<![CDATA[", "", $openid[1]);
        $openid = str_replace("]]>", "", $openid);
        $openid = $openid[0];

        preg_match_all("/\<transaction_id\>(.*?)\<\/transaction_id\>/",$xml,$transaction_id);
        $transaction_id = str_replace("<![CDATA[", "", $transaction_id[1]);
        $transaction_id = str_replace("]]>", "", $transaction_id);
        $transaction_id = $transaction_id[0];

        $wechat_log = D("wxpay_notify")->where("order_sn='$out_trade_no'")->find();
        if(!empty($wechat_log)){
            die();
        }
        $data1['text'] = $data;
        $data1['openid'] = $openid;
        $data1['transaction_id'] = $transaction_id;
        $data1['order_sn'] = $out_trade_no;
        D("wxpay_notify")->add($data1);

        if( $return_code=="SUCCESS" && $result_code=="SUCCESS"){
            //更新订单状态
            $order_data['pay_time'] = date("Y-m-d H:i:s", time());
            $order_data['status'] = 2;
            $order_data['pay_code'] = 1;
            D("order")->where("order_sn='$out_trade_no'")->save($order_data);

            //查询订单信息
            $orders = D("order")->where("order_sn='$out_trade_no'")->find();
            $order_sn = $orders['order_sn'];
            $order_id = $orders['order_id'];
            $user_id = $orders['user_id'];

            //订单提交用户的微信消息推送
            $WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
            $nickname = M('users')->where("user_id='$user_id'")->getField('nickname');
            require_once(THINK_PATH.'../WeChat/lanewechat.php');
            $w_description = '订单编号: '.$orders['order_sn']."\r\n".'充值号码: '.$orders['phone']."\r\n套餐名称: ".$orders['package_name']."\r\n订单金额: ".$orders['order_money']." 元\r\n支付状态: 微信支付 - 付款成功\r\n \r\n我们正在为您充值流量，很快到账哦～";
            $w_title = "订单付款成功提醒";
            $w_picurl = $WECHAT_URL."wechat_pay_success.jpg";
            $w_url = $WECHAT_URL."../index.php/user/order_detail/id/".$orders['order_id'];
            //订单的用户推送订单提醒
            $tuwenList[] = array('title'=>$w_title, 'description'=>$w_description, 'pic_url'=>$w_picurl, 'url'=>$w_url);
            $itemList = array();
            foreach ($tuwenList as $tuwen) {
                $itemList[] = \LaneWeChat\Core\ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);

            //更新用户消费金额统计
            if($orders['order_money'] > 0){
                D("user")->where("user_id='$user_id'")->setInc('cost_money', $orders['order_money']);
            }

            $notify = new \PayNotifyCallBack();
            $notify->Handle(false);

        }
    }
    
    
    public function ajaxAmountPay(){
    	$user_id = $_SESSION['user_id'];
    	$money = $_POST['order_money'];
    	$order_id = $_POST['order_id'];
		
    	//订单金额
    	$map1['order_id'] = $order_id;
    	$orders = M("order")->where($map1)->find();
    	$order_money = $orders['order_money'];
    	
    	if((float)$order_money != (float)$money){
    		die('支付失败');
    	}
    	//帐户余额
    	$map['user_id'] = $user_id;
    	$user_money = D("users")->where($map)->getField('user_money');
    	if((float)$user_money < (float)$order_money){
    		die('余额不足');
    	}
    	$data['user_money'] = $user_money - $order_money;
    	$amountPay = M("Users")->where($map)->save($data);
    	//帐户异动记录
    	$data_account_log['user_id'] = $user_id;
    	$data_account_log['order_id'] = $_POST['order_id'];
    	$data_account_log['user_money'] = -$order_money;
    	$data_account_log['change_desc'] = "订单". $order_id ."支付， 余额支付";
    	$data_account_log['change_type'] = 3;
    	$data_account_log['change_time'] = date("Y-m-d H:i:s", time());
    	$data_account_log['create_time'] = date("Y-m-d H:i:s", time());
    	M("users_account_log")->add($data_account_log);

    	//更新订单状态
        $order_data['pay_time'] = date("Y-m-d H:i:s", time());
        $order_data['status'] = 2;
        $order_data['pay_code'] = 2;
        D("order")->where("order_id='$order_id'")->save($order_data);

        //查询订单信息
        $orders = D("order")->where("order_id='$order_id'")->find();
        $order_sn = $orders['order_sn'];
        $openid = $_SESSION['openid'];
        //订单提交用户的微信消息推送
        $WECHAT_URL = M('wechat_config')->where("id=1")->getField('url');
        require_once(THINK_PATH.'../WeChat/lanewechat.php');
        $w_description = '订单编号: '.$orders['order_sn']."\r\n".'充值号码: '.$orders['phone']."\r\n套餐名称: ".$orders['package_name']."\r\n订单金额: ".$orders['order_money']." 元\r\n支付状态: 余额支付 - 付款成功\r\n \r\n我们正在为您充值流量，很快到账哦～";
        $w_title = "订单付款成功提醒";
        $w_picurl = $WECHAT_URL."wechat_pay_success.jpg";
        $w_url = $WECHAT_URL."../index.php/user/order_detail/id/".$orders['order_id'];
        //订单的用户推送订单提醒
        $tuwenList[] = array('title'=>$w_title, 'description'=>$w_description, 'pic_url'=>$w_picurl, 'url'=>$w_url);
        $itemList = array();
        foreach ($tuwenList as $tuwen) {
        	$itemList[] = \LaneWeChat\Core\ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
        }
        \LaneWeChat\Core\ResponseInitiative::news($openid, $itemList);
        

        $this->ajaxReturn($amountPay);
    }
    
    
    public function ajaxCallDaYu(){
    	$this->fengzhushou();//蜂助手自动充值
    	//include 'taobao/recharge.php';//大鱼自动充值
    	$data = 1;
    	$this->ajaxReturn($data);
    }
    
    public function ajaxRemoveMobileBelong(){
    	$where['user_id'] = $_SESSION['user_id'];
    	$where['phone'] = I("post.mobile");
    	$data = M("Users_phonenumber")->where($where)->delete();
    	$this->ajaxReturn($where['phone']);
    }
    
    public function fengzhushou(){
    	import('Org.Net.HttpCurl');
    	 
    	$channelNo = '577918';
    	$userID	= 'flow_shmow';
    	$userpwd_md5 = md5('E7C&1pA5JSn');
    	$KeyStr	= 'FZSFLOW';
    	$range = "2";
    	$effectStartTime = "1";
    	$effectTime = "1";
    	$retUrl = "http://m.llian.com.cn/admin.php/Web/Index/feng_callback";
    	 
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
    	 
    	$where['status'] = 3;
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

class PayNotifyCallBack extends \WxPayNotify{
    //查询订单 
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);

        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {

        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        
        $data1['text'] = $data;
        $data1['openid'] = $data['openid'];
        $data1['transaction_id'] = $data['transaction_id'];
        $data1['order_sn'] = $data['out_trade_no'];
        D("wxpay_notify")->add($data1);

        return true;
    }
}