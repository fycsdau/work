<?php

namespace Home\Controller;
use Think\Controller;

class FansController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;

		$orglist=orgcateTree($pid=0,$level=0,$type=0);
		$this->assign('orglist',$orglist);

		$authlist = C('USER_AUTH_TYPE');
		$auth_list = array();
		foreach ($authlist as $key => $value) {
			$auth_list[$key]['id'] = $key;
			$auth_list[$key]['name'] = $value;
		}
		$this->assign('auth_list',$auth_list);
	}
	
	function _filter(&$map) {
		$user_name = I('post.user_name');
		if(!empty($user_name)){
			$map['user_name'] = array('like','%'. $user_name .'%');
			$map['nickname'] = array('like','%'. $user_name .'%');
			$map['_logic'] = 'OR';
		}
		$user_phone = I('post.user_phone');
		if(!empty($user_phone)){
			//$map['user_phone'] = array('like','%'. $user_phone .'%');
			$map['user_phone'] = $user_phone;
		}
		$user_city = I('post.user_city');
		if(!empty($user_city)){
			$map['user_city'] = $user_city;
		}
		$sex = I('post.sex');
		if(!empty($sex)){
			$map['sex'] = $sex;
		}
		$user_auth = I('post.user_auth');
		if(!empty($user_auth)){
			$map['user_auth'] = $user_auth;
		}
	}

	function _before_edit(){
		$orglist=orgcateTree($pid=0,$level=0,$type=0);
		$this->assign('orglist',$orglist);

		$authlist = C('USER_AUTH_TYPE');
		$auth_list = array();
		foreach ($authlist as $key => $value) {
			$auth_list[$key]['id'] = $key;
			$auth_list[$key]['name'] = $value;
		}
		$this->assign('auth_list',$auth_list);
	}

	public function _befor_update($data){
		$id = $data["id"];
		require_once(THINK_PATH.'../WeChat/lanewechat.php');
		$tousername = M('fans')->field('openid')->find($id);
		//$push = \LaneWeChat\Core\ResponseInitiative::text($tousername, '文本消息内容');
		//var_dump($push);
	}

}