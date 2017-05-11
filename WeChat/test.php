<?php
error_reporting(E_ALL);
echo "111";
$con = mysql_connect("rds9i26q4svwz3vhl7o1o.mysql.rds.aliyuncs.com","liulian","daiyan12345678");
echo "222";
var_dump($con);
if (!$con){
	die('Could not connect: ' . mysql_error());
}else{
	die('connent success');
}
?>