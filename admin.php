<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',True);
define('APP_PATH','./App/');
require './Core/index.php';
//include './App/Home/Controller/taobao/recharge.php';//自动充值
//include './App/Home/Controller/taobao/callback.php';//状态回查
