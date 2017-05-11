<?php
return array( 
    'URL_MODEL'   =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    'URL_CASE_INSENSITIVE' =>true, //表示URL访问不区分大小写
   
	//权限验证设置
	'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, 
        'AUTH_TYPE' => 1, // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP' => 'llhl_auth_group', 
        'AUTH_GROUP_ACCESS' => 'llhl_auth_group_access', 
        'AUTH_RULE' => 'llhl_auth_rule', 
        'AUTH_USER' => 'llhl_user'
    ),
	'NOT_AUTH_MODULE' => 'Public,Index', // 默认无需认证模块
    //超级管理员id,拥有全部权限,只要用户uid在这个角色组里的,就跳出认证.可以设置多个值,如array('1','2','3')
    'ADMINISTRATOR'=>array('1'),
	'SESSION_OPTIONS' =>  array('expire'=>36000),
	'SESSION_PREFIX'        =>  'llhl', 
	
	// 加载扩展配置文件 多个用,隔开
	'LOAD_EXT_CONFIG' => 'web,db', 
	
	//上传设置
	'UPLOAD_MAXSIZE'=>31457280,
	'UPLOAD_EXTS'=>array('jpg','gif','png','jpeg','txt','doc','docx','xls','xlsx','ppt','pptx','pdf','rar','zip','wps','wpt','dot','rtf','dps','dpt','pot','pps','et','ett','xlt'),// 设置附件上传类型 
	'UPLOAD_SAVEPATH'=>'./Public/',
		
	/* 自动运行配置 */
	'CRON_CONFIG_ON' => true, // 是否开启自动运行
	'CRON_CONFIG' => array(
			'测试定时任务' => array('Home/Run/crons', '0', '0'), //路径(格式同R)、间隔秒（0为一直运行）、指定一个开始时间
	),
);
