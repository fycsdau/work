<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',True);
define('BIND_MODULE','Web');
define('APP_PATH','./App/');
require './Core/index.php';
//include './App/Home/Controller/taobao/recharge.php';//×Ô¶¯³äÖµ
$_SESSION['user_id'] = 81036;
