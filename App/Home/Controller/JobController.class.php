<?php

namespace Home\Controller;
use Think\Controller;

class JobController extends CommonController{
	
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
		$count = M('Job')->where($map)->count();
		if ($count > 0) {
			$numPerPage=C('PERPAGE');
			$jobs = M('Job')->order("job_id" . $sort)->limit($numPerPage)->page($pageCurrent.','.$numPerPage.'')->select();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			$this->assign('list', $jobs);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);
       
        $this->display();
	
	}
	public function info(){
		$job_id = I("get.job_id");
		$info = M("Job")->where("job_id=".$job_id)->find();
		$this->assign('info',$info);
		$job_type = M("Jobtype")->where("id=".$info['job_type'])->field("type_name")->find();
		$this->assign('job_type',$job_type['type_name']);
		$educational = $this->get_education($info['educational']);
		$experience = $this->get_experience($info['experience']);
		$this->assign('educational',$educational);
		$this->assign('experience',$experience);
		$this->display();
	}

	function get_education($educational){
		switch ($educational){
			case 0:
				$edu = "学历不限";
				break;
			case 1:
				$edu = "初中";
				break;
			case 2:
				$edu = "高中";
				break;
			case 3:
				$edu = "专科";
				break;
			case 4:
				$edu = "本科";
				break;
			case 5:
				$edu = "硕士";
				break;
			case 6:
				$edu = "博士";
				break;
			default:
				$edu = "学历不限";
				break;
		}
		return $edu;
	}
	function get_experience($experience){
		switch ($experience){
			case 0:
				$edu = "经验不限";
				break;
			case 1:
				$edu = "一年以下";
				break;
			case 2:
				$edu = "一到两年";
				break;
			case 3:
				$edu = "两到三年";
				break;
			case 4:
				$edu = "三到五年";
				break;
			case 5:
				$edu = "五年以上";
				break;
			default:
				$edu = "经验不限";
				break;
		}
		return $edu;
	}
	

}