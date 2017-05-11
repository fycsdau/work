<?php
namespace Web\Controller;
use Think\Controller;
class PublicController extends CommonController {

	public function _initialize() {
		parent::_initialize();
	}

	public function login(){

		session_start();
		if(is_wechat_browser()){	
			if(empty($_SESSION['openid'])){
				require_once(THINK_PATH.'../WeChat/lanewechat.php');
				$code = $_GET['code'];
				if(!empty($code)){
					$wechat_info = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
					$openid = $wechat_info['openid'];

					$_SESSION['openid'] = $openid;
					header("Location:".$redirect_uri); 
				}else{
					$redirect_uri = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
					\LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state=1, $scope='snsapi_base');
				}
			}else{
				$openid = $_SESSION['openid'];

				require_once(THINK_PATH.'../WeChat/lanewechat.php');
				//把关注了公众号没有会员信息的帐号同步到本地
				$user_info = \LaneWeChat\Core\UserManage::getUserInfo($openid);
				if($user_info){
					$_SESSION['openid'] = $openid;
					$_SESSION['nickname'] = $user_info["nickname"];
					$_SESSION['headimgurl'] = $user_info["headimgurl"];
				}

				$users=M('users')->where("openid='$openid'")->find();
				if(empty($users)){
					$User = M("Users");
					$result = $User->add($user_info);
					if($result){
						$insertId = $result;
					}

                	//插入用户后重新更新flag
					$user_info['flag'] = "F". $insertId;
					$user_info['level'] = "0";
					$user_info['parentid'] = "0";
					$user_info['reg_time'] = time();
				}
				$User = M("Users");
				$User->where("openid='$openid'")->save($user_info);

				$users=M('users')->where("openid='$openid'")->find();
				$_SESSION['user_id'] = $users['user_id'];
				$_SESSION['user_phone'] = $users['phone'];
			}
			$this->assign('is_wechat_browser', 1);
			redirect('/'.$last_id, 0, '正在跳转到首页');
        die();
		}

		if(IS_POST){ 
			$admin = I('post.');
			$rs = D('Admin', 'Service')->login($admin);
			if (!$rs['status']) {
				$this->error($rs['data']);
			}
		//Redirect("http://".$_SERVER['SERVER_NAME'].__ROOT__);
			$this->success('登录成功，正在跳转...',__ROOT__,1);
		}
		else{
			$this->display();
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
	
	public function logout() {
		$dat['username'] = session('username');
        $dat['content'] = '退出成功！';
        $dat['os']=$_SERVER['HTTP_USER_AGENT'];
        $dat['url'] = U();
        $dat['addtime'] = date("Y-m-d H:i:s",time());
        $dat['ip'] = get_client_ip();
        M("log")->add($dat);
        session_unset();
        session_destroy();
		$this->redirect('/index');
	}

	public function changepwd() {
		if(IS_POST){
			$password=I('post.password');
			$map = array();
			if(I('post.password')!=I('post.repassword'))
			{
				$data['statusCode']=300;
				$data['message']='两次输入密码不一致！';
			}
			$map['password'] = md5(md5((I('post.oldpassword'))));
			$map['id'] = session('uid');
			$User = M("User");
			if (!$User->where($map)->field('id')->find()) {

				$data['statusCode']=300;
				$data['message']='旧密码不符或者用户名错误！';

			} else {
				if (empty($password) || strlen($password) < 5) {
					$data['statusCode']=300;
					$data['message']='密码长度必须大于6个字符！';

				}else{
					$User->password =md5(md5(($password)));
					$User->save();
					$data['statusCode']=200;
					$data['message']='密码修改成功！';
					$data['tabid']=$_REQUEST['navTabId'];
					$data['closeCurrent']='true';
					$data['forward']='';
					$data['forwardConfirm']='';
				}

			}
			$this->dwzajaxReturn($data);
		}else{
			$this->display();	
		}
	}
	
	public function changeinfo() {
		$id=session('uid');
		$rs=M('user')->find($id);
		if(IS_POST){
			M('user')->save(I('post.'));
			$data['statusCode']=200;
			$data['message']='资料修改成功！';
			$data['tabid']=$_REQUEST['navTabId'];
			$data['closeCurrent']='true';
			$data['forward']='';
			$this->dwzajaxReturn($data);
		}else{
			$this->assign('id', $id);
			$this->assign('Rs', $rs);
			$this->display();	
		}
	}
	
	protected function dwzajaxReturn($data, $type='') {

		$udata['id']=session('uid');
		$udata['update_time']=time();
		$Rs=M("user")->save($udata);
		$dat['username'] = session('username');
		$dat['content'] = $data['message'];
		$dat['os']=$_SERVER['HTTP_USER_AGENT'];
		$dat['url'] = U();
		$dat['addtime'] = date("Y-m-d H:i:s",time());
		$dat['ip'] = get_client_ip();
		M("log")->add($dat);

		$result = array();
		$result['statusCode'] = $data['statusCode'];
		$result['tabid'] = $data['tabid'];
		$result['closeCurrent'] = $data['closeCurrent'];
		$result['message'] = $data['message'];
		$result['forward'] = $data['forward'];
		$result['forwardConfirm']=$data['forwardConfirm'];

		if (empty($type))
			$type = C('DEFAULT_AJAX_RETURN');
		if (strtoupper($type) == 'JSON') {
            // 返回JSON数据格式到客户端 包含状态信息
			header("Content-Type:text/html; charset=utf-8");
			exit(json_encode($result));
		} elseif (strtoupper($type) == 'XML') {
            // 返回xml格式数据
			header("Content-Type:text/xml; charset=utf-8");
			exit(xml_encode($result));
		} elseif (strtoupper($type) == 'EVAL') {
            // 返回可执行的js脚本
			header("Content-Type:text/html; charset=utf-8");
			exit($data);
		} else {
            // TODO 增加其它格式
		}
	}


	public function upload(){
		$upload = new \Think\Upload();
		$upload->maxSize   =     C('UPLOAD_MAXSIZE') ;
		$upload->exts      =     C('UPLOAD_EXTS');
		$upload->savePath  =     C('UPLOAD_SAVEPATH');
		$info   =  $upload->upload(); 
		$gourl = 'index.php/home/public/attfile/attid/'.I('attid').'/'; 
		if(!$info) {
			echo "<script language='javascript' type='text/javascript'>"; 
			echo "alert('上传失败！$upload->getError()');";
			echo "window.location.href='$gourl'"; 
			echo "</script>";  			 
	   //$this->error($upload->getError());    
		}else{     
	   //dump($info);
			$data['attid']=I('attid');
			$data['folder']='Uploads/'.str_replace('./','',$info["filename"]["savepath"]);
			$data['filename']=$info["filename"]["savename"];
			$data['filetype']=$info["filename"]["ext"];
			$data['filedesc']=$info["filename"]["name"];
			$data['uid']=session('uid');
			$data['addtime']=date("Y-m-d H:i:s",time());
	   //dump($data);
			M('files')->data($data)->add();
			$filename=$info["filename"]["name"];
			echo "<script language='javascript' type='text/javascript'>"; 
			echo "alert('文件$filename 上传成功');";
			echo "window.location.href='$gourl'"; 
			echo "</script>";  
		}
	}
	

}