<?php
namespace Web\Controller;
use Think\Controller;
class AboutController extends CommonController {

    public function _initialize(){
        parent::_initialize();
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config);
        }
        C($config);
        $this->assign('page_title',"乐透河南");
    }

    public function index(){
        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);
        $map['type'] = 3;
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
        $this->assign('loadmore',$loadmore);

        $this->display();
    }

    public function hotline() {
        $model = D('info');
        $map['type']=5;
        $map['id']=15;
        $vo = M('info')->field('value')->where($map)->order('id desc')->find();
        if($vo){
            $value = $vo['value'];
            $value = str_replace("&amp;","",$value);
            $value = str_replace("nbsp;","",$value);
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

        $orglist = M('auth_group')->where('type=0 and pid=1')->order('sort asc')->select();
        $this->assign('orglist', $orglist);

        $this->assign('Rs', $vo);
        $this->display();
    }

}