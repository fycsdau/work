<?php

namespace Home\Controller;
use Think\Controller;

class PointController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;

		$verifs = C('POINT_VERIF_STATUS');
		$verifstatus = array();
		foreach ($verifs as $key => $value) {
			$verifstatus[$key]['id'] = $key;
			$verifstatus[$key]['name'] = $value;
		}
		$this->assign('verif_status',$verifstatus);

		$orglist=orgcateTree($pid=0,$level=0,$type=0);

		$uid = $_SESSION['lthn']['uid'];
		$user_rules_id = M('auth_group_access a')
		->where("a.uid='$uid' and g.status='1'")
		->join("lthn_auth_group g on a.group_id=g.id")
		->field('rules')->find();
		$user_rules_ids = implode(",", $user_rules_id);
		$user_rule = M('auth_rule')->where("id in (". $user_rules_ids .")")->field('name')->select();
		foreach ($user_rule as $key => $value) {
			$userrule[] = $value['name'];
		}

		$orglist=orgcateTree($pid=1,$level=0,$type=0);
		if(!in_array($uid,C('ADMINISTRATOR'))){ 
			if(in_array("home/info/index/limit/all", $userrule)){
			}elseif(in_array("home/info/index/limit/user", $userrule)){
				$this->assign('orgone',1);
				$orglist = array();
				$orglist[0]["id"] = $_SESSION['lthn']['depname'];
				$orglist[0]["title"] = getparentname($_SESSION['lthn']['depname']);
				$orglist[0]["selected"] = 1;
			}else{
				$this->assign('orgone',1);
				$orglist = array();
				$orglist[0]["id"] = $_SESSION['lthn']['depname'];
				$orglist[0]["title"] = getparentname($_SESSION['lthn']['depname']);
				$orglist[0]["selected"] = 1;
			}
		}

		$this->assign('orglist',$orglist);
	}
	
	function _filter(&$map) {
		$title = I('post.title');
		if(!empty($title)){
			$map['title'] = array('like','%'. $title .'%');
		}
		$person = I('post.person');
		if(!empty($person)){
			$map['person'] = array('like','%'. $person .'%');
		}
		$status = I('get.status')?I('get.status'):I('post.status');
		if(!empty($status)){
			$map['status'] = $status;
		}
		$parentid = I('get.parentid')?I('get.parentid'):I('post.parentid');
		if(!empty($parentid)){
			$map['parentid'] = $parentid;
		}else{
			$parentid = $_SESSION['lthn']['depname'];
			$uid = $_SESSION['lthn']['uid'];
			if(!in_array($uid,C('ADMINISTRATOR'))){ 
				if(in_array("home/point/index/limit/all", $userrule)){
				}elseif(in_array("home/point/index/limit/user", $userrule)){
					$map['parentid'] = $parentid;
				}else{
					$map['parentid'] = $parentid;
				}
			}
		}
	}

	public function _befor_insert($data){
		$d = time();
		$ctime = date("Y-m-d H:i:s", $d);
		$data['adddate'] = $ctime;
		$data['adduser'] = session('truename')."(". session('username') .")";
		$data['adduserid']=session('uid');
		if($data['status']==1){
			$data['verifdate'] = $ctime;
			$data['verifuser'] = session('truename')."(". session('username') .")";
			$data['verifuserid']=session('uid');
		}
		return $data;
	}

}