<?php

namespace Home\Controller;
use Think\Controller;

class VerifController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = "coupon";

		//奖品列表
		$prize = M("prize")->field('id,title')->select();
		$this->assign('prize', $prize);
	}
	
	function _filter(&$map) {
		$tickets = I('post.tickets');
		if(!empty($tickets)){
			$map['tickets'] = array('like','%'. $tickets .'%');
		}
		$prize = I('post.prize');
		if(!empty($prize)){
			$map['prize_id'] = $prize;
		}
		$status = I('post.status');
		if($status != -1){
			$map['status'] = $status;
		}
	}

	function _before_edit(){
		$model = D($this->dbname);
		$id = I('get.id');
		$vo = $model->getField('type');
		$this->assign('infonavTabId', "info". $vo ."list");
	}

	function verifed(){
		$model = D("prize_receive");

		$tickets = I('post.tickets');
		if(!empty($tickets)){
			$map['tickets'] = array('like','%'. $tickets .'%');
		}
		$prize = I('post.prize');
		if(!empty($prize)){
			$map['prize_id'] = $prize;
		}
		$map['status'] = 1;

		if (isset($_REQUEST ['pageCurrent'])) {
			$pageCurrent = $_REQUEST ['pageCurrent'];
		}
		if($pageCurrent=='') {
			$pageCurrent =1;
		}
		if($order=='') {
			$order = $model->getPk();
		}
		
		//取得满足条件的记录数
		$count = $model->where($map)->count();
		if ($count > 0) {

			$numPerPage=C('PERPAGE');
			$voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($numPerPage)->page($pageCurrent.','.$numPerPage.'')->select();
			
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			
			$this->assign('list', $voList);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);

		$this->display();
	}

}