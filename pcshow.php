<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',True);
define('BIND_MODULE','Pcshow');
define('APP_PATH','./App/');
define('DEFAULT_ACTION', 'index');
require './Core/index.php';
//$_SESSION['user_id'] = 81036;
