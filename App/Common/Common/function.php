<?php
date_default_timezone_set('PRC');

// Ð£ÑéÑéÖ¤Âë	@return boolean
function checkVerify(){
	$verify = new \Think\Verify();
	return $verify->check(I('verify'));
}

/**
      * ¼ì²éÈ¨ÏÞ
      * @param name string|array  ÐèÒªÑéÖ¤µÄ¹æÔòÁÐ±í,Ö§³Ö¶ººÅ·Ö¸ôµÄÈ¨ÏÞ¹æÔò»òË÷ÒýÊý×é
      * @param uid  int           ÈÏÖ¤ÓÃ»§µÄid
      * @param string mode        Ö´ÐÐcheckµÄÄ£Ê½
      * @param relation string    Èç¹ûÎª 'or' ±íÊ¾Âú×ãÈÎÒ»Ìõ¹æÔò¼´Í¨¹ýÑéÖ¤;Èç¹ûÎª 'and'Ôò±íÊ¾ÐèÂú×ãËùÓÐ¹æÔò²ÅÄÜÍ¨¹ýÑéÖ¤
      * @return boolean           Í¨¹ýÑéÖ¤·µ»Øtrue;Ê§°Ü·µ»Øfalse
*/
function authcheck($name, $uid, $type=1, $mode='url', $relation='or'){
    if(!in_array($uid,C('ADMINISTRATOR'))){ 
        $auth=new \Think\Auth();
        return $auth->check($name, $uid, $type, $mode, $relation)?true:false;
    }else{
        return true;
    }
}

function display($name){
    $name='Home/'.$name;
    $uid=session('uid');
    if(!in_array($uid,C('ADMINISTRATOR'))){
        if(!authcheck($name, $uid, $type=1, $mode='url', $relation='or')){
            return "style='display:none'";
        }
    }
}

function cateTree($pid=0,$level=0,$db=0){
    $cate=M(''.$db.'');
    $array=array();
    $tmp=$cate->where(array('pid'=>$pid))->order("sort")->select();
    print_r($tmp);die;
    if(is_array($tmp)){
        foreach($tmp as $v){
            $v['level']=$level;
            //$v['pid']>0;
            $array[count($array)]=$v;
            $sub=cateTree($v['id'],$level+1,$db);
            if(is_array($sub))$array=array_merge($array,$sub);
        }
    }
    return $array;
}

function orgcateTree($pid=0,$level=0,$type=0){
    $cate=M('auth_group');
    $array=array();
    $tmp=$cate->where(array('pid'=>$pid,'type'=>$type))->order("sort")->select();
    if(is_array($tmp)){
        foreach($tmp as $v){
            $v['level']=$level;
            //$v['pid']>0;
            $array[count($array)]=$v;
            $sub=orgcateTree($v['id'],$level+1,$type);
            if(is_array($sub))$array=array_merge($array,$sub);
        }
    }
    return $array;
}

function cateTreed($pid=0,$level=0){
    $cate=M('datalist');
    $array=array();
    $tmp=$cate->where(array('pid'=>$pid))->order("sort")->select();
    if(is_array($tmp)){
        foreach($tmp as $v){
            $v['level']=$level;
            //$v['pid']>0;
            $array[count($array)]=$v;
            $sub=cateTreed($v['id'],$level+1);
            if(is_array($sub))$array=array_merge($array,$sub);
        }
    }
    return $array;
}

/**
 * ¸ñÊ½»¯×Ö½Ú´óÐ¡
 * @param  number $size      ×Ö½ÚÊý
 * @param  string $delimiter Êý×ÖºÍµ¥Î»·Ö¸ô·û
 * @return string            ¸ñÊ½»¯ºóµÄ´øµ¥Î»µÄ´óÐ¡
 * @author Âóµ±Ãç¶ù <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
}

/**
 * µ÷ÓÃÏµÍ³µÄAPI½Ó¿Ú·½·¨£¨¾²Ì¬·½·¨£©
 * api('User/getName','id=5'); µ÷ÓÃ¹«¹²Ä£¿éµÄUser½Ó¿ÚµÄgetName·½·¨
 * api('Admin/User/getName','id=5');  µ÷ÓÃAdminÄ£¿éµÄUser½Ó¿Ú
 * @param  string  $name ¸ñÊ½ [Ä£¿éÃû]/½Ó¿ÚÃû/·½·¨Ãû
 * @param  array|string  $vars ²ÎÊý
 */
function api($name,$vars=array()){
    $array     = explode('/',$name);
    $method    = array_pop($array);
    $classname = array_pop($array);
    $module    = $array? array_pop($array) : 'Common';
    $callback  = $module.'\\Api\\'.$classname.'Api::'.$method;
    if(is_string($vars)) {
        parse_str($vars,$vars);
    }
    return call_user_func_array($callback,$vars);
}

function check_table_exist($tableName){
    $tableName = C('DB_PREFIX') . strtolower($tableName);
    $tables = M()->query('show tables');
    if(empty($tables)){
        exit('Êý¾Ý¿âÖÐÃ»ÓÐ±í');
    }
    foreach($tables as $v){
        if($v['tables_in_test']==$tableName){
            return true ;
        }
    }
    exit('Êý¾Ý¿âÖÐÃ»ÓÐ '.$tableName.' ±í£¬Çë´´½¨');
}

