<?php
//全局头部调用
function head(){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo '<html xmlns="http://www.w3.org/1999/xhtml"> ';
	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> ';
	echo '<link href="http://www.wxgqsc.com/templates/admin/css/admincp.css?20150430" rel="stylesheet" type="text/css" /> ';
	echo '<link href="http://www.wxgqsc.com/templates/admin/css/colpick.css?20150430" rel="stylesheet" type="text/css" /> ';
	echo '<script type="text/javascript" src="http://www.wxgqsc.com/static/js/jquery.js?20150430"></script> ';
	echo '</head> ';
	header("Content-Type: text/html;charset=utf-8"); 
}



/* 手机验证码生成函数*/
function get_mobile_code()
{
	$forbidden_num = "1989:10086:12590:1259:10010:10001:10000:";
	do
	{
		$mobile_code = substr(microtime(), 2, 6);
	}
	while (preg_match($mobile_code.':', $forbidden_num));
	return $mobile_code;
}