<?php
namespace LaneWeChat;
use LaneWeChat\Core\Wechat;
//引入配置文件
include_once __DIR__.'/config.php';
//引入自动载入函数
include_once __DIR__.'/autoloader.php';
//调用自动载入函数
AutoLoader::register();
//初始化微信类
$wechat = new WeChat(WECHAT_TOKEN, TRUE);

$wechat->checkSignature();
//echo $wechat->run();