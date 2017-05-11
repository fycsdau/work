<?php

namespace Home\Controller;
use Think\Controller;

class HelpController extends CommonController{

	public function _initialize() {
		parent::_initialize();
		$this->dbname = "info";
	}
	
	function _filter(&$map) {
		$title = I('post.title');
		if(!empty($title)){
			$map['title'] = array('like','%'. $title .'%');
		}

	}

}