/**
 * ¸ù¾ÝÌõ¼þ×Ö¶Î»ñÈ¡Ö¸¶¨±íµÄÊý¾Ý
 * @param mixed $value Ìõ¼þ£¬¿ÉÓÃ³£Á¿»òÕßÊý×é
 * @param string $condition Ìõ¼þ×Ö¶Î
 * @param string $field ÐèÒª·µ»ØµÄ×Ö¶Î£¬²»´«Ôò·µ»ØÕû¸öÊý¾Ý
 * @param string $table ÐèÒª²éÑ¯µÄ±í
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null){
    if(empty($value) || empty($table)){
        return false;
    }

    //Æ´½Ó²ÎÊý
    $map[$condition] = $value;
    $info = M(ucfirst($table))->where($map);
    if(empty($field)){
        $info = $info->field(true)->find();
    }else{
        $info = $info->getField($field);
    }
    return $info;
}


function Hex($indata){
	$lX8 = $indata & 0x80000000;
	if($lX8)
	{
        $indata=$indata & 0x7fffffff;
    }
    while ($indata>16)
    {
        $temp_1=$indata % 16;
        $indata=$indata /16 ;
        if($temp_1<10)
            $temp_1=$temp_1+0x30;
        else
            $temp_1=$temp_1+0x41-10; 

        $outstring= chr($temp_1) . $outstring ; 

    }
    $temp_1=$indata;
    if($lX8)$temp_1=$temp_1+8;
    if($temp_1<10)
        $temp_1=$temp_1+0x30;
    else
        $temp_1=$temp_1+0x41-10; 

    $outstring= chr($temp_1) . $outstring ; 

    return $outstring;

}

/**
 * ×Ö·û´®×ª»»ÎªÊý×é£¬Ö÷ÒªÓÃÓÚ°Ñ·Ö¸ô·ûµ÷Õûµ½µÚ¶þ¸ö²ÎÊý
 * @param  string $str  Òª·Ö¸îµÄ×Ö·û´®
 * @param  string $glue ·Ö¸î·û
 * @return array
 * @author Âóµ±Ãç¶ù <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}

/**
 * Êý×é×ª»»Îª×Ö·û´®£¬Ö÷ÒªÓÃÓÚ°Ñ·Ö¸ô·ûµ÷Õûµ½µÚ¶þ¸ö²ÎÊý
 * @param  array  $arr  ÒªÁ¬½ÓµÄÊý×é
 * @param  string $glue ·Ö¸î·û
 * @return string
 * @author Âóµ±Ãç¶ù <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
    return implode($glue, $arr);
}

/**
 * ×Ö·û´®½ØÈ¡£¬Ö§³ÖÖÐÎÄºÍÆäËû±àÂë
 * @static
 * @access public
 * @param string $str ÐèÒª×ª»»µÄ×Ö·û´®
 * @param string $start ¿ªÊ¼Î»ÖÃ
 * @param string $length ½ØÈ¡³¤¶È
 * @param string $charset ±àÂë¸ñÊ½
 * @param string $suffix ½Ø¶ÏÏÔÊ¾×Ö·û
 * @return string
 */
function msubstr($str, $start=0, $length) {
	$charset="utf-8";
	$suffix=true;
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'' : $slice;
}

function getuserid(){
	return session("uid");
}

function gettruename(){
	return session("truename");
}

function gettime(){
	return date('Y-m-d H:i:s',time());
}

function encrypt($data) {
        //return md5(C('AUTH_MASK') . md5($data));
  return md5(md5($data));
}

//html´úÂëÊä³ö
function html_out($str){
    if(function_exists('htmlspecialchars_decode')){
        $str=htmlspecialchars_decode($str);
    }else{
        $str=html_entity_decode($str);
    }
    $str = stripslashes($str);
    return $str;
}

function truename($id){
    $data=M('users')->where('id='.$id)->getField('truename');
    return $data;
}

//获取公网IP
function get_onlineip() {
    $url='http://www.ip138.com/ip2city.asp';
    $html=file_get_contents($url);
    preg_match('/\[(.*)\]/', $html, $ip);
    return $ip[1];
}

//根据用户的IP地址，返回相关信息
function ip_to_loc($ip){
    $url='http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='. $ip;
    $html=file_get_contents($url);  
    $obj=json_decode($html);
    return $obj;
}


//获取角色名称
function getdepname($id){
    if(!empty($id)){
     $data=M('auth_group')->where('type=1 and id='.$id)->getField('title');
     return $data; 
 }
}


//提取内容图片
function getImgs($content,$order='ALL'){
    $pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,$content,$match);
    if(isset($match[1])&&!empty($match[1])){
        if($order==='ALL'){
            return $match[1];
        }
        if(is_numeric($order)&&isset($match[1][$order])){
            return $match[1][$order];
        }
    }
    return '';
}

function is_wechat_browser(){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false){
        return false;
    } else {
        //echo '<br>你的微信版本号为:'.$matches[2];
        return true;
    }
}

//生成唯一订单号
function build_order_no()
{
    $no = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 10);
        //检测是否存在
    $db = M('order');
    $info = $db->where(array('order_sn'=>$no))->find();
    (!empty($info)) && $no = build_order_no();
    return $no;

}

