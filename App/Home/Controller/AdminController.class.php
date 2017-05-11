<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {

    public function _initialize(){
        
        if(!session('uid')){
            redirect(U('public/login'));
        }
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); 
    }

    public function index(){
        if(IS_POST){
            M('users')->data(I("post."))->save();
            $this->mtReturn(200,'保存成功',$_REQUEST['navTabId'],false);
        }
        $Rs=M('users')->find(session('uid'));
        $this->assign('Rs', $Rs);
        
		$yestoday_0 = date('Y-m-d 00:00:00',strtotime('-1 day'));
		$today_0 = date('Y-m-d 00:00:00',strtotime('0 day'));
		$today_24 = date('Y-m-d 24:00:00',strtotime('0 day'));
//         //昨日充值笔数
// 		$yes_map['create_time'] =  array(array('gt',$yestoday_0),array('lt',$today_0)) ;
// 		$fans_yesterday = M("Recharge")->where($yes_map)->count();

//         //今日充值笔数
//         $to_map['create_time'] =  array(array('gt',$today_0),array('lt',$today_24)) ;
//         $fans_today = M("Recharge")->where($to_map)->count();
        
        //昨日新增粉丝数
        $tomap['reg_time'] = array(array('gt',strtotime('yesterday')),array('lt',strtotime('today'))) ;
        $pointv = M('Users')->where($yesmap)->count();
        
        //今日粉丝数
        $yesmap['reg_time'] = array(array('gt',strtotime('today')),array('lt',strtotime('tomorrow'))) ;
        $point = M('Users')->where($tomap)->count();
//         //昨天发送短信
//         $yes_sms['addtime'] =  array(array('gt',$yestoday_0),array('lt',$today_0)) ;
//         $y_sms = M('Sms_log')->where($yes_sms)->count();
        
//         //今日发送短信
//         $to_sms['addtime'] =  array(array('gt',$today_0),array('lt',$today_24)) ;
//         $sms = M('Sms_log')->where($to_sms)->count();
        
        //代充值序列
//         $lottery = M("Order")->where('status = 2')->order('order_id desc')->limit(10)->select();
//         $lottery_count = M("Order")->where('status = 2')->count();
        
//         //佣金排行
//         $commission = M('Users')->order('commission desc')->limit(10)->select();
      //  print_r($commission);die;
        $this->assign('fans_yesterday',$fans_yesterday);
        $this->assign('fans_today',$fans_today);
        $this->assign('pointv',$pointv);
        $this->assign('point',$point);
        $this->assign('y_sms',$y_sms);
        $this->assign('sms',$sms);
        
        $this->assign('commission',$commission);
        $this->assign('lottery',$lottery);
        $this->assign('lottery_count',$lottery_count);
        $this->display();
    }

    protected function mtReturn($status,$info,$navTabId="",$closeCurrent=true) {
        $udata['id']=session('uid');
        $udata['update_time']=time();
        $Rs=M("users")->save($udata);
        $dat['username'] = session('username');
        $dat['content'] = $info;
        $dat['os']=$_SERVER['HTTP_USER_AGENT'];
        $dat['url'] = U();
        //$dat['addtime'] = date("Y-m-d H:i:s",time());
        $dat['ip'] = get_client_ip();
        M("log")->add($dat);


        $result = array();
        $result['statusCode'] = $status; 
        $result['message'] = $info;
        $result['tabid'] = strtolower($navTabId).'/index';
        $result['forward'] = '';
        $result['forwardConfirm']='';
        $result['closeCurrent'] =$closeCurrent;

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

}