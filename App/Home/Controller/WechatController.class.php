<?php

namespace Home\Controller;
use Think\Controller;

class WechatController extends CommonController{

	public function index(){
		$model = D("wechat_config");
		$config = $model->find('1');
		$this->assign('config',$config);
		$this->assign('id',$config['id']);
		$this->display();
	}

	//保存微信接口配置
	public function savecongfig(){
		$model = D("wechat_config");

		$data=I('post.');
		$model->save($data);
		$id = $data['id'];
		$loginfo = $_REQUEST['navTabId'].' 编辑 '.$id;

		$this->mtReturn(200, "保存成功", $_REQUEST['navTabId'], false, $loginfo);
	}

	//微信关注回复
	public function subscribe(){
		$model = D("wechat_subscribe_reply");
		$subscribe = $model->find('1');

		$newsitem = $subscribe['newsitems'];
		$newsitems = unserialize($newsitem);

		$this->assign('subscribe',$subscribe);
		$this->assign('newsitems',$newsitems);
		$this->assign('id',$subscribe['id']);

		$this->display();
	}

	//微信关注回复保存
	public function savesubscribe(){
		$model = D("wechat_subscribe_reply");

		$data = I('post.');
		$datas = array();
		$datas['id'] = $data['id'];
		$datas['msgtype'] = $data['msgtype'];
		$datas['text'] = $data['text'];

		$newsitems = array();
		$newsitems['title'] = $data['items_title'];
		$newsitems['description'] = '';
		$newsitems['pic_url'] = $data['items_pic_url'];
		$newsitems['url'] = $data['items_url'];
		$newsitems = serialize($newsitems);
		$datas['newsitems'] = $newsitems;

		$model->save($datas);
		$id = $data['id'];
		$loginfo = $_REQUEST['navTabId'].' 编辑微信关注回复 ';

		$this->mtReturn(200, "保存成功", $_REQUEST['navTabId'], false, $loginfo);
	}

	//微信自定义菜单
	public function menu(){
		$this->display();
	}

	//微信自定义菜单保存
	public function savemenu(){
		$model = D("wechat_menu");
		$data = I('post.');
		for ($i=0; $i < count($data['title']); $i++) {
			$id = $i+1;
			$datas=array(
				'id'=>$id,
				'name'=>$data['title'][$i],
				'menu_type'=>$data['menutype'][$i],
				'value'=>$data['code'][$i]
				);
			$model->save($datas);
		}

		$loginfo = $_REQUEST['navTabId'].' 编辑微信自定义菜单 ';

		$this->mtReturn(200, "保存成功. 别忘记点击【微信菜单同步】按钮同步到微信平台哦～", $_REQUEST['navTabId'], false, $loginfo);
	}

	//微信自定义菜单同步
	public function syncmenu(){
		require_once(THINK_PATH.'../WeChat/lanewechat.php');
		$menuList = array();
		$model = D("wechat_menu");
		$menu = $model->where("parent='0'")->order("id asc")->select();
		for ($i=0; $i < count($menu); $i++) {
			$id = $menu[$i]['id'];
			$menu_type = $menu[$i]['menu_type'];
			$name = $menu[$i]['name'];
			$value = $menu[$i]['value'];
			$parent = '';
			if(!empty($name)){
				$menuList[] = array('id'=>$id, 'pid'=>$parent,  'name'=>$name, 'type'=>$menu_type, 'code'=>$value);

				$child_menu = $model->where("parent='$id'")->order("id asc")->select();
				for ($a=0; $a < count($child_menu); $a++) {
					$id = $child_menu[$a]['id'];
					$menu_type = $child_menu[$a]['menu_type'];
					$name = $child_menu[$a]['name'];
					$value = $child_menu[$a]['value'];
					$parent = $child_menu[$i]['parent'];
					if(!empty($name)){
						$menuList[] = array('id'=>$id, 'pid'=>$parent,  'name'=>$name, 'type'=>$menu_type, 'code'=>$value);
					}
				}
			}
		}

		\LaneWeChat\Core\Menu::setMenu($menuList);

		$this->mtReturn(200, '已将自定义菜单同步到微信，24小时以内响应哦～', $_REQUEST['navTabId'], false, $loginfo);

	}

