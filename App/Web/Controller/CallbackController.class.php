<?php
namespace Web\Controller;
use Think\Controller;
use Home\Controller\PublicController;
class CallbackController extends CommonController {

    public function index(){
    	$getall = I('get.');
    	$filename = 'file.txt';
		$word = "你好!\r\nwebkaka";  //双引号会换行 单引号不换行
		file_put_contents($filename, $getall);
       // $this->display();
    }
    
}
