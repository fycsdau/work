<?php

namespace Home\Controller;
use Think\Controller;

class JobtypeController extends CommonController{
	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;
	}
	public function index() {
		$keywords = $_REQUEST ['keywords'];
		if(!empty($keywords)){
			$map['operate_name'] = array('like','%'. $keywords .'%');
		}
		if (isset($_REQUEST ['pageCurrent'])) {
			$pageCurrent = $_REQUEST ['pageCurrent'];
		}
		if($pageCurrent=='') {
			$pageCurrent =1;
		}
		//取得满足条件的记录数
		$count = M('Jobtype')->where($map)->count();
		if ($count > 0) {
			$numPerPage=C('PERPAGE');
			$job_types = M('Jobtype')->where($map)->limit($numPerPage)->page($pageCurrent.','.$numPerPage.'')->select();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			$this->assign('list', $job_types);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);
		 
		$this->display();
	
	}
	
// 	public function _befor_add(){
// 		$list=cateTree($pid=0,$level=0,$this->dbname);
// 		print_r($list);die;
// 		$this->assign('list',$list);
// 	}
	function _filter(&$map) {
		$title = I('post.keys');
		print_r($title);die;
		if(!empty($title)){
			$map['operate_name'] = array('like','%'. $title .'%');
		}
	}
	
	//套餐id列表
	public function package_id_list(){
		$SupplierPackage   = M('SupplierPackage')->select();
		$this->assign('data',$SupplierPackage);
		$this->display();
	}
	
	//添加套餐code
	public function add_package(){
		$this->display();
	}
	
	

}