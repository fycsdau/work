<?php

namespace Home\Controller;
use Think\Controller;

class RechargeController extends CommonController{
	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;
	}
	
	function _filter(&$map) {
		$operate_name = I('post.operate_name');
		$phone = I('post.phone');
		$status = I('post.status');
		$out_recharge_id = I('post.out_recharge_id');
		//print_r($status);die;
		if(!empty($operate_name) || !empty($phone) || !empty($out_recharge_id)){
			$map['phone'] = array('like','%'. $phone .'%');
			$map['operate_name'] = array('like','%'. $operate_name .'%');
			$map['out_recharge_id'] = array('like','%'. $out_recharge_id .'%');
			$map['_logic'] = 'and';
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
			M('Recharge')->where("id='$id'")->delete();
			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
		}
	}
	

}