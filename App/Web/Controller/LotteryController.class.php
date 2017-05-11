<?php
namespace Web\Controller;
use Think\Controller;
class LotteryController extends CommonController {

    public function _initialize(){
        parent::_initialize();
        $this->assign('page_title',"开奖信息");
    }

    public function index(){
        //大乐透
        $lottery_dlt = M('info')->where("type=1 and playname='大乐透'")->field('id,title,addtime,code,numbers,playname')->order('id desc')->find();
        $this->assign('lottery_dlt',$lottery_dlt);
        
        //排列3
        $lottery_pl3 = M('info')->where("type=1 and playname='排列3'")->field('id,title,addtime,code,numbers,playname')->order('id desc')->find();
        $this->assign('lottery_pl3',$lottery_pl3);
        
        //排列5
        $lottery_pl5 = M('info')->where("type=1 and playname='排列5'")->field('id,title,addtime,code,numbers,playname')->order('id desc')->find();
        $this->assign('lottery_pl5',$lottery_pl5);
        
        //七星彩
        $lottery_qxc = M('info')->where("type=1 and playname='7星彩'")->field('id,title,addtime,code,numbers,playname')->order('id desc')->find();
        $this->assign('lottery_qxc',$lottery_qxc);

        $this->display();
    }

    public function lists(){
        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);
        $playname = I('get.playname','大乐透');
        $this->assign('playname',$playname);

        $map['type'] = 1;
        $map['playname'] = $playname;
        $list = M('info')->where($map)->order('id desc')->page($p .','. $page_num)->select();

        $numcount = M('info')->where($map)->count();
        $pagecount = intval($numcount / $page_num);
        if ($numcount%$page_num){$pagecount++;}
        
        $loadmore = '<ul class="am-pagination">';
        $loadmore .= '   <li class="am-pagination-prev">';
        if($p>1){
            $prep = $p-1;
            $loadmore .= '   <a href="'.__CONTROLLER__.'/lists/playname/'.$playname.'/p/'.$prep.'">上一页</a></li>';
        }else{
            $loadmore .= '   <a class="noloadtips" href="javascript:;">上一页</a></li>';
        }
        
        $loadmore .= '   </li>';
        if($p==0){ $p = 1; }
        $loadmore .= '   <li><a>第 '.$p.' / '.$pagecount.' 页</a></li>';
        $loadmore .= '   <li class="am-pagination-next">';
        if($p<$pagecount){
            $nextp = $p+1; 
            $loadmore .= '   <a href="'.__CONTROLLER__.'/lists/playname/'.$playname.'/p/'.$nextp.'">下一页</a></li>';
        }else{
            $loadmore .= '   <a class="noloadtips" href="javascript:;">下一页</a></li>';
        }
        $loadmore .= '   </li>';
        $loadmore .= '</ul>';

        $this->assign('list',$list);
        $this->assign('loadmore',$loadmore);

        $this->display();
    }

    public function view() {
        $model = D('info');
        $id = I('get.id',0);
        $map['type']=1;
        if($id){
            $map['id']=$id;
        }
        $vo = M('info')->field('id,title,addtime,code,numbers,value,playname')->where($map)->order('id desc')->find();
        if($vo){
            $value = $vo['value'];
            $value = str_replace("&amp;","",$value);
            $value = str_replace("emsp;","",$value);
            $value = str_replace("&lt;","<",$value);
            $value = str_replace("&gt;",">",$value);
            $value = str_replace("&quot;","'",$value);
            $value = str_replace("\r\n","",$value);
            $value = str_replace("\t","",$value);
            $value = str_replace("<table","<div class='am-scrollable-horizontal'><table",$value);
            $value = str_replace("</table>","</table></div>",$value);
            $vo['value'] = $value;

            $playname = $vo['playname'];
            $this->assign('playname',$playname);
        }

        $this->assign('Rs', $vo);
        $this->display();
    }

}