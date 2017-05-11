<?php

namespace Home\Controller;
use Think\Controller;

class OfferController extends CommonController{
	public function _initialize() {
		parent::_initialize();
		$this->dbname = 'users_money_offer';
	}

	function _filter(&$map) {
		$user_id = I('post.user_id');
		$status = I('post.status');
		if(!empty($user_id)){
			$map['user_id'] = array('like','%'. $user_id .'%');
		}
		if ($status != ''){
			$map['status'] = $status;
			$map['_logic'] = 'and';
		}
	}
	
	//删除订单
	public function del(){
		$id = I('get.id');
		if($id){
			$loginfo = $_REQUEST['navTabId'].' 删除 '.$id;
			M('Order')->where("order_id='$id'")->delete();
			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
		}
	}
	

}