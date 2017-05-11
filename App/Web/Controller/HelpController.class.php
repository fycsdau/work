<?php
namespace Web\Controller;
use Think\Controller;
class HelpController extends CommonController {

	public function _initialize() {
		parent::_initialize();
		$this->assign('page_title', '帮助');
		$this->dbname = 'info';
	}
	
}