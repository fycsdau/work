<?php
namespace Pcshow\Controller;
use Think\Controller;
class LoginController extends CommonController {

    public function _initialize(){
        $config =   S('DB_CONFIG_DATA');

        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); 
    }


    public function index(){
        $this->display();
    }
    public function register(){
    	$this->display();
    }
    
    //验证用户名是否可用
    public function name_can_use(){
    	$name = $_POST['name'];
    	$user_name=M('User')->where(array("username"=>$name))->getField('id');
    	if ($user_name){
    		echo 0;die;
    	}else{
    		echo 1;die;
    	}
    }
    //添加用户
    public function add_user(){
    	$data['username'] = I("post.user_name");
    	$data['password'] = I("post.password");
    	$data['add_time'] = date("Y-m-d H:i:s",time());
    	$data['truename'] = I("post.user_name");
    	
    	$where['username'] = $data['username'];
    	$user_info = M("User")->where($where)->select();
    	if(empty($user_info)){
	    	$lastInsId = M("User")->add($data);
	    	$_SESSION['user_id'] = $lastInsId;
	    	$coo = new CommonController;
	    	$coo->update_user();
	    	$this->redirect('Index/index');
    	}else{
    		$this->error("注册失败，请重试！","register");
    	}
    }
    //登录
    public function login(){
    	$data['username'] = I("post.username");
    	$data['password'] = I("post.password");
    	
    	$where['username'] = $data['username'];
    	$where['password'] = $data['password'];
    	$user_info = M("User")->where($where)->find();
    	if(!empty($user_info)){
	    	$_SESSION['user_id'] = $user_info['id'];
	    	$coo = new CommonController;
	    	$coo->update_user();
	    	$this->redirect('Index/index');
    	}else{
    		$this->error("登录失败，请重试！","index");
    	}
    }
    public function verify(){
		ob_clean();
		$config =    array(
			'fontSize'    =>    20,    // 验证码字体大小
		    'length'      =>    4,     // 验证码位数
		    'imageH'	  => 	35,
		    'useNoise'    =>    false, // 关闭验证码杂点
		    );
		$verify = new \Think\Verify($config);
		$verify->codeSet = '0123456789';
		$verify->entry();
	}
	
 	public function phpinfo(){
		print_r($_SESSION);die;
	}
}