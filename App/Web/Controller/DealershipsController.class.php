<?php
namespace Web\Controller;
use Think\Controller;
class DealershipsController extends CommonController {

    public function _initialize(){
        parent::_initialize();
        $this->assign('page_title',"认证网点");

        $model = D("wechat_config");
        $wechat = $model->find('1');

        $jssdk = new JSSDK($wechat['appid'], $wechat['appsecret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
    }

    public function index(){
        $user_city = $_SESSION['user_city'];
        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);
        $map['status'] = 1;
        //$map['parentid'] = $user_city;
        $list = M('point')->where($map)->order('id desc')->page($p .','. $page_num)->select();

        foreach ($list as $key => $value) {
            if(!empty($value['position'])){
                $positions = explode(", ", $value['position']);
                $lng = $positions[1];
                $lat = $positions[0];
                $list[$key]['lng'] = $lng;
                $list[$key]['lat'] = $lat;
                $list[$key]['showmaplink'] = 1;
            }
        }

        $numcount = M('point')->where($map)->count();
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

        $this->assign('list',$list);
        if($numcount>0){
            $this->assign('loadmore',$loadmore);
        }

        $this->display();
    }

    public function verif(){
        $user_city = $_SESSION['user_city'];
        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);
        $status = I('get.status', -1);
        if($status != -1){
            $map['status'] = $status;
            if($status){
                $statusname = " - 已认证";
            }else{
                $statusname = " - 待认证";
            }
            $this->assign('statusname',$statusname);
        }

        if($user_city){
            if($status){
                $no_verif_count = M('point')->where("parentid='$user_city' and status=0")->count();
                $this->assign('verif_count','<a href="/index.php/Dealerships/verif/status/0">待认证网点( '. $no_verif_count .' )</a>');
            }else{
                $no_verif_count = M('point')->where("parentid='$user_city' and status=1")->count();
                $this->assign('verif_count','<a href="/index.php/Dealerships/verif/status/1">已认证网点( '. $no_verif_count .' )</a>');
            }
        }

        $map['parentid'] = $user_city;
        $list = M('point')->where($map)->order('id desc')->page($p .','. $page_num)->select();

        $numcount = M('point')->where($map)->count();
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

        $this->assign('list',$list);
        if($numcount>0){
            $this->assign('loadmore',$loadmore);
        }

        $this->display();
    }

    public function view() {
        $model = D('point');
        $id = I('get.id',0);
        if($id){
            $map['id']=$id;
        }
        $vo = M('point')->where($map)->order('id desc')->find();

        //当前用户的身份判断(当前用户为中心管理员user_auth=3,用户所在部门为网点上级部门)
        $user_auth = $_SESSION['user_auth'];
        $user_city = $_SESSION['user_city'];
        if($user_auth=='3' && $vo['parentid']==$user_city){
            $this->assign('show_action', 1);
        }
        if(!empty($vo['position'])){
            $positions = explode(",", $vo['position']);
            $lng = $positions[1];
            $lat = $positions[0];

            $this->assign('lng', $lng);
            $this->assign('lat', $lat);
            $this->assign('showmaplink', 1);
        }
        $this->assign('Rs', $vo);

        $this->display();
    }

    public function verifsubmit(){
        if(IS_POST){
            $model = D('point');
            $data=I('post.');
            $d = time();
            $ctime = date("Y-m-d H:i:s", $d);
            $data['adddate'] = $ctime;
            $data['adduser'] = $_SESSION['user_name'];
            $data['adduserid'] = $_SESSION['user_id'];
            $data['status']= 0;

            //如果记录中已经存在对应的名称，则直接跳转到对应记录
            $map['title'] = $data['title'];
            $map['adduserid'] = $data['adduserid'];
            $list = M('point')->where($map)->order('id desc')->select();
            if(!empty($list)){
                redirect('/index.php/Dealerships/view/id/'.$list['id'], 0, '页面跳转中...');
                die();
            }
            if($model->add($data)){
                $id = $model->getLastInsID();
                redirect('/index.php/Dealerships/view/id/'.$id, 0, '页面跳转中...');
                die();
            }
        }

        $this->assign('page_title',"网点认证申请");
        //如果是中心管理员，提示
        $user_auth = $_SESSION['user_auth'];
        if($user_auth==3){
            $this->display('error');
        }

        $user_id = $_SESSION['user_id'];
        //如果当前用户有申请记录，则显示申请详情和进度，否则显示申请提交界面
        $map['adduserid'] = $user_id;
        $list = M('point')->where($map)->order('id desc')->select();
        if(!empty($list)){
            redirect('/index.php/Dealerships/view/id/'.$list['id'], 0, '页面跳转中...');
            die();
        }
        $orglist=orgcateTree($pid=1,$level=0,$type=0);
        $this->assign('orglist',$orglist);

        $this->display();
    }

