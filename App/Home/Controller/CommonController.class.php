<?php
namespace Home\Controller;
use Think\Controller;

Class CommonController extends Controller{

	public function _initialize(){
		
		$this->_name = CONTROLLER_NAME;
		if(!session('uid')){
			redirect(U('Public/login'));
			$this->mtReturn(301,''.session('username').'请重新登陆',$_REQUEST['navTabId']);
			die();
		}

		$config =   S('DB_CONFIG_DATA');
		if(!$config){
			$config =   api('Config/lists');
			S('DB_CONFIG_DATA',$config);
		}
		C($config); 

		$name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		if(!authcheck($name,session('uid'))){
		   //$this->error(''.session('username').'很抱歉,此项操作您没有权限！');
			$this->mtReturn(300,''.session('username').'很抱歉,此项操作您没有权限！',$_REQUEST['navTabId']);
		}	 
	}
	
	protected function mtReturn($status,$info,$navTabId="",$closeCurrent=true, $loginfo=null) {

// 		$udata['id']=session('uid');
// 		$udata['update_time']=time();
// 		$Rs=M("user")->save($udata);

		$loginfo = empty($loginfo) ? $info : $loginfo;
		$dat['username'] = session('username');
		$dat['content'] = $loginfo;
		$dat['os']=$_SERVER['HTTP_USER_AGENT'];
		$dat['url'] = U();
		$dat['addtime'] = date("Y-m-d H:i:s",time());
		$dat['ip'] = get_client_ip();
		M("log")->add($dat);

		$result = array();
		$result['statusCode'] = $status; 
		$result['message'] = $info;
		$result['tabid'] = $navTabId;
		//$result['tabid'] = strtolower($navTabId).'/index';
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
			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				if(in_array($val, C('SEARCHKEY'))){
					$map [$val] = $_REQUEST[$val];
				}else{
				}
			}
		}
		return $map;
	}

	public function add() {
		if(IS_POST){
			$model=M(''.$this->dbname.'');
			$data=I('post.');
			if (false === $data = $model->create()) {
				$this->mtReturn(300,'失败，请检查值是否已经存在',$_REQUEST['navTabId'],true);  
			}
			if (method_exists($this, '_befor_insert')) {
				$data = $this->_befor_insert($data);
			}
			$data['add_time'] = date("Y-m-d H:i:s",time());
			if($model->add($data)){
				if (method_exists($this, '_after_add')) {
					$id = $model->getLastInsID();
					$data=$model->find($id);
					$this->_after_add($data);
				}
				$id = $model->getLastInsID();
				$loginfo = $_REQUEST['navTabId'].' 新增 '.$id;

				$this->mtReturn(200, "添加成功", $_REQUEST['navTabId'], true, $loginfo);  
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
			$loginfo = $_REQUEST['navTabId'].' 编辑 '.$id;

		  	//$this->mtReturn(200,"编辑成功".$id,$_REQUEST['navTabId'],true);
			$this->mtReturn(200, "编辑成功", $_REQUEST['navTabId'], true, $loginfo); 	  
		}
		if (method_exists($this, '_befor_edit')) {
			$this->_befor_edit();
		}
		$id = $_REQUEST [$model->getPk()];
		//$id = I('get.id');
		$vo = $model->getById($id);

		//var_dump($model->getlastsql());
		$this->assign('id',$id);
		$this->assign('Rs', $vo);
		$this->display();
	}
	
	public function view() {
		$model = D($this->dbname);
		$id = $_REQUEST [$model->getPk()];
		$vo = $model->getById($id);
		if (method_exists($this, '_befor_view')) {
			$this->_befor_view($vo);
		}
		$this->assign('Rs', $vo);
		$this->display();
	}

	public function status(){
		$model = D($this->dbname);
		$id = I('get.id');
		if($id){
			$data=$model->find($id);
			$data['id']=$id;
			if($data['status']==1){
				$data['status']=0;
				$msg='锁定';
			}else{
				$data['status']=1;
				$msg='启用';
			}
			$loginfo = $_REQUEST['navTabId'].$msg.$id;

			if (method_exists($this, '_befor_status')) {
				$this->_befor_status();
			}

			$model->where("id='$id'")->save($data);
			$this->mtReturn(200,$msg."成功",$_REQUEST['navTabId'],false,$loginfo);
		}
	}
	
	public function del(){
		$model = D($this->dbname);
		$id = I('get.id');
		if($id){
			$loginfo = $_REQUEST['navTabId'].' 删除 '.$id;
			$model->where("id='$id'")->delete();
			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
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
	
}