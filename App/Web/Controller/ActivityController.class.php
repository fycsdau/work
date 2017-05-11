<?php
namespace Web\Controller;
use Think\Controller;
class ActivityController extends CommonController {

    public function _initialize(){
        parent::_initialize();
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config); 
        $this->assign('page_title',"优惠活动");
    }

    public function index(){
        $area = I('get.area',0);
        $this->assign('area',$area);
        if(!empty($area)){
            $map['_string'] = "active_area='$area' or instr(active_area,'".$area."')";
        }
        

        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);
        $map['type'] = 2;
        $map['status'] = 1;
        $list = M('info')->where($map)->order('id desc')->page($p .','. $page_num)->select();

        $numcount = M('info')->where($map)->count();
        $pagecount = intval($numcount / $page_num);
        if ($numcount%$page_num){$pagecount++;}
        
        $loadmore = '<ul class="am-pagination">';
        $loadmore .= '   <li class="am-pagination-prev">';
        if($p>1){
            $prep = $p-1;
            $loadmore .= '   <a href="'.__CONTROLLER__.'/index/p/'.$prep.'">上一页</a></li>';
        }else{
            $loadmore .= '   <a class="noloadtips" href="javascript:;">上一页</a></li>';
        }
        
        $loadmore .= '   </li>';
        if($p==0){ $p = 1; }
        $loadmore .= '   <li><a>第 '.$p.' / '.$pagecount.' 页</a></li>';
        $loadmore .= '   <li class="am-pagination-next">';
        if($p<$pagecount){
            $nextp = $p+1; 
            $loadmore .= '   <a href="'.__CONTROLLER__.'/index/p/'.$nextp.'">下一页</a></li>';
        }else{
            $loadmore .= '   <a class="noloadtips" href="javascript:;">下一页</a></li>';
        }
        $loadmore .= '   </li>';
        $loadmore .= '</ul>';

        foreach ($list as $key => $value) {
            $value = $value['value'];
            $value = str_replace("&amp;","",$value);
            $value = str_replace("emsp;","",$value);
            $value = str_replace("&lt;","<",$value);
            $value = str_replace("&gt;",">",$value);
            $value = str_replace("&quot;","'",$value);
            $value = str_replace("\r\n","",$value);
            $value = str_replace("\t","",$value);
            $toppic = getImgs($value,0);
            $value = strip_tags($value);
            $list[$key]['desc'] = mb_substr($value, 0, 100);
            $list[$key]['toppic'] = $toppic;
        }

        $this->assign('list',$list);
        if($numcount){
            $this->assign('loadmore',$loadmore);
        }

        $orglist=orgcateTree($pid=1,$level=0,$type=0);
        $this->assign('orglist',$orglist);

        $this->display();
    }

    public function view() {
        $model = D('info');
        $id = I('get.id',0);
        $map['type']=2;
        if($id){
            $map['id']=$id;
        }
        $vo = M('info')->where($map)->order('id desc')->find();
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
            $value = strip_tags($value);
            $vo['desc'] = mb_substr($value, 0, 100);
        }

        $this->assign('Rs', $vo);
        $this->display();
    }

}