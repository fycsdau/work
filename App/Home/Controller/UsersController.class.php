<?php
namespace Home\Controller;
use Think\Controller;
class UsersController extends CommonController {

	public function _initialize() {
		parent::_initialize();
		$this->dbname = CONTROLLER_NAME;
	}

	function _filter(&$map) {
		$title = I('post.keys');
		if(!empty($title)){
			$map['nickname'] = array('like','%'. $title .'%');
// 			$map['phone'] = array('like','%'. $title .'%');
			$map['_logic'] = 'OR';
		}
	}
	//用户列表
	public function index() {
		$keywords = $_REQUEST ['keys'];
		if(!empty($keywords)){
			$map['nickname'] = array('like','%'. $keywords .'%');
		}
		
		if (isset($_REQUEST ['pageCurrent'])) {
			$pageCurrent = $_REQUEST ['pageCurrent'];
		}
		if($pageCurrent=='') {
			$pageCurrent =1;
		}
		if (isset($_REQUEST ['orderField'])) {
			$order = $_REQUEST ['orderField'];
		}
		if($order=='') {
			$order = M('Users')->getPk();
		}
		if (isset($_REQUEST ['orderDirection'])) {
			$sort = $_REQUEST ['orderDirection'];
		}
		if($sort=='') {
			$sort = $asc ? 'asc' : 'desc';
		}
		
		$count = M('Users')->where($map)->count();
		
		if ($count > 0) {
			$numPerPage=C('PERPAGE');
			$users = M('Users')->where($map)->order("`" . $order . "` " . $sort)->limit($numPerPage)->page($pageCurrent.','.$numPerPage.'')->select();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			$this->assign('list', $users);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);
		
		$this->display();
	}

// 	//删除会员
// 	public function del(){
// 		$id = I('get.id');
// 		if($id){
// 			$loginfo = $_REQUEST['navTabId'].' 删除 '.$id;
// 			M('Users')->where("user_id='$id'")->delete();
// 			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
// 		}
// 	}
	
	
}