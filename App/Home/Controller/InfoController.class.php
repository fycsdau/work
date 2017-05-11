<?php

namespace Home\Controller;
use Think\Controller;

class InfoController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;

		$infotype = C('INFO_TYPE');
		$infotypes = array();
		foreach ($infotype as $key => $value) {
			$infotypes[$key]['id'] = $key;
			$infotypes[$key]['name'] = $value;
		}
		$this->assign('infotype',$infotypes);

		$playname = C('PLAYNAME');
		$playnames = array();
		foreach ($playname as $key => $value) {
			$playnames[$key]['id'] = $key;
			$playnames[$key]['name'] = $value;
		}
		$this->assign('playnames',$playnames);

		$typelist = C('INFO_TYPE');
		$type = I('get.type');
		$this->assign('infotypeid',$type);
		$this->assign('infotypename',$typelist[$type]);
		$this->assign('infonavTabId', "info". $type ."list");

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
			if(in_array("home/info/index/changeinfostatus", $userrule)){
				$this->assign('info_status_change',1);
			}else{
				$this->assign('info_status_change',0);
			}
		}else{
			$this->assign('info_status_change',1);
		}

		$this->assign('orglist',$orglist);
	}
	
	function _filter(&$map) {
		$type = I('get.type', 0);
		if(!empty($type)){
			$map['ctype'] = $type;
		}
		$title = I('post.title');
		if(!empty($title)){
			$map['title'] = array('like','%'. $title .'%');
		}

		$uid = $_SESSION['lthn']['uid'];
		if(!in_array($uid,C('ADMINISTRATOR'))){ 
			if(in_array("home/info/index/limit/all", $userrule)){
			}elseif(in_array("home/info/index/limit/user", $userrule)){
				$map['uid'] = $uid;
			}else{
				$map['uid'] = $uid;
			}
		}
	}

	function _befor_insert($data){
		$active_area = $_POST['active_area'];
		$active_area = implode(',',$active_area);
		$data['active_area'] = $active_area;
		$data['status'] = 0;
		return $data;
	}

	function _befor_update($data){
		$active_area = $_POST['active_area'];
		$active_area = implode(',',$active_area);
		$data['active_area'] = $active_area;
		return $data;
	}

	function _before_edit(){
		$model = D($this->dbname);
		$id = I('get.id');
		$vo = $model->getField('type');
		$this->assign('infonavTabId', "info". $vo ."list");
	}

}