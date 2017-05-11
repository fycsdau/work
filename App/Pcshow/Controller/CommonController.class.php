<?php
namespace Pcshow\Controller;
use Think\Controller;

Class CommonController extends Controller{

	public function _initialize(){
		$this->_name = CONTROLLER_NAME;

		$config =   S('DB_CONFIG_DATA');
		if(!$config){
			$config =   api('Config/lists');
			S('DB_CONFIG_DATA',$config);
		}
		C($config);
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
				}$User = M("Users");
				$User->where("openid='$openid'")->save($user_info);

				$users=M('users')->where("openid='$openid'")->find();
				$_SESSION['user_id'] = $users['user_id'];
				$_SESSION['user_phone'] = $users['phone'];
			}
			$this->assign('is_wechat_browser', 1);
			$wechat_api = D("wechat_config")->where("id=1")->find();
			$appid = $wechat_api['appid'];
			$appsecret = $wechat_api['appsecret'];
	
			$jssdk = new JSSDK($appid, $appsecret);
			$signPackage = $jssdk->GetSignPackage();
			$this->assign('signPackage',$signPackage);
		}else{
			$this->assign('is_wechat_browser', 0);
			$this->update_user();
		}
		$user_id = $_SESSION['user_id'];
		$user_auth = $_SESSION['user_auth'];
		$this->assign('user_id', $user_id);
		$this->assign('user_auth', $user_auth);
	}

	protected function mtReturn($status,$info,$navTabId="",$closeCurrent=true) {

		$udata['id']=session('uid');
		$udata['update_time']=time();
		$Rs=M("user")->save($udata);
		$dat['username'] = session('username');
		$dat['content'] = $info;
		$dat['os']=$_SERVER['HTTP_USER_AGENT'];
		$dat['url'] = U();
		$dat['addtime'] = date("Y-m-d H:i:s",time());
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

	/**
    * 列表页面
    */
	protected function _list($model, $map, $asc = false) {
	//排序字段 默认为主键名
		if (isset($_REQUEST ['orderField'])) {
			$order = $_REQUEST ['orderField'];
		}
		if($order=='') {
			$order = $model->getPk();
		}

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset($_REQUEST ['orderDirection'])) {
			$sort = $_REQUEST ['orderDirection'];
		}
		if($sort=='') {
			$sort = $asc ? 'asc' : 'desc';
		}

		if (isset($_REQUEST ['pageCurrent'])) {
			$pageCurrent = $_REQUEST ['pageCurrent'];
		}
		if($pageCurrent=='') {
			$pageCurrent =1;
		}
		
		//取得满足条件的记录数
		$count = $model->where($map)->count();

		if ($count > 0) {

			$numPerPage=C('PERPAGE');
			$voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($numPerPage)->page($pageCurrent.','.$numPerPage.'')->select();
			
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			
			if( method_exists($this, '_after_list')){
				$voList=$this->_after_list($voList);
			}
			$this->assign('list', $voList);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);
		return;
	}

	public function index() {

		$model = D($this->dbname);
		$map = $this->_search();
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}
		if (!empty($model)) {
			$this->_list($model, $map);
		}
		if (method_exists($this, '_befor_index')) {
			$this->_befor_index();
		}
		$this->display();
	}

	protected function _search($dbname='') {
		$dbname = $dbname ? $dbname : $this->dbname;
		$model = D($dbname);
		$map = array();
		foreach ($model->getDbFields() as $key => $val) {
			if (isset($_REQUEST['keys']) && $_REQUEST['keys'] != '') {
				if(in_array($val, C('SEARCHKEY'))){
					$map [$val] = array('like','%'.trim($_REQUEST['keys']).'%');
				}else{
					//$map [$val] = $_REQUEST['keys'];
				}
			}
		}
		$map['_logic'] = 'or';
		if ((IS_POST)&&isset($_REQUEST['keys']) && $_REQUEST['keys'] != '') {
			$where['_complex'] = $map;
			return $where;
		}else{
			return $map;
		}
	}

	public function add() {
		if(IS_POST){
			$model = D($this->dbname);
			$data=I('post.');
			if (false === $data = $model->create()) {
				$this->mtReturn(300,'失败，请检查值是否已经存在',$_REQUEST['navTabId'],true);  
			}
			if (method_exists($this, '_befor_insert')) {
				$data = $this->_befor_insert($data);
			}		  
			if($model->add($data)){
				if (method_exists($this, '_after_add')) {
					$id = $model->getLastInsID();
					$this->_after_add($id);
				}
				$id = $model->getLastInsID();
				//$this->mtReturn(200,"新增成功".$id,$_REQUEST['navTabId'],true);  
				$this->mtReturn(200,"新增成功",$_REQUEST['navTabId'],true);  
			}

		}
		if (method_exists($this, '_befor_add')) {
			$this->_befor_add();
		}
		$this->display();
	}

	public function edit() {
		$model = D($this->dbname);
		if(IS_POST){
			$data=I('post.');
			if (false === $data = $model->create()) {
				$this->mtReturn(300,'失败，请检查值是否已经存在',$_REQUEST['navTabId'],true);  
			}
			if (method_exists($this, '_befor_update')) {
				$data = $this->_befor_update($data);
			}		
			if($model->save($data)){
				if (method_exists($this, '_after_edit')) {
					$id = $data['id'];
					$this->_after_edit($id);
				} 
			}	
			$id = $data['id'];
		  	//$this->mtReturn(200,"编辑成功".$id,$_REQUEST['navTabId'],true); 		  
			$this->mtReturn(200,"编辑成功",$_REQUEST['navTabId'],true); 		  
		}
		if (method_exists($this, '_befor_edit')) {
			$this->_befor_edit();
		}
		$id = $_REQUEST [$model->getPk()];
		$vo = $model->getById($id);
		$this->assign('id',$id);
		$this->assign('Rs', $vo);
		$this->display();
	}
	
	public function view() {
		$model = D($this->dbname);
		$id = $_REQUEST [$model->getPk()];
		$vo = $model->getById($id);
		$this->assign('Rs', $vo);
		$this->display();
	}
	
	public function del(){
		$model = D($this->dbname);
		$id = I('get.id');
		if($id){
			$data=$model->find($id);
			$data['id']=$id;
			if($data['status']==1){
				$data['status']=0;
				$msg='锁定';
				if (method_exists($this, '_befor_del')) {
					$this->_befor_del($id);
				}	
			}else{
				$data['status']=1;
				$msg='启用';
			}
			$model->save($data);
			$this->mtReturn(200,$msg.$id,$_REQUEST['navTabId'],false);
		}else{
			$info=$model->where('status=0')->select();
			foreach($info as $key=>$v){
				$attid=$v['attid'];
				$ad['attid']=0;
				M('files')->where(array("attid"=>$attid))->save($ad);
			}
			$info=M('files')->where('attid=0 and  uid='.session('uid'))->select();
			foreach($info as $key=>$v){
				$filepath=$v['folder'].$v['filename'];
				unlink($filepath);
			}
			M('files')->where('attid=0 and  uid='.session('uid'))->delete();
			if(!in_array(session('uid'),C('ADMINISTRATOR'))){
				$Rs=$model->where('status=0 and uid='.session("uid"))->delete();
			}else{
				$Rs=$model->where('status=0')->delete();  
			}
			$this->mtReturn(200,'清理'.$Rs.'条无用的记录',$_REQUEST['navTabId'],false);
		}

	}
	
	public function _fenxi($fd,$ft,$type) {
		import("Org.Util.Chart");
		$chart = new \Chart;
		$model = D($this->dbname);
		$this->fd=$fd;
		$map = $this->_search();
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}
		$list = $model->where($map)->distinct($this->fd)->field($this->fd)->select();
		echo  $model->getlastsql();
		foreach ($list as $key =>$vo){	
			$info=$info.",".$vo[$this->fd];
			$co = $model->where(array($this->fd=>$vo[$this->fd]))->where($map)->count('id');
			$count=$count.",".$co;
		}
		$title = $ft; 
		$data = explode(",", substr ($count, 1)); 
		$size = 140; 
		$width = 750; 
		$height = 300; 
		$legend = explode(",", substr ($info, 1));
		ob_end_clean();
		if ($type == 1) {
			$chart->create3dpie($title,$data,$size,$height,$width,$legend);
		}
		if ($type == 2) {
			$chart->createcolumnar($title,$data,$size,$height,$width,$legend);
		}
		if ($type == 3) {
			$chart->createmonthline($title,$data,$size,$height,$width,$legend);
		}
		if ($type == 4) {
			$chart->createring($title,$data,$size,$height,$width,$legend);
		}
		if ($type == 5) {
			$chart->createhorizoncolumnar($title,$subtitle,$data,$size,$height,$width,$legend);
		}

	}
	
	public function xlsout($filename='数据表',$headArr,$list){
		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
		import("Org.Util.PHPExcel");
		import("Org.Util.PHPExcel.Writer.Excel5");
		import("Org.Util.PHPExcel.IOFactory.php");
		$this->getExcel($filename,$headArr,$list);
	}
	public function xlsin(){
		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
		import("Org.Util.PHPExcel");
		//要导入的xls文件，位于根目录下的Public文件夹
		$filename="./Public/1.xls";
		//创建PHPExcel对象，注意，不能少了\
		$PHPExcel=new \PHPExcel();
		//如果excel文件后缀名为.xls，导入这个类
		import("Org.Util.PHPExcel.Reader.Excel5");
		//如果excel文件后缀名为.xlsx，导入这下类
		//import("Org.Util.PHPExcel.Reader.Excel2007");
		//$PHPReader=new \PHPExcel_Reader_Excel2007();

		$PHPReader=new \PHPExcel_Reader_Excel5();
		//载入文件
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=1;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
			for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
			}

		}

	}
	public	function getExcel($fileName,$headArr,$data){
		//对数据进行检验
		if(empty($data) || !is_array($data)){
			die("data must be a array");
		}
		//检查文件名
		if(empty($fileName)){
			exit;
		}

		$date = date("Y_m_d",time());
		$fileName .= "_{$date}.xls";


		//创建PHPExcel对象，注意，不能少了\
		$objPHPExcel = new \PHPExcel();
		$objProps = $objPHPExcel->getProperties();

		//设置表头
		$key = ord("A");
		foreach($headArr as $v){
			$colum = chr($key);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
			$key += 1;
		}

		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();


		//设置为文本格式
		foreach($data as $key => $rows){ //行写入
			$span = ord("A");
			foreach($rows as $keyName=>$value){// 列写入
				$j = chr($span);

				$objActSheet->setCellValueExplicit($j.$column, $value);
				$span++;
			}
			$column++;
		}

		$fileName = iconv("utf-8", "gb2312", $fileName);
		//重命名表
		// $objPHPExcel->getActiveSheet()->setTitle('test');
		//设置活动单指数到第一个表,所以Excel打开这是第一个表
		$objPHPExcel->setActiveSheetIndex(0);
		ob_end_clean();//清除缓冲区,避免乱码
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=\"$fileName\"");
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output'); //文件通过浏览器下载
		exit;
	}
	
	/**
	 * 更新session
	 */
	public function update_user(){
		if($_SESSION['user_id']){
			$where['id'] = $_SESSION['user_id'];
			$user_info = M("User")->where($where)->find();
			$_SESSION['username'] = $user_info['username'];
			$_SESSION['truename'] = $user_info['truename'];
			$_SESSION['phone'] = $user_info['phone'];
		}
		return $user_info;
	}
	
}


