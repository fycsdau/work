<?php

namespace Home\Controller;
use Think\Controller;

class PrizeController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;

		$model = D("prize_type");
		$type = $model->select();
		$this->assign('type', $type);
	}

	//分类列表
	public function type(){
		$model = D("prize_type");

		$title = $_REQUEST ['title'];
		if(!empty($title)){
			$map['title'] = array('like','%'. $title .'%');
		}

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

	//分类添加
	public function typeadd(){
		$model = D("prize_type");
		if(IS_POST){
			$data=I('post.');
			if (false === $data = $model->create()) {
				$this->mtReturn(300,'失败，请检查值是否已经存在',$_REQUEST['navTabId'],true);  
			}
			if($model->add($data)){
				$id = $model->getLastInsID();
				$loginfo = $_REQUEST['navTabId'].' 新增 '.$id;
				$this->mtReturn(200, "添加成功", $_REQUEST['navTabId'], true, $loginfo);  
			}

		}

		$sortcount = $model->count();
		$this->assign('sortcount',$sortcount+1);
		$this->display();
	}

	//分类修改
	public function typeedit(){
		$model = D("prize_type");
		if(IS_POST){
			$data=I('post.');
			if (false === $data = $model->create()) {
				$this->mtReturn(300,'失败，请检查值是否已经存在',$_REQUEST['navTabId'],true);  
			}
			if($model->save($data)){
			}	
			$id = $data['id'];
			$loginfo = $_REQUEST['navTabId'].' 编辑 '.$id;
			$this->mtReturn(200, "编辑成功", $_REQUEST['navTabId'], true, $loginfo); 	  
		}
		
		$id = $_REQUEST [$model->getPk()];
		$vo = $model->getById($id);
		$this->assign('id',$id);
		$this->assign('Rs', $vo);
		$this->display();
	}

	//分类删除
	public function typedel(){
		$model = D("prize_type");
		$id = I('get.id');
		if($id){
			$model1 = D("prize");
			$vo = $model1->getById($id);
			$loginfo = $_REQUEST['navTabId'].' 删除 '.$id;
			if($vo){
				$this->mtReturn(300, '删除失败，分类正在使用中', $_REQUEST['navTabId'], false, $loginfo);
			}
			$model->where("id='$id'")->delete();
			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
		}
	}

	//奖品领取记录
	public function receive(){
		$prize = M("prize")->field('id,title')->select();
		$this->assign('prize', $prize);

		$model = D("prize_receive");

		$user_name = $_REQUEST['user_name'];
		if(!empty($user_name)){
			$map['user_name'] = array('like','%'. $user_name .'%');
		}

		$prize = $_REQUEST['prize'];
		if(!empty($prize)){
			$map['prize_id'] = $prize;
		}
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