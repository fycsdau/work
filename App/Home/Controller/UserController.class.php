<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController {

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;
	}

	function _filter(&$map) {
		$title = I('post.keys');
		if(!empty($title)){
			$map['username'] = array('like','%'. $title .'%');
			$map['truename'] = array('like','%'. $title .'%');
			$map['phone'] = array('like','%'. $title .'%');
			$map['_logic'] = 'OR';
		}
	}

	public function _befor_insert($data){
		$password=md5(md5(I('pwd')));
		$data['password']=$password;
		unset($data['pwd']);
		return $data;
	}

	public function _after_add($data){
		$gcdata['uid'] = $data['id'];
		$gcdata['group_id'] = $data['depid'];
		M('auth_group_access')->data($gcdata)->add();
	}

	public function _befor_update($data){
		$id = $data['id'];
		$group_id = $data['depid'];
		M('auth_group_access')->where('uid='.$id.'')->delete();
		$gcdata['uid'] = $id;
		$gcdata['group_id'] = $group_id;
		M('auth_group_access')->data($gcdata)->add();

		if (strlen(I('pwd'))!==32){
			$password=md5(md5(I('pwd')));
			$data['password']=$password;
		}
		unset($data['pwd']);
		return $data;
	}

	public function editrule(){
		$uid=I('get.id');
		M('auth_group_access')->where('uid='.$uid.'')->delete(); 
		$gcdata['uid']=$uid;
		$gcdata['group_id']=M('auth_group')->where(array("title"=>I('get.depname')))->getField('id');
		M('auth_group_access')->data($gcdata)->add();
		$gcdata['group_id']=M('auth_group')->where(array("title"=>I('get.posname')))->getField('id');
		M('auth_group_access')->data($gcdata)->add();
		$this->mtReturn(200,"设置成功".$id,$_REQUEST['navTabId'],false); 
	}

	public function _befor_del($id){
		$uid=$id; 
		M('auth_group_access')->where('uid='.$uid.'')->delete(); 
	}
	
}