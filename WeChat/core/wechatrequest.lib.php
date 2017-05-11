<?php
namespace LaneWeChat\Core;

class WechatRequest{
        /**
         * @descrpition 分发请求
         * @param $request
         * @return array|string
         */
        public static function switchType(&$request){
            $data = array();

            switch ($request['msgtype']) {
                //事件
                case 'event':
                $request['event'] = strtolower($request['event']);
                switch ($request['event']) {
                        //关注
                    case 'subscribe':
                            //二维码关注
                    if(isset($request['eventkey']) && isset($request['ticket'])){
                        $data = self::eventQrsceneSubscribe($request);
                            //普通关注
                    }else{
                        $data = self::eventSubscribe($request);
                    }
                    break;
                        //扫描二维码
                    case 'scan':
                    $data = self::eventScan($request);
                    break;
                        //地理位置
                    case 'location':
                    $data = self::eventLocation($request);
                    break;
                        //自定义菜单 - 点击菜单拉取消息时的事件推送
                    case 'click':
                    $data = self::eventClick($request);
                    break;
                        //自定义菜单 - 点击菜单跳转链接时的事件推送
                    case 'view':
                    $data = self::eventView($request);
                    break;
                        //自定义菜单 - 扫码推事件的事件推送
                    case 'scancode_push':
                    $data = self::eventScancodePush($request);
                    break;
                        //自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
                    case 'scancode_waitmsg':
                    $data = self::eventScancodeWaitMsg($request);
                    break;
                        //自定义菜单 - 弹出系统拍照发图的事件推送
                    case 'pic_sysphoto':
                    $data = self::eventPicSysPhoto($request);
                    break;
                        //自定义菜单 - 弹出拍照或者相册发图的事件推送
                    case 'pic_photo_or_album':
                    $data = self::eventPicPhotoOrAlbum($request);
                    break;
                        //自定义菜单 - 弹出微信相册发图器的事件推送
                    case 'pic_weixin':
                    $data = self::eventPicWeixin($request);
                    break;
                        //自定义菜单 - 弹出地理位置选择器的事件推送
                    case 'location_select':
                    $data = self::eventLocationSelect($request);
                    break;
                        //取消关注
                    case 'unsubscribe':
                    $data = self::eventUnsubscribe($request);
                    break;
                        //群发接口完成后推送的结果
                    case 'masssendjobfinish':
                    $data = self::eventMassSendJobFinish($request);
                    break;
                        //模板消息完成后推送的结果
                    case 'templatesendjobfinish':
                    $data = self::eventTemplateSendJobFinish($request);
                    break;
                    default:
                    return Msg::returnErrMsg(MsgConstant::ERROR_UNKNOW_TYPE, '收到了未知类型的消息', $request);
                    break;
                }
                break;
                //文本
                case 'text':
                $data = self::text($request);
                break;
                //图像
                case 'image':
                $data = self::image($request);
                break;
                //语音
                case 'voice':
                $data = self::voice($request);
                break;
                //视频
                case 'video':
                $data = self::video($request);
                break;
                //小视频
                case 'shortvideo':
                $data = self::shortvideo($request);
                break;
                //位置
                case 'location':
                $data = self::location($request);
                break;
                //链接
                case 'link':
                $data = self::link($request);
                break;
                default:
                return ResponsePassive::text($request['fromusername'], $request['tousername'], '收到未知的消息，我不知道怎么处理');
                break;
            }
            return $data;
        }


