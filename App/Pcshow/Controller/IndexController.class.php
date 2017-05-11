<?php
namespace Pcshow\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function _initialize(){
        parent::_initialize();
    }

    public function index(){
    	$more = I("get.more");
    	$map['status'] = 1;
    	$map['use_status'] = 1;
    	$sql_input = I("post.sel_input");
    	if($sql_input){
    		$map['job_name'] = array('like',"%$sql_input%");
    	}
    	if($more){
    		if(!empty($_SESSION['user_id'])){
		    	$job_list = M('Job')->where($map)->select();
    		}else{
    			$this->error("请登录！","Login/login");
    		}
    	}else{
    		$job_list = M('Job')->where($map)->limit(8)->select();
    	}
    	$this->assign('sql_input', "$sql_input");
    	$this->assign('from_action', "job_index");
    	$this->assign('job_list', $job_list);//数据总数
        $this->display();
    }
    
    public function job_info(){
    	$job_id = I("get.job_id");
    	$map['job_id'] = $job_id;
    	$job_info = M('Job')->where($map)->find();
    	//更新浏览次数
    	if($job_info){
    		$data['browse_count'] = $job_info['browse_count'] + 1;
    		M('Job')->where($map)->save($data);
	    	//boss联系方式
	    	$boss_info = M('Boss')->where("id=1")->find();
	      	$this->assign('boss_info', $boss_info);//联系方式
	      	//高需求工作
	      	$high_demand_job = M("Jobtype")->order("now_count desc")->limit('0,5')->select();
	      	$max = $high_demand_job[0]['now_count'];
	      	$l_max = (intval($max / 10 ) + 1) * 10;
	      	foreach($high_demand_job as $key=>$item){
	      		if($item['now_count']){
		      		$high_demand_job[$key]['now_count_rate'] = $item['now_count'] / $l_max * 100;
	      		}else{
	      			unset($high_demand_job[$key]);
	      		}
	      	}
	      	$this->assign('high_demand_job', $high_demand_job);
	      	//相关工作
	      	$con_job = M("Job")->where("job_type=".$job_info['job_type'])->limit(4)->select();
	      	$this->assign('con_job', $con_job);
	      	//类型名称
	      	$type_name = M(Jobtype)->where("id=".$job_info['job_type'])->field('type_name')->find();
	      	$this->assign('type_name', $type_name);
	      	
	      	$job_content = htmlspecialchars_decode($job_info['content']);
	      	$this->assign('job_content', $job_content);
	      	
	      	//工作类型
	      	$job_type = M("Jobtype")->where("id=".$job_info['job_type'])->find();
    		$job_info['job_type_name'] = $job_type_info['type_name'];
	      	$this->assign('job_info', $job_info);//工作详情
    	}
        $this->display();
    }
    //添加工作页面
    public function add_job(){
    	if(!empty($_SESSION['user_id'])){
	    	$job_list = M('Job')->where($map)->select();
	    	$this->assign('haveimage', 0);
		}else{
			$this->error("请登录！","../Login/index");
		}
		$this->display();
    }
    //添加工作保存
    public function save_job(){
    	if(empty($_SESSION['user_id'])){
    		$this->error("请登录！","../Login/index");
    	}
    	//工作类型
    	$job_type = I("post.job_type");
    	$job_type_info = M("Jobtype")->where("type_name='$job_type'")->select();
    	$job_type_id = $job_type_info['id'];
    	if(empty($job_type_id)){
    		$job_types['type_name'] = $job_type;
    		$job_types['history_count'] = 0;
    		$job_types['now_count'] = 0;
    		$job_types['status'] = 1;
    		$job_type_id = M("Jobtype")->add($job_types);
    	}
    	
    	$job_id = I("post.job_id");
    	$data['user_id'] = $_SESSION['user_id'];
    	$data['company'] = I("post.company_name");
		$data['job_name'] = I("post.job_name");
		$data['job_type'] = $job_type_id;
		$data['desc'] = I("post.job_desc");
		$data['con_name'] = I("post.con_name");
		$data['mobile'] = I("post.con_mobile");
		$data['salary'] = I("post.job_money");
		$data['content'] = I("post.job_content");
		$data['educational'] = I("post.educational");
		$data['experience'] = I("post.experience");
		$data['need_count'] = I("post.job_count");
		$data['address'] = I("post.job_address");
		$data['img'] = I("post.job_image_input");
		$data['use_status'] = I("post.use_status");
		$data['add_time'] = date("Y-m-d H:i:s",time());
    	if(empty($job_id)){
			$job_id = M('Job')->add($data);
	    	//更新类表数量
	    	$type['history_count'] = $job_type_info['history_count'] + 1;
	    	$type['now_count'] = $job_type_info['now_count'] + 1;
	    	$job_type_info = M("Jobtype")->where("id='$job_type_id'")->save($type);
    	}else{
    		$job_id = M('Job')->where("job_id=$job_id")->save($data);
    	}
    	
    	$this->redirect('Index/myjob');
    }
    //我的招聘
    public function myjob(){
    	if(empty($_SESSION['user_id'])){
    		$this->error("请登录！","../Login/index");
    	}else{
    		$map['user_id'] = $_SESSION['user_id'];
    		$job_list = M('Job')->where($map)->select();
    	}
    	$this->assign('job_list', $job_list);//数据总数
    	$this->display();
    }
    
    public function myjob_info(){
    	$job_id = I("get.job_id");
    	$map['job_id'] = $job_id;
    	$job_info = M('Job')->where($map)->find();
    	$this->assign('job_info', $job_info);
    	if($job_info['img']){
    		$imgarr = explode(",", $job_info['img']);
    	}
    	$this->assign('job_img', $imgarr);
    	$job_content = htmlspecialchars_decode($job_info['content']);
    	$this->assign('job_content', $job_content);
    	$this->assign('haveimage', count($imgarr));
    	$this->display();
    }
    //上传图片
    public function upload(){
    	$base64_image_content = I("post.base64Img");
    	$date = date("Ym",time());
    	$path = C(APP_PUBLIC_PATH).$date;
    	
    	$result = $this->saveBase64Image($base64_image_content);
    	echo $result;die;
    	
    }
    
    /**
     * 保存64位编码图片
     */
    
    function saveBase64Image($base64_image_content){
    
    	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
    		//图片后缀
    		$type = $result[2];
    		//保存位置--图片名
    		$image_name=date('YmdHis',time()).str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".".$type;
    		$image_url = 'Uploads/image/';
    		
    		//解码
    		$decode=base64_decode(str_replace($result[1], '', $base64_image_content));
    		if (file_put_contents($image_url.$image_name, $decode)){
    			$data['result']=1;
    			$data['imageName']=$image_name;
    			$data['url']=$image_url;
    			$data['msg']='保存成功！';
    		}else{
    			$data['result']=0;
    			$data['imgageName']='';
    			$data['url']='';
    			$data['msg']='图片保存失败！';
    		}
    	}else{
    		$data['result']=0;
    		$data['imgageName']='';
    		$data['url']='';
    		$data['msg']='base64图片格式有误！';
    	}
    	return json_encode($data,true);
    
    
    }

}