	//关键字自动回复
	public function keyword(){
		$model = D("wechat_keyword_reply");

		$keywords = $_REQUEST ['keywords'];
		if(!empty($keywords)){
			$map['keyword'] = array('like','%'. $keywords .'%');
		}

		if (isset($_REQUEST ['pageCurrent'])) {
			$pageCurrent = $_REQUEST ['pageCurrent'];
		}
		if($pageCurrent=='') {
			$pageCurrent =1;
		}
		if($order=='') {
			$order = $model->getPk();
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
			
			$this->assign('list', $voList);
		}
		$this->assign('totalCount', $count);//数据总数
		$this->assign('currentPage', !empty($_REQUEST[C('VAR_PAGE')]) ? $_REQUEST[C('VAR_PAGE')] : 1);//当前的页数，默认为1
		$this->assign('numPerPage', $numPerPage); //每页显示多少条
		cookie('_currentUrl_', __SELF__);

		$this->display();
	}

	//关键字自动回复 - 添加编辑
	public function keywordadd(){	
		$model = D("wechat_keyword_reply");

		$id = I('get.id');

		$keywords = $model->getById($id);

		if($keywords){
			foreach ($keywords as $key => $value) {
				$this->assign($key, $value);
			}

			$newsitem = $keywords['newsitems'];
			$newsitems = unserialize($newsitem);
			foreach ($newsitems as $key => $value) {
				$this->assign('newsitems_'.$key, $value);
			}

		}

		$this->display();
	}

	//关键字自动回复 - 删除
	public function keyworddel(){
		$model = D("wechat_keyword_reply");
		$id = I('get.id');
		if($id){
			$loginfo = $_REQUEST['navTabId'].' 删除 '.$id;
			$model->where("id='$id'")->delete();
			$this->mtReturn(200, '删除成功', $_REQUEST['navTabId'], false, $loginfo);
		}
	}

	//关键字自动回复 - 保存
	public function keywordsave(){
		$model = D("wechat_keyword_reply");

		$data = I('post.');

		$datas = array();
		$datas['keyword'] = $data['keywords'];
		$datas['msgtype'] = $data['msgtype'];
		$datas['text'] = $data['text'];

		$newsitems = array();
		$newsitems['title'] = $data['items_title'];
		$newsitems['description'] = '';
		$newsitems['pic_url'] = $data['items_pic_url'];
		$newsitems['url'] = $data['items_url'];
		$newsitems = serialize($newsitems);
		$datas['newsitems'] = $newsitems;

		if(!empty($data['id'])){
			$id = $data['id'];
			$keywords = $data['keywords'];
			//判断是否已经存在相关记录
			$idd = $model->where("id<>'$id' and keyword='$keywords'")->select();
			if($idd){
				$this->mtReturn(300, "关键字 [ ". $keywords ." ] 已存在!", $_REQUEST['navTabId'], true, $loginfo);  
			}

			$datas['id'] = $data['id'];
			$model->save($datas);
			$loginfo = $_REQUEST['navTabId'].' 编辑 '.$id;
			$this->mtReturn(200, "编辑成功", $_REQUEST['navTabId'], true, $loginfo);  
		}else{
			$keywords = $data['keywords'];
			//判断是否已经存在相关记录
			$idd = $model->where("keyword='$keywords'")->select();
			if($idd){
				$this->mtReturn(300, "关键字 [ ". $keywords ." ] 已存在!", $_REQUEST['navTabId'], true, $loginfo);  
			}

			$model->add($datas);
			$id = $model->getLastInsID();
			$loginfo = $_REQUEST['navTabId'].' 新增 '.$id;
			$this->mtReturn(200, "新增成功", $_REQUEST['navTabId'], true, $loginfo);  
		}
	}

	//微信支付
	public function pay(){
		$model = D("wechat_config");
		if(IS_POST){
			$data=I('post.');
			$model->where('id=1')->save($data);
			$loginfo = $_REQUEST['navTabId'].' 编辑微信支付参数 ';
			$this->mtReturn(200, "修改成功", $_REQUEST['navTabId'], true, $loginfo); 
		}
		$config = $model->find('1');
		$this->assign('config',$config);
		$this->assign('id',$config['id']);
		$this->display();
	}

}