        /**
         * @descrpition 文本
         * @param $request
         * @return array
         */
        public static function text(&$request){
            $content = '收到～';
            $key = $request['content'];
            $openid = $request['fromusername'];

            $sql = "select id,keyword,msgtype,text,newsitems from `llhl_wechat_keyword_reply` where keyword='$key' limit 0,1";
            $keywords = \LaneWeChat\Core\DB::get_one($sql);
            if($keywords){
                $keyword = $keywords['keyword'];
                $replaytype = $keywords['msgtype'];
                $text = $keywords['text'];
                $newsitems = $keywords['newsitems'];

                $newsitems = unserialize($newsitems);
                $items_title = $newsitems['title'];
                $items_pic_url = $newsitems['pic_url'];
                $items_url = $newsitems['url'];

                if($replaytype == 'newsitems'){
                    $tuwenList[] = array('title'=>$items_title, 'description'=>'', 'pic_url'=>$items_pic_url, 'url'=>$items_url);
                    $itemList = array();
                    foreach($tuwenList as $tuwen){
                        $itemList[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                    }
                    return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
                }else{
                    $content = $text;
                    return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
                }
            }
        }

        /**
         * @descrpition 图像
         * @param $request
         * @return array
         */
        public static function image(&$request){
            $content = '收到图片';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 语音
         * @param $request
         * @return array
         */
        public static function voice(&$request){
            if(!isset($request['recognition'])){
                $content = '收到语音';
                //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }else{
                $content = '收到语音识别消息，语音识别结果为：'.$request['recognition'];
                //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }
        }

        /**
         * @descrpition 视频
         * @param $request
         * @return array
         */
        public static function video(&$request){
            $content = '收到视频';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 视频
         * @param $request
         * @return array
         */
        public static function shortvideo(&$request){
            $content = '收到小视频';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 地理
         * @param $request
         * @return array
         */
        public static function location(&$request){
            $content = '收到上报的地理位置';

            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 链接
         * @param $request
         * @return array
         */
        public static function link(&$request){
            $content = '收到连接';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 关注
         * @param $request
         * @return array
         */
        public static function eventSubscribe(&$request){
            $content = '感谢您的关注';
            $openid = $request['fromusername'];

            $user_info = \LaneWeChat\Core\UserManage::getUserInfo($openid);

            if(isset($user_info['errcode'])){
                $access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
                $user_info = \LaneWeChat\Core\UserManage::getUserInfo($openid);
            }

            if(!isset($user_info["errcode"])){
                $nickname = $user_info["nickname"];
                $sex = $user_info["sex"];
                $language = $user_info["language"];
                $city = $user_info["city"];
                $province = $user_info["province"];
                $country = $user_info["country"];
                $headimgurl = $user_info["headimgurl"];
                $subscribe_time = $user_info["subscribe_time"];
                $unionid = $user_info["unionid"];
            }

            //用户关注后自动注册
            $sql = "select user_id from `llhl_users` where openid='$openid'";
            $usernow = \LaneWeChat\Core\DB::get_one($sql);
            //如果数据库没有当前用户openid对应的记录，则进行自动注册操作
            if(empty($usernow)){
                $sql = "insert into `llhl_users` (`reg_time`, `subscribe_time`, `sex`, `parentid`, `openid`, `level`, `nickname`, `city`, `province`, `country`, `language`, `headimgurl`) values ('".time()."', '$subscribe_time', '$sex', '0', '$openid', '0', '$nickname', '$city', '$province', '$country', '$language', '$headimgurl')";
                \LaneWeChat\Core\DB::query($sql);

                //插入用户后重新更新flag
                $sql = "select user_id from `llhl_users` where openid='$openid'";
                $usernow = \LaneWeChat\Core\DB::get_one($sql);
                if(!empty($usernow)){
                    $now_userid = $usernow['user_id'];
                    $flag = "F". $now_userid;
                    $sql = "update `llhl_users` set `flag`='$flag' where user_id='$now_userid'";
                    \LaneWeChat\Core\DB::query($sql);
                }
            }else{
                //如果有记录则进行个人信息更新操作
                $sql = "update `llhl_users` set `nickname`='$nickname', `city`='$city', `province`='$province', `country`='$country', `language`='$language', `headimgurl`='$headimgurl' where  openid='$openid'";
                \LaneWeChat\Core\DB::query($sql);
            }

            $sql = "select msgtype,text,newsitems from `llhl_wechat_subscribe_reply` where id='1' limit 0,1";
            $Subscribe = \LaneWeChat\Core\DB::get_one($sql);
            if($Subscribe){
                if($Subscribe['msgtype'] == 'newsitems'){
                    $newsitem = $Subscribe['newsitems'];
                    $newsitems = unserialize($newsitem);
                    $items_title = $newsitems['title'];
                    $items_pic_url = $newsitems['pic_url'];
                    $items_url = $newsitems['url'];
                    for ($i=0; $i < 5; $i++) {
                        if(isset($items_title[$i]) && !empty($items_title[$i])){
                            $title = $items_title[$i];
                            $pic_url = $items_pic_url[$i];
                            if(!strpos($pic_url, 'http')){
                                $pic_url = WECHAT_URL ."../". $pic_url;
                            }
                            $url = $items_url[$i];
                            $tuwenList[] = array('title'=>$title, 'description'=>'', 'pic_url'=>$pic_url, 'url'=>$url);
                        }
                    }
                    $itemList = array();
                    foreach($tuwenList as $tuwen){
                        $itemList[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                    }
                    return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
                }else{
                    $content = $Subscribe['text'];
                    return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
                }
            }
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 取消关注
         * @param $request
         * @return array
         */
        public static function eventUnsubscribe(&$request){
            $content = '为什么不理我了？';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 扫描二维码关注（未关注时）
         * @param $request
         * @return array
         */
        public static function eventQrsceneSubscribe(&$request){
            $content = '欢迎您关注我们的微信，将为您竭诚服务';
            $openid = $request['fromusername'];
            $scene_id = $request['eventkey'];
            $scene_id = str_replace('qrscene_', '', $scene_id);
            //$content = json_encode($request);
            
            $user_info = \LaneWeChat\Core\UserManage::getUserInfo($openid);

            if(isset($user_info['errcode'])){
                $access_token = \LaneWeChat\Core\AccessToken::RefreshAccessToken();
                $user_info = \LaneWeChat\Core\UserManage::getUserInfo($openid);
            }

            if(!isset($user_info["errcode"])){
                $nickname = $user_info["nickname"];
                $sex = $user_info["sex"];
                $language = $user_info["language"];
                $city = $user_info["city"];
                $province = $user_info["province"];
                $country = $user_info["country"];
                $headimgurl = $user_info["headimgurl"];
                $subscribe_time = $user_info["subscribe_time"];
                $unionid = $user_info["unionid"];
            }

            //用户关注后自动注册
            $sql = "select user_id from `llhl_users` where openid='$openid'";
            $usernow = \LaneWeChat\Core\DB::get_one($sql);
            //如果数据库没有当前用户openid对应的记录，则进行自动注册操作
            if(empty($usernow)){
                $sql = "insert into `llhl_users` (`reg_time`, `subscribe_time`, `sex`, `parentid`, `openid`, `level`, `nickname`, `city`, `province`, `country`, `language`, `headimgurl`) values ('".time()."', '$subscribe_time', '$sex', '$scene_id', '$openid', '0', '$nickname', '$city', '$province', '$country', '$language', '$headimgurl')";
                \LaneWeChat\Core\DB::query($sql);

                //插入用户后重新更新flag
                $sql = "select * from `llhl_users` where user_id='$scene_id'";
                $parent_user = \LaneWeChat\Core\DB::get_one($sql);
                if(!empty($parent_user)){
                    $level = $parent_user['level']+1;
                    $flag = $parent_user['flag'];
                }
                $sql = "select user_id from `llhl_users` where openid='$openid'";
                $usernow = \LaneWeChat\Core\DB::get_one($sql);
                if(!empty($usernow)){
                    $now_userid = $usernow['user_id'];
                    $sql = "update `llhl_users` set `flag`='$flag', `level`='$level' where user_id='$now_userid'";
                    \LaneWeChat\Core\DB::query($sql);
                }
            }else{
                //如果有记录则进行个人信息更新操作
                $sql = "update `llhl_users` set `nickname`='$nickname', `city`='$city', `province`='$province', `country`='$country', `language`='$language', `headimgurl`='$headimgurl' where  openid='$openid'";
                \LaneWeChat\Core\DB::query($sql);

                $row = \LaneWeChat\Core\DB::get_one("select parentid from llhl_users where openid='$openid'");
                $scene_id = $row['parentid'];
            }

            //推荐人消息推送
            $sql = "select openid from `llhl_users` where user_id='$scene_id'";
            $ecs_users = \LaneWeChat\Core\DB::get_one($sql);
            $parent_wxid = $ecs_users['openid'];
            $gourl = WECHAT_URL . '../index.php/user/spokes';
            $push_pic = WECHAT_URL."wechat_qrcode_tj.jpg";
            $tuwenList[] = array('title'=>'新朋友通过扫码加入', 'description'=>'你的朋友[ '. $nickname .' ]扫描你的二维码加入会员，你可以获得奖励哦~', 'pic_url'=>$push_pic, 'url'=>$gourl);
            $itemList = array();
            foreach ($tuwenList as $tuwen) {
                $itemList[] = ResponseInitiative::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
            }
            $ret = ResponseInitiative::news($parent_wxid, $itemList);


            //关注用户信息推送
            $sql = "select msgtype,text,newsitems from `llhl_wechat_subscribe_reply` where id='1' limit 0,1";
            $Subscribe = \LaneWeChat\Core\DB::get_one($sql);
            if($Subscribe){
                if($Subscribe['msgtype'] == 'newsitems'){
                    $newsitem = $Subscribe['newsitems'];
                    $newsitems = unserialize($newsitem);
                    $items_title = $newsitems['title'];
                    $items_pic_url = $newsitems['pic_url'];
                    $items_url = $newsitems['url'];
                    for ($i=0; $i < 5; $i++) {
                        if(isset($items_title[$i]) && !empty($items_title[$i])){
                            $title = $items_title[$i];
                            $pic_url = $items_pic_url[$i];
                            if(!strpos($pic_url, 'http')){
                                $pic_url = WECHAT_URL ."../". $pic_url;
                            }
                            $url = $items_url[$i];
                            $tuwenList[] = array('title'=>$title, 'description'=>'', 'pic_url'=>$pic_url, 'url'=>$url);
                        }
                    }
                    $itemList = array();
                    foreach($tuwenList as $tuwen){
                        $itemList[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                    }
                    return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
                }else{
                    $content = $Subscribe['text'];
                    return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
                }
            }
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);

        }

        /**
         * @descrpition 扫描二维码（已关注时）
         * @param $request
         * @return array
         */
        public static function eventScan(&$request){
            $content = '您已经关注了哦～';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 上报地理位置
         * @param $request
         * @return array
         */
        public static function eventLocation(&$request){
            $content = '收到上报的地理位置';
            //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 点击菜单拉取消息时的事件推送
         * @param $request
         * @return array
         */
        public static function eventClick(&$request){
    		//获取该分类的信息
            $eventKey = $request['eventkey'];
            $openid = $request['fromusername'];
            $return_type = 1;
            //$content = '收到点击菜单事件，您设置的key是' . $eventKey;

            if ($eventKey == 'qrcode') {
                $sql = "select user_id, qrcode from `llhl_users` where openid='$openid'";
                $users = \LaneWeChat\Core\DB::get_one($sql);
                if(!empty($users)){
                    $gourl = WECHAT_URL . '../index.php/user/qrcode/scene_id/' . $scene_id;
                    $qr_path = $users['qrcode'];
                    $push_pic = WECHAT_URL."wechat_qrcode.jpg";
                    /*
                    if(!empty($qr_path)){
                        $surl = $qr_path;
                    }else{
                        $scene_id = $users['user_id'];

                        // 拉取官方二维码
                        $ticket = Popularize::createTicket(2, 1800, $scene_id);
                        $ticket = $ticket['ticket'];
                        $qrcode = Popularize::getQrcode($ticket);

                        $time = time(); 
                        $ip = GetHostByName($_SERVER['SERVER_NAME']);
                        $ipr = str_replace('.', '', $ip);
                        $rand_name = $time . sprintf("%03d", mt_rand(1,999));
                        $img_name = 'qrcode_'. $scene_id .'_'. $ipr . $rand_name;
                        $dir = 'images';
                        $sub_dir = date('Ym', $time);
                        $img_ext = '.jpg';

                        if (!is_dir('./'.$dir.'/qrcode_img/'.$sub_dir)){
                            if (!mkdir('./'.$dir.'/qrcode_img/'.$sub_dir,0777,true)){
                                return ResponsePassive::text($request['fromusername'], $request['tousername'], "目录创建失败");
                            }
                        }

                        $path = './'.$dir.'/qrcode_img/'.$sub_dir.'/'.$img_name.$img_ext;

                        $local_file=fopen($path,'a');
                        if(false !==$local_file){
                            if(false !==fwrite($local_file,$qrcode)){
                                fclose($local_file);

                                $key = 'QRCODE/'.$sub_dir.'/'.$img_name.$img_ext;
                                $img_path = $dir.'/qrcode_img/'.$sub_dir.'/'.$img_name.$img_ext;
                                
                                $surl = "http://oss.llian.com.cn/".$key;
                                //$surl = $key;

                                include("aliyun_oss.php");
                                aliossput($key, $img_path);

                                //将生成的二维码图片的地址放到数据库中
                                $ssurl = addslashes($surl);
                                $insert_sql = "update `llhl_users` set qrcode='$ssurl' where openid='$openid'";
                                \LaneWeChat\Core\DB::query($insert_sql);
                                $return_type = 2;
                            }
                        }else{
                            return ResponsePassive::text($request['fromusername'], $request['tousername'], "请检查写入权限");
                        }
                    }
                    */

                    $tuwenList[] = array('title'=>'推荐二维码', 'description'=>'你的朋友扫描你的二维码加入会员，你可以获得奖励哦~', 'pic_url'=>$push_pic, 'url'=>$gourl);
                    $itemList = array();
                    foreach ($tuwenList as $tuwen) {
                        $itemList[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                    }
                    return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
                }else{
                    $content = "未能查到您的会员信息，请重试或重新关注";
                }
            }elseif($eventKey == 'jqqd'){
                $content = '敬请期待。';
                $return_type = 1;
            }else{
                return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }


            if($return_type == 1){
                return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
            }else{
                $itemList = array();
                foreach($tuwenList as $tuwen){
                    $itemList[] = ResponsePassive::newsItem($tuwen['title'], $tuwen['description'], $tuwen['pic_url'], $tuwen['url']);
                }
                return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
            }
        }

        /**
         * @descrpition 自定义菜单 - 点击菜单跳转链接时的事件推送
         * @param $request
         * @return array
         */
        public static function eventView(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到跳转链接事件，您设置的key是' . $eventKey;
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 扫码推事件的事件推送
         * @param $request
         * @return array
         */
        public static function eventScancodePush(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到扫码推事件的事件，您设置的key是' . $eventKey;
            $content .= '。扫描信息：'.$request['scancodeinfo'];
            $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
            $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
         * @param $request
         * @return array
         */
        public static function eventScancodeWaitMsg(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到扫码推事件且弹出“消息接收中”提示框的事件，您设置的key是' . $eventKey;
            $content .= '。扫描信息：'.$request['scancodeinfo'];
            $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
            $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 弹出系统拍照发图的事件推送
         * @param $request
         * @return array
         */
        public static function eventPicSysPhoto(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到弹出系统拍照发图的事件，您设置的key是' . $eventKey;
            $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
            $content .= '。发送的图片数量：'.$request['count'];
            $content .= '。图片列表：'.$request['piclist'];
            $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 弹出拍照或者相册发图的事件推送
         * @param $request
         * @return array
         */
        public static function eventPicPhotoOrAlbum(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到弹出拍照或者相册发图的事件，您设置的key是' . $eventKey;
            $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
            $content .= '。发送的图片数量：'.$request['count'];
            $content .= '。图片列表：'.$request['piclist'];
            $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 弹出微信相册发图器的事件推送
         * @param $request
         * @return array
         */
        public static function eventPicWeixin(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到弹出微信相册发图器的事件，您设置的key是' . $eventKey;
            $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
            $content .= '。发送的图片数量：'.$request['count'];
            $content .= '。图片列表：'.$request['piclist'];
            $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * @descrpition 自定义菜单 - 弹出地理位置选择器的事件推送
         * @param $request
         * @return array
         */
        public static function eventLocationSelect(&$request){
            //获取该分类的信息
            $eventKey = $request['eventkey'];
            $content = '收到点击跳转事件，您设置的key是' . $eventKey;
            $content .= '。发送的位置信息：'.$request['sendlocationinfo'];
            $content .= '。X坐标信息：'.$request['location_x'];
            $content .= '。Y坐标信息：'.$request['location_y'];
            $content .= '。精度(可理解为精度或者比例尺、越精细的话 scale越高)：'.$request['scale'];
            $content .= '。地理位置的字符串信息：'.$request['label'];
            $content .= '。朋友圈POI的名字，可能为空：'.$request['poiname'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * 群发接口完成后推送的结果
         *
         * 本消息有公众号群发助手的微信号“mphelper”推送的消息
         * @param $request
         */
        public static function eventMassSendJobFinish(&$request){
            //发送状态，为“send success”或“send fail”或“err(num)”。但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。err(num)是审核失败的具体原因，可能的情况如下：err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004), //涉嫌社会 err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他
            $status = $request['status'];
            //计划发送的总粉丝数。group_id下粉丝数；或者openid_list中的粉丝数
            $totalCount = $request['totalcount'];
            //过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
            $filterCount = $request['filtercount'];
            //发送成功的粉丝数
            $sentCount = $request['sentcount'];
            //发送失败的粉丝数
            $errorCount = $request['errorcount'];
            $content = '发送完成，状态是'.$status.'。计划发送总粉丝数为'.$totalCount.'。发送成功'.$sentCount.'人，发送失败'.$errorCount.'人。';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }

        /**
         * 群发接口完成后推送的结果
         *
         * 本消息有公众号群发助手的微信号“mphelper”推送的消息
         * @param $request
         */
        public static function eventTemplateSendJobFinish(&$request){
            //发送状态，成功success，用户拒收failed:user block，其他原因发送失败failed: system failed
            $status = $request['status'];
            if($status == 'success'){
                //发送成功
            }else if($status == 'failed:user block'){
                //因为用户拒收而发送失败
            }else if($status == 'failed: system failed'){
                //其他原因发送失败
            }
            return true;
        }

        public static function test(){
            // 第三方发送消息给公众平台
            $encodingAesKey = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG";
            $token = "pamtest";
            $timeStamp = "1409304348";
            $nonce = "xxxxxx";
            $appId = "wxb11529c136998cb6";
            $text = "<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";


            $pc = new Aes\WXBizMsgCrypt($token, $encodingAesKey, $appId);
            $encryptMsg = '';
            $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) {
                print("加密后: " . $encryptMsg . "\n");
            } else {
                print($errCode . "\n");
            }

            $xml_tree = new \DOMDocument();
            $xml_tree->loadXML($encryptMsg);
            $array_e = $xml_tree->getElementsByTagName('Encrypt');
            $array_s = $xml_tree->getElementsByTagName('MsgSignature');
            $encrypt = $array_e->item(0)->nodeValue;
            $msg_sign = $array_s->item(0)->nodeValue;

            $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
            $from_xml = sprintf($format, $encrypt);

            $msg = '';
            $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
            if ($errCode == 0) {
                print("解密后: " . $msg . "\n");
            } else {
                print($errCode . "\n");
            }
        }

    }