    public function verif_update(){
        $id = I('get.id',0);
        if($id){
            $map['id']=$id;
            $vo = M('point')->where($map)->order('id desc')->find();
            if($vo['status']==1){
                $data['status']=0;
                $data['verifdate'] = null;
                $data['verifuser'] = null;
                $data['verifuserid'] = null;
            }else{
                $d = time();
                $ctime = date("Y-m-d H:i:s", $d);
                $data['status']=1;
                $data['verifdate'] = $ctime;
                $data['verifuser'] = session('truename')."(". session('username') .")";
                $data['verifuserid'] = session('uid');
            }
            M('point')->where("id='$id'")->save($data);
        }
        echo "<script>window.location.href='". __APP__."/Dealerships/view/id/". $id ."/'</script>";
        die();
    }

    public function near(){
        $page_num = C('USER_PERPAGE');
        $p = I('get.p',0);

        $map['status'] = 1;
        $latitude = I('get.latitude',0);
        $longitude = I('get.longitude',0);

        if(!empty($latitude) || !empty($longitude)){
            $squares = $this->returnSquarePoint($longitude, $latitude);

            $map['lat'] = array(array('egt',$squares['right-bottom']['lat']),array('elt',$squares['left-top']['lat']));

            $map['lng'] = array(array('egt',$squares['left-top']['lng']), array('elt',$squares['right-bottom']['lng']));

            $list = M('point')->where($map)->order('id desc')->page($p .','. $page_num)->select();

            $numcount = M('point')->where($map)->count();
            $pagecount = intval($numcount / $page_num);
            if ($numcount%$page_num){$pagecount++;}

            $loadmore = '<ul class="am-pagination">';
            $loadmore .= '   <li class="am-pagination-prev">';
            if($p>1){
                $prep = $p-1;
                $loadmore .= '   <a href="javascript:mappage('.$latitude.', '.$longitude.', '.$prep.')">上一页</a></li>';
            }else{
                $loadmore .= '   <a class="noloadtips" href="javascript:;">上一页</a></li>';
            }

            $loadmore .= '   </li>';
            if($p==0){ $p = 1; }
            $loadmore .= '   <li><a>第 '.$p.' / '.$pagecount.' 页</a></li>';
            $loadmore .= '   <li class="am-pagination-next">';
            if($p<$pagecount){
                $nextp = $p+1; 
                $loadmore .= '   <a href="javascript:mappage('.$latitude.', '.$longitude.', '.$nextp.')">下一页</a></li>';
            }else{
                $loadmore .= '   <a class="noloadtips" href="javascript:;">下一页</a></li>';
            }
            $loadmore .= '   </li>';
            $loadmore .= '</ul>';

            if(!empty($list)){
                foreach ($list as $key => $value) {
                    echo '<li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-bottom-right">';
                    echo '        <a href="/index.php/Dealerships/view/id/'. $value['id'] .'/">';
                    echo '            <div class="am-list-item-hd am-text-danger"><strong>'. $value['title'] .'</strong></div>';
                    echo '            <div class="am-list-main">';
                    echo '                '. $value['addres'] .'<br>';
                    if(!empty($value['tel'])){
                        echo $value['tel'] . " / ";
                    }
                    if(!empty($value['phone'])){
                        echo $value['phone'];
                    }

                    echo '            </div>';
                    echo '        </a>';
                    if(!empty($value['position'])){
                        $positions = explode(", ", $value['position']);
                        $lng = $positions[1];
                        $lat = $positions[0];

                        echo '            <hr style="margin:1rem 0rem;">';
                        echo '            <div class="am-cf am-text-sm">';

                        $Distance = $this->getDistance($lat, $lng, $latitude, $longitude);

                        echo '                <span class="am-fl">距离您约 '. $Distance .' 米</span>';
                        echo '                <span class="am-fr am-text-sm mapview"'; 
                        echo '                data-lng="'. $lng .'" ';
                        echo '                data-lat="'. $lat .'"';
                        echo '                data-title="'. $value['title'] .'"';
                        echo '                data-addres="'. $value['addres'] .'"';
                        echo '                >街景、导航</span>';
                        echo '            </div>';
                        echo '        </if>';
                        echo '    </li>';
                    }
                }
                echo "<script>mapinit();</script>";
                echo $loadmore;
            }else{
                echo "非常抱歉，附近没有找到网点";
            }

            die();
        }
        $this->assign('page_title',"附近的网点");
        $this->display();
    }

    public function returnSquarePoint($lng, $lat,$distance = 1.5){
        $earthRadius = 6371;
        $dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/$earthRadius;
        $dlat = rad2deg($dlat);
        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
            );
    }

    /** 
    * @desc 根据两点间的经纬度计算距离 
    * @param float $lat 纬度值 
    * @param float $lng 经度值 
    */
    function getDistance($lat1, $lng1, $lat2, $lng2){  

        $earthRadius = 6367000; 

        $lat1 = ($lat1 * pi() ) / 180; 
        $lng1 = ($lng1 * pi() ) / 180; 

        $lat2 = ($lat2 * pi() ) / 180; 
        $lng2 = ($lng2 * pi() ) / 180;   

        $calcLongitude = $lng2 - $lng1; 
        $calcLatitude = $lat2 - $lat1; 
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
        $stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
        $calculatedDistance = $earthRadius * $stepTwo; 

        return round($calculatedDistance); 
    }

}