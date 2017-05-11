<?php

namespace Home\Controller;
use Think\Controller;

class SmsController extends CommonController{

	public function config(){
		$model = D("sms_config");
		$config = $model->find('1');
		$this->assign('config',$config);
		$this->assign('id',$config['id']);
		$this->display();
	}

	//保存微信接口配置
	public function savecongfig(){
		$model = D("sms_config");

		$data=I('post.');
		$model->save($data);
		$id = $data['id'];
		$loginfo = $_REQUEST['navTabId'].' 编辑 '.$id;

		$this->mtReturn(200, "保存成功", $_REQUEST['navTabId'], false, $loginfo);
	}

	//短信发送日志
	public function log(){

		$model = D("sms_log");

		$phone = $_REQUEST['phone'];
		if(!empty($phone)){
			$map['phone'] = array('like','%'. $phone .'%');
		}

		$status = $_REQUEST['status'];
		if(!empty($status)){
			$map['status'] = $status;
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
		$numPerPage=C('PERPAGE');
		$count = $model->where($map)->count();
		if ($count > 0) {

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