//微信方法
class JSSDK {
	private $appId;
	private $appSecret;
	public function __construct($appId, $appSecret) {
		$this->appId = $appId;
		$this->appSecret = $appSecret;
	}
	public function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
      // 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"appId"     => $this->appId,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
			);
		return $signPackage; 
	}
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	private function getJsApiTicket() {
      // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = json_decode(file_get_contents("jsapi_ticket.json"));
		if ($data->expire_time < time()) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode($this->httpGet($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data->expire_time = time() + 7000;
				$data->jsapi_ticket = $ticket;
				$fp = fopen("jsapi_ticket.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$ticket = $data->jsapi_ticket;
		}
		return $ticket;
	}
	private function getAccessToken() {
      // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = json_decode(file_get_contents("access_token.json"));
		if ($data->expire_time < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				$fp = fopen("access_token.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}

//Snoopy.class.php
class Snoopy
{
	/**** Public variables ****/

	/* user definable vars */

    var $scheme = 'http'; // http or https
    var $host = "www.php.net"; // host name we are connecting to
    var $port = 80; // port we are connecting to
    var $proxy_host = ""; // proxy host to use
    var $proxy_port = ""; // proxy port to use
    var $proxy_user = ""; // proxy user to use
    var $proxy_pass = ""; // proxy password to use

    var $agent = "Snoopy v2.0.0"; // agent we masquerade as
    var $referer = ""; // referer info to pass
    var $cookies = array(); // array of cookies to pass
    // $cookies["username"]="joe";
    var $rawheaders = array(); // array of raw headers to send
    // $rawheaders["Content-type"]="text/html";

    var $maxredirs = 5; // http redirection depth maximum. 0 = disallow
    var $lastredirectaddr = ""; // contains address of last redirected address
    var $offsiteok = true; // allows redirection off-site
    var $maxframes = 0; // frame content depth maximum. 0 = disallow
    var $expandlinks = true; // expand links to fully qualified URLs.
    // this only applies to fetchlinks()
    // submitlinks(), and submittext()
    var $passcookies = true; // pass set cookies back through redirects
    // NOTE: this currently does not respect
    // dates, domains or paths.

    var $user = ""; // user for http authentication
    var $pass = ""; // password for http authentication

    // http accept types
    var $accept = "image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*";

    var $results = ""; // where the content is put

    var $error = ""; // error messages sent here
    var $response_code = ""; // response code returned from server
    var $headers = array(); // headers returned from server sent here
    var $maxlength = 500000; // max return data length (body)
    var $read_timeout = 0; // timeout on read operations, in seconds
    // supported only since PHP 4 Beta 4
    // set to 0 to disallow timeouts
    var $timed_out = false; // if a read operation timed out
    var $status = 0; // http request status

    var $temp_dir = "/tmp"; // temporary directory that the webserver
    // has permission to write to.
    // under Windows, this should be C:\temp

    var $curl_path = false;
    // deprecated, snoopy no longer uses curl for https requests,
    // but instead requires the openssl extension.

    // send Accept-encoding: gzip?
    var $use_gzip = true;

    // file or directory with CA certificates to verify remote host with
    var $cafile;
    var $capath;

    /**** Private variables ****/

    var $_maxlinelen = 4096; // max line length (headers)

    var $_httpmethod = "GET"; // default http request method
    var $_httpversion = "HTTP/1.0"; // default http request version
    var $_submit_method = "POST"; // default submit method
    var $_submit_type = "application/x-www-form-urlencoded"; // default submit type
    var $_mime_boundary = ""; // MIME boundary for multipart/form-data submit type
    var $_redirectaddr = false; // will be set if page fetched is a redirect
    var $_redirectdepth = 0; // increments on an http redirect
    var $_frameurls = array(); // frame src urls
    var $_framedepth = 0; // increments on frame depth

    var $_isproxy = false; // set if using a proxy server
    var $_fp_timeout = 30; // timeout for socket connection

    /*======================================================================*\
        Function:	fetch
        Purpose:	fetch the contents of a web page
                    (and possibly other protocols in the
                    future like ftp, nntp, gopher, etc.)
        Input:		$URI	the location of the page to fetch
        Output:		$this->results	the output text from the fetch
        \*======================================================================*/

        function fetch($URI)
        {

        	$URI_PARTS = parse_url($URI);
        	if (!empty($URI_PARTS["user"]))
        		$this->user = $URI_PARTS["user"];
        	if (!empty($URI_PARTS["pass"]))
        		$this->pass = $URI_PARTS["pass"];
        	if (empty($URI_PARTS["query"]))
        		$URI_PARTS["query"] = '';
        	if (empty($URI_PARTS["path"]))
        		$URI_PARTS["path"] = '';

        	$fp = null;

        	switch (strtolower($URI_PARTS["scheme"])) {
        		case "https":
        		if (!extension_loaded('openssl')) {
        			trigger_error("openssl extension required for HTTPS", E_USER_ERROR);
        			exit;
        		}
        		$this->port = 443;
        		case "http":
        		$this->scheme = strtolower($URI_PARTS["scheme"]);
        		$this->host = $URI_PARTS["host"];
        		if (!empty($URI_PARTS["port"]))
        			$this->port = $URI_PARTS["port"];
        		if ($this->_connect($fp)) {
        			if ($this->_isproxy) {
                        // using proxy, send entire URI
        				$this->_httprequest($URI, $fp, $URI, $this->_httpmethod);
        			} else {
        				$path = $URI_PARTS["path"] . ($URI_PARTS["query"] ? "?" . $URI_PARTS["query"] : "");
                        // no proxy, send only the path
        				$this->_httprequest($path, $fp, $URI, $this->_httpmethod);
        			}

        			$this->_disconnect($fp);

        			if ($this->_redirectaddr) {
        				/* url was redirected, check if we've hit the max depth */
        				if ($this->maxredirs > $this->_redirectdepth) {
                            // only follow redirect if it's on this site, or offsiteok is true
        					if (preg_match("|^https?://" . preg_quote($this->host) . "|i", $this->_redirectaddr) || $this->offsiteok) {
        						/* follow the redirect */
        						$this->_redirectdepth++;
        						$this->lastredirectaddr = $this->_redirectaddr;
        						$this->fetch($this->_redirectaddr);
        					}
        				}
        			}

        			if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
        				$frameurls = $this->_frameurls;
        				$this->_frameurls = array();

        				while (list(, $frameurl) = each($frameurls)) {
        					if ($this->_framedepth < $this->maxframes) {
        						$this->fetch($frameurl);
        						$this->_framedepth++;
        					} else
        					break;
        				}
        			}
        		} else {
        			return false;
        		}
        		return $this;
        		break;
        		default:
                // not a valid protocol
        		$this->error = 'Invalid protocol "' . $URI_PARTS["scheme"] . '"\n';
        		return false;
        		break;
        	}
        	return $this;
        }

    /*======================================================================*\
        Function:	submit
        Purpose:	submit an http(s) form
        Input:		$URI	the location to post the data
                    $formvars	the formvars to use.
                        format: $formvars["var"] = "val";
                    $formfiles  an array of files to submit
                        format: $formfiles["var"] = "/dir/filename.ext";
        Output:		$this->results	the text output from the post
        \*======================================================================*/

        function submit($URI, $formvars = "", $formfiles = "")
        {
        	unset($postdata);

        	$postdata = $this->_prepare_post_body($formvars, $formfiles);

        	$URI_PARTS = parse_url($URI);
        	if (!empty($URI_PARTS["user"]))
        		$this->user = $URI_PARTS["user"];
        	if (!empty($URI_PARTS["pass"]))
        		$this->pass = $URI_PARTS["pass"];
        	if (empty($URI_PARTS["query"]))
        		$URI_PARTS["query"] = '';
        	if (empty($URI_PARTS["path"]))
        		$URI_PARTS["path"] = '';

        	switch (strtolower($URI_PARTS["scheme"])) {
        		case "https":
        		if (!extension_loaded('openssl')) {
        			trigger_error("openssl extension required for HTTPS", E_USER_ERROR);
        			exit;
        		}
        		$this->port = 443;
        		case "http":
        		$this->scheme = strtolower($URI_PARTS["scheme"]);
        		$this->host = $URI_PARTS["host"];
        		if (!empty($URI_PARTS["port"]))
        			$this->port = $URI_PARTS["port"];
        		if ($this->_connect($fp)) {
        			if ($this->_isproxy) {
                        // using proxy, send entire URI
        				$this->_httprequest($URI, $fp, $URI, $this->_submit_method, $this->_submit_type, $postdata);
        			} else {
        				$path = $URI_PARTS["path"] . ($URI_PARTS["query"] ? "?" . $URI_PARTS["query"] : "");
                        // no proxy, send only the path
        				$this->_httprequest($path, $fp, $URI, $this->_submit_method, $this->_submit_type, $postdata);
        			}

        			$this->_disconnect($fp);

        			if ($this->_redirectaddr) {
        				/* url was redirected, check if we've hit the max depth */
        				if ($this->maxredirs > $this->_redirectdepth) {
        					if (!preg_match("|^" . $URI_PARTS["scheme"] . "://|", $this->_redirectaddr))
        						$this->_redirectaddr = $this->_expandlinks($this->_redirectaddr, $URI_PARTS["scheme"] . "://" . $URI_PARTS["host"]);

                            // only follow redirect if it's on this site, or offsiteok is true
        					if (preg_match("|^https?://" . preg_quote($this->host) . "|i", $this->_redirectaddr) || $this->offsiteok) {
        						/* follow the redirect */
        						$this->_redirectdepth++;
        						$this->lastredirectaddr = $this->_redirectaddr;
        						if (strpos($this->_redirectaddr, "?") > 0)
                                    $this->fetch($this->_redirectaddr); // the redirect has changed the request method from post to get
                                else
                                	$this->submit($this->_redirectaddr, $formvars, $formfiles);
                            }
                        }
                    }

                    if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
                    	$frameurls = $this->_frameurls;
                    	$this->_frameurls = array();

                    	while (list(, $frameurl) = each($frameurls)) {
                    		if ($this->_framedepth < $this->maxframes) {
                    			$this->fetch($frameurl);
                    			$this->_framedepth++;
                    		} else
                    		break;
                    	}
                    }

                } else {
                	return false;
                }
                return $this;
                break;
                default:
                // not a valid protocol
                $this->error = 'Invalid protocol "' . $URI_PARTS["scheme"] . '"\n';
                return false;
                break;
            }
            return $this;
        }

    /*======================================================================*\
        Function:	fetchlinks
        Purpose:	fetch the links from a web page
        Input:		$URI	where you are fetching from
        Output:		$this->results	an array of the URLs
        \*======================================================================*/

        function fetchlinks($URI)
        {
        	if ($this->fetch($URI) !== false) {
        		if ($this->lastredirectaddr)
        			$URI = $this->lastredirectaddr;
        		if (is_array($this->results)) {
        			for ($x = 0; $x < count($this->results); $x++)
        				$this->results[$x] = $this->_striplinks($this->results[$x]);
        		} else
        		$this->results = $this->_striplinks($this->results);

        		if ($this->expandlinks)
        			$this->results = $this->_expandlinks($this->results, $URI);
        		return $this;
        	} else
        	return false;
        }

    /*======================================================================*\
        Function:	fetchform
        Purpose:	fetch the form elements from a web page
        Input:		$URI	where you are fetching from
        Output:		$this->results	the resulting html form
        \*======================================================================*/

        function fetchform($URI)
        {

        	if ($this->fetch($URI) !== false) {

        		if (is_array($this->results)) {
        			for ($x = 0; $x < count($this->results); $x++)
        				$this->results[$x] = $this->_stripform($this->results[$x]);
        		} else
        		$this->results = $this->_stripform($this->results);

        		return $this;
        	} else
        	return false;
        }


    /*======================================================================*\
        Function:	fetchtext
        Purpose:	fetch the text from a web page, stripping the links
        Input:		$URI	where you are fetching from
        Output:		$this->results	the text from the web page
        \*======================================================================*/

        function fetchtext($URI)
        {
        	if ($this->fetch($URI) !== false) {
        		if (is_array($this->results)) {
        			for ($x = 0; $x < count($this->results); $x++)
        				$this->results[$x] = $this->_striptext($this->results[$x]);
        		} else
        		$this->results = $this->_striptext($this->results);
        		return $this;
        	} else
        	return false;
        }

    /*======================================================================*\
        Function:	submitlinks
        Purpose:	grab links from a form submission
        Input:		$URI	where you are submitting from
        Output:		$this->results	an array of the links from the post
        \*======================================================================*/

        function submitlinks($URI, $formvars = "", $formfiles = "")
        {
        	if ($this->submit($URI, $formvars, $formfiles) !== false) {
        		if ($this->lastredirectaddr)
        			$URI = $this->lastredirectaddr;
        		if (is_array($this->results)) {
        			for ($x = 0; $x < count($this->results); $x++) {
        				$this->results[$x] = $this->_striplinks($this->results[$x]);
        				if ($this->expandlinks)
        					$this->results[$x] = $this->_expandlinks($this->results[$x], $URI);
        			}
        		} else {
        			$this->results = $this->_striplinks($this->results);
        			if ($this->expandlinks)
        				$this->results = $this->_expandlinks($this->results, $URI);
        		}
        		return $this;
        	} else
        	return false;
        }

    /*======================================================================*\
        Function:	submittext
        Purpose:	grab text from a form submission
        Input:		$URI	where you are submitting from
        Output:		$this->results	the text from the web page
        \*======================================================================*/

        function submittext($URI, $formvars = "", $formfiles = "")
        {
        	if ($this->submit($URI, $formvars, $formfiles) !== false) {
        		if ($this->lastredirectaddr)
        			$URI = $this->lastredirectaddr;
        		if (is_array($this->results)) {
        			for ($x = 0; $x < count($this->results); $x++) {
        				$this->results[$x] = $this->_striptext($this->results[$x]);
        				if ($this->expandlinks)
        					$this->results[$x] = $this->_expandlinks($this->results[$x], $URI);
        			}
        		} else {
        			$this->results = $this->_striptext($this->results);
        			if ($this->expandlinks)
        				$this->results = $this->_expandlinks($this->results, $URI);
        		}
        		return $this;
        	} else
        	return false;
        }


    /*======================================================================*\
        Function:	set_submit_multipart
        Purpose:	Set the form submission content type to
                    multipart/form-data
                    \*======================================================================*/
                    function set_submit_multipart()
                    {
                    	$this->_submit_type = "multipart/form-data";
                    	return $this;
                    }


    /*======================================================================*\
        Function:	set_submit_normal
        Purpose:	Set the form submission content type to
                    application/x-www-form-urlencoded
                    \*======================================================================*/
                    function set_submit_normal()
                    {
                    	$this->_submit_type = "application/x-www-form-urlencoded";
                    	return $this;
                    }




    /*======================================================================*\
        Private functions
        \*======================================================================*/


    /*======================================================================*\
        Function:	_striplinks
        Purpose:	strip the hyperlinks from an html document
        Input:		$document	document to strip.
        Output:		$match		an array of the links
        \*======================================================================*/

        function _striplinks($document)
        {
        	preg_match_all("'<\s*a\s.*?href\s*=\s*			# find <a href=
        		([\"\'])?					# find single or double quote
        		(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
        		'isx", $document, $links);


        // catenate the non-empty matches from the conditional subpattern

        	while (list($key, $val) = each($links[2])) {
        		if (!empty($val))
        			$match[] = $val;
        	}

        	while (list($key, $val) = each($links[3])) {
        		if (!empty($val))
        			$match[] = $val;
        	}

        // return the links
        	return $match;
        }

    /*======================================================================*\
        Function:	_stripform
        Purpose:	strip the form elements from an html document
        Input:		$document	document to strip.
        Output:		$match		an array of the links
        \*======================================================================*/

        function _stripform($document)
        {
        	preg_match_all("'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[\r\n]*)|(?=[\r\n]*))|(?=[\r\n]*))'Usi", $document, $elements);

        // catenate the matches
        	$match = implode("\r\n", $elements[0]);

        // return the links
        	return $match;
        }


    /*======================================================================*\
        Function:	_striptext
        Purpose:	strip the text from an html document
        Input:		$document	document to strip.
        Output:		$text		the resulting text
        \*======================================================================*/

        function _striptext($document)
        {

        // I didn't use preg eval (//e) since that is only available in PHP 4.0.
        // so, list your entities one by one here. I included some of the
        // more common ones.

        $search = array("'<script[^>]*?>.*?</script>'si", // strip out javascript
            "'<[\/\!]*?[^<>]*?>'si", // strip out html tags
            "'([\r\n])[\s]+'", // strip out white space
            "'&(quot|#34|#034|#x22);'i", // replace html entities
            "'&(amp|#38|#038|#x26);'i", // added hexadecimal values
            "'&(lt|#60|#060|#x3c);'i",
            "'&(gt|#62|#062|#x3e);'i",
            "'&(nbsp|#160|#xa0);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "'&(reg|#174);'i",
            "'&(deg|#176);'i",
            "'&(#39|#039|#x27);'",
            "'&(euro|#8364);'i", // europe
            "'&a(uml|UML);'", // german
            "'&o(uml|UML);'",
            "'&u(uml|UML);'",
            "'&A(uml|UML);'",
            "'&O(uml|UML);'",
            "'&U(uml|UML);'",
            "'&szlig;'i",
            );
        $replace = array("",
        	"",
        	"\\1",
        	"\"",
        	"&",
        	"<",
        	">",
        	" ",
        	chr(161),
        	chr(162),
        	chr(163),
        	chr(169),
        	chr(174),
        	chr(176),
        	chr(39),
        	chr(128),
        	"ä",
        	"ö",
        	"ü",
        	"Ä",
        	"Ö",
        	"Ü",
        	"ß",
        	);

        $text = preg_replace($search, $replace, $document);

        return $text;
    }

    /*======================================================================*\
        Function:	_expandlinks
        Purpose:	expand each link into a fully qualified URL
        Input:		$links			the links to qualify
                    $URI			the full URI to get the base from
        Output:		$expandedLinks	the expanded links
        \*======================================================================*/

        function _expandlinks($links, $URI)
        {

        	preg_match("/^[^\?]+/", $URI, $match);

        	$match = preg_replace("|/[^\/\.]+\.[^\/\.]+$|", "", $match[0]);
        	$match = preg_replace("|/$|", "", $match);
        	$match_part = parse_url($match);
        	$match_root =
        	$match_part["scheme"] . "://" . $match_part["host"];

        	$search = array("|^http://" . preg_quote($this->host) . "|i",
        		"|^(\/)|i",
        		"|^(?!http://)(?!mailto:)|i",
        		"|/\./|",
        		"|/[^\/]+/\.\./|"
        		);

        	$replace = array("",
        		$match_root . "/",
        		$match . "/",
        		"/",
        		"/"
        		);

        	$expandedLinks = preg_replace($search, $replace, $links);

        	return $expandedLinks;
        }

    /*======================================================================*\
        Function:	_httprequest
        Purpose:	go get the http(s) data from the server
        Input:		$url		the url to fetch
                    $fp			the current open file pointer
                    $URI		the full URI
                    $body		body contents to send if any (POST)
        Output:
        \*======================================================================*/

        function _httprequest($url, $fp, $URI, $http_method, $content_type = "", $body = "")
        {
        	$cookie_headers = '';
        	if ($this->passcookies && $this->_redirectaddr)
        		$this->setcookies();

        	$URI_PARTS = parse_url($URI);
        	if (empty($url))
        		$url = "/";
        	$headers = $http_method . " " . $url . " " . $this->_httpversion . "\r\n";
        	if (!empty($this->host) && !isset($this->rawheaders['Host'])) {
        		$headers .= "Host: " . $this->host;
        		if (!empty($this->port) && $this->port != '80')
        			$headers .= ":" . $this->port;
        		$headers .= "\r\n";
        	}
        	if (!empty($this->agent))
        		$headers .= "User-Agent: " . $this->agent . "\r\n";
        	if (!empty($this->accept))
        		$headers .= "Accept: " . $this->accept . "\r\n";
        	if ($this->use_gzip) {
            // make sure PHP was built with --with-zlib
            // and we can handle gzipp'ed data
        		if (function_exists('gzinflate')) {
        			$headers .= "Accept-encoding: gzip\r\n";
        		} else {
        			trigger_error(
        				"use_gzip is on, but PHP was built without zlib support." .
        				"  Requesting file(s) without gzip encoding.",
        				E_USER_NOTICE);
        		}
        	}
        	if (!empty($this->referer))
        		$headers .= "Referer: " . $this->referer . "\r\n";
        	if (!empty($this->cookies)) {
        		if (!is_array($this->cookies))
        			$this->cookies = (array)$this->cookies;

        		reset($this->cookies);
        		if (count($this->cookies) > 0) {
        			$cookie_headers .= 'Cookie: ';
        			foreach ($this->cookies as $cookieKey => $cookieVal) {
        				$cookie_headers .= $cookieKey . "=" . urlencode($cookieVal) . "; ";
        			}
        			$headers .= substr($cookie_headers, 0, -2) . "\r\n";
        		}
        	}
        	if (!empty($this->rawheaders)) {
        		if (!is_array($this->rawheaders))
        			$this->rawheaders = (array)$this->rawheaders;
        		while (list($headerKey, $headerVal) = each($this->rawheaders))
        			$headers .= $headerKey . ": " . $headerVal . "\r\n";
        	}
        	if (!empty($content_type)) {
        		$headers .= "Content-type: $content_type";
        		if ($content_type == "multipart/form-data")
        			$headers .= "; boundary=" . $this->_mime_boundary;
        		$headers .= "\r\n";
        	}
        	if (!empty($body))
        		$headers .= "Content-length: " . strlen($body) . "\r\n";
        	if (!empty($this->user) || !empty($this->pass))
        		$headers .= "Authorization: Basic " . base64_encode($this->user . ":" . $this->pass) . "\r\n";

        //add proxy auth headers
        	if (!empty($this->proxy_user))
        		$headers .= 'Proxy-Authorization: ' . 'Basic ' . base64_encode($this->proxy_user . ':' . $this->proxy_pass) . "\r\n";


        	$headers .= "\r\n";

        // set the read timeout if needed
        	if ($this->read_timeout > 0)
        		socket_set_timeout($fp, $this->read_timeout);
        	$this->timed_out = false;

        	fwrite($fp, $headers . $body, strlen($headers . $body));

        	$this->_redirectaddr = false;
        	unset($this->headers);

        // content was returned gzip encoded?
        	$is_gzipped = false;

        	while ($currentHeader = fgets($fp, $this->_maxlinelen)) {
        		if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
        			$this->status = -100;
        			return false;
        		}

        		if ($currentHeader == "\r\n")
        			break;

            // if a header begins with Location: or URI:, set the redirect
        		if (preg_match("/^(Location:|URI:)/i", $currentHeader)) {
                // get URL portion of the redirect
        			preg_match("/^(Location:|URI:)[ ]+(.*)/i", chop($currentHeader), $matches);
                // look for :// in the Location header to see if hostname is included
        			if (!preg_match("|\:\/\/|", $matches[2])) {
                    // no host in the path, so prepend
        				$this->_redirectaddr = $URI_PARTS["scheme"] . "://" . $this->host . ":" . $this->port;
                    // eliminate double slash
        				if (!preg_match("|^/|", $matches[2]))
        					$this->_redirectaddr .= "/" . $matches[2];
        				else
        					$this->_redirectaddr .= $matches[2];
        			} else
        			$this->_redirectaddr = $matches[2];
        		}

        		if (preg_match("|^HTTP/|", $currentHeader)) {
        			if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|", $currentHeader, $status)) {
        				$this->status = $status[1];
        			}
        			$this->response_code = $currentHeader;
        		}

        		if (preg_match("/Content-Encoding: gzip/", $currentHeader)) {
        			$is_gzipped = true;
        		}

        		$this->headers[] = $currentHeader;
        	}

        	$results = '';
        	do {
        		$_data = fread($fp, $this->maxlength);
        		if (strlen($_data) == 0) {
        			break;
        		}
        		$results .= $_data;
        	} while (true);

        // gunzip
        	if ($is_gzipped) {
            // per http://www.php.net/manual/en/function.gzencode.php
        		$results = substr($results, 10);
        		$results = gzinflate($results);
        	}

        	if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
        		$this->status = -100;
        		return false;
        	}

        // check if there is a a redirect meta tag

        	if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]*URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results, $match)) {
        		$this->_redirectaddr = $this->_expandlinks($match[1], $URI);
        	}

        // have we hit our frame depth and is there frame src to fetch?
        	if (($this->_framedepth < $this->maxframes) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
        		$this->results[] = $results;
        		for ($x = 0; $x < count($match[1]); $x++)
        			$this->_frameurls[] = $this->_expandlinks($match[1][$x], $URI_PARTS["scheme"] . "://" . $this->host);
        } // have we already fetched framed content?
        elseif (is_array($this->results))
        	$this->results[] = $results;
        // no framed content
        else
        	$this->results = $results;

        return $this;
    }

    /*======================================================================*\
        Function:	setcookies()
        Purpose:	set cookies for a redirection
        \*======================================================================*/

        function setcookies()
        {
        	for ($x = 0; $x < count($this->headers); $x++) {
        		if (preg_match('/^set-cookie:[\s]+([^=]+)=([^;]+)/i', $this->headers[$x], $match))
        		$this->cookies[$match[1]] = urldecode($match[2]);
        	}
        	return $this;
        }


    /*======================================================================*\
        Function:	_check_timeout
        Purpose:	checks whether timeout has occurred
        Input:		$fp	file pointer
        \*======================================================================*/

        function _check_timeout($fp)
        {
        	if ($this->read_timeout > 0) {
        		$fp_status = socket_get_status($fp);
        		if ($fp_status["timed_out"]) {
        			$this->timed_out = true;
        			return true;
        		}
        	}
        	return false;
        }

    /*======================================================================*\
        Function:	_connect
        Purpose:	make a socket connection
        Input:		$fp	file pointer
        \*======================================================================*/

        function _connect(&$fp)
        {
        	if (!empty($this->proxy_host) && !empty($this->proxy_port)) {
        		$this->_isproxy = true;

        		$host = $this->proxy_host;
        		$port = $this->proxy_port;

        		if ($this->scheme == 'https') {
        			trigger_error("HTTPS connections over proxy are currently not supported", E_USER_ERROR);
        			exit;
        		}
        	} else {
        		$host = $this->host;
        		$port = $this->port;
        	}

        	$this->status = 0;

        	$context_opts = array();

        	if ($this->scheme == 'https') {
            // if cafile or capath is specified, enable certificate
            // verification (including name checks)
        		if (isset($this->cafile) || isset($this->capath)) {
        			$context_opts['ssl'] = array(
        				'verify_peer' => true,
        				'CN_match' => $this->host,
        				'disable_compression' => true,
        				);

        			if (isset($this->cafile))
        				$context_opts['ssl']['cafile'] = $this->cafile;
        			if (isset($this->capath))
        				$context_opts['ssl']['capath'] = $this->capath;
        		}

        		$host = 'ssl://' . $host;
        	}

        	$context = stream_context_create($context_opts);

        	if (version_compare(PHP_VERSION, '5.0.0', '>')) {
        		if($this->scheme == 'http')
        			$host = "tcp://" . $host;
        		$fp = stream_socket_client(
        			"$host:$port",
        			$errno,
        			$errmsg,
        			$this->_fp_timeout,
        			STREAM_CLIENT_CONNECT,
        			$context);
        	} else {
        		$fp = fsockopen(
        			$host,
        			$port,
        			$errno,
        			$errstr,
        			$this->_fp_timeout,
        			$context);
        	}

        	if ($fp) {
            // socket connection succeeded
        		return true;
        	} else {
            // socket connection failed
        		$this->status = $errno;
        		switch ($errno) {
        			case -3:
        			$this->error = "socket creation failed (-3)";
        			case -4:
        			$this->error = "dns lookup failure (-4)";
        			case -5:
        			$this->error = "connection refused or timed out (-5)";
        			default:
        			$this->error = "connection failed (" . $errno . ")";
        		}
        		return false;
        	}
        }

    /*======================================================================*\
        Function:	_disconnect
        Purpose:	disconnect a socket connection
        Input:		$fp	file pointer
        \*======================================================================*/

        function _disconnect($fp)
        {
        	return (fclose($fp));
        }


    /*======================================================================*\
        Function:	_prepare_post_body
        Purpose:	Prepare post body according to encoding type
        Input:		$formvars  - form variables
                    $formfiles - form upload files
        Output:		post body
        \*======================================================================*/

        function _prepare_post_body($formvars, $formfiles)
        {
        	settype($formvars, "array");
        	settype($formfiles, "array");
        	$postdata = '';

        	if (count($formvars) == 0 && count($formfiles) == 0)
        		return;

        	switch ($this->_submit_type) {
        		case "application/x-www-form-urlencoded":
        		reset($formvars);
        		while (list($key, $val) = each($formvars)) {
        			if (is_array($val) || is_object($val)) {
        				while (list($cur_key, $cur_val) = each($val)) {
        					$postdata .= urlencode($key) . "[]=" . urlencode($cur_val) . "&";
        				}
        			} else
        			$postdata .= urlencode($key) . "=" . urlencode($val) . "&";
        		}
        		break;

        		case "multipart/form-data":
        		$this->_mime_boundary = "Snoopy" . md5(uniqid(microtime()));

        		reset($formvars);
        		while (list($key, $val) = each($formvars)) {
        			if (is_array($val) || is_object($val)) {
        				while (list($cur_key, $cur_val) = each($val)) {
        					$postdata .= "--" . $this->_mime_boundary . "\r\n";
        					$postdata .= "Content-Disposition: form-data; name=\"$key\[\]\"\r\n\r\n";
        					$postdata .= "$cur_val\r\n";
        				}
        			} else {
        				$postdata .= "--" . $this->_mime_boundary . "\r\n";
        				$postdata .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
        				$postdata .= "$val\r\n";
        			}
        		}

        		reset($formfiles);
        		while (list($field_name, $file_names) = each($formfiles)) {
        			settype($file_names, "array");
        			while (list(, $file_name) = each($file_names)) {
        				if (!is_readable($file_name)) continue;

        				$fp = fopen($file_name, "r");
        				$file_content = fread($fp, filesize($file_name));
        				fclose($fp);
        				$base_name = basename($file_name);

        				$postdata .= "--" . $this->_mime_boundary . "\r\n";
        				$postdata .= "Content-Disposition: form-data; name=\"$field_name\"; filename=\"$base_name\"\r\n\r\n";
        				$postdata .= "$file_content\r\n";
        			}
        		}
        		$postdata .= "--" . $this->_mime_boundary . "--\r\n";
        		break;
        	}

        	return $postdata;
        }

    /*======================================================================*\
    Function:	getResults
    Purpose:	return the results of a request
    Output:		string results
    \*======================================================================*/

    function getResults()
    {
    	return $this->results;
    }
	
}