<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	<title>微信后台管理系统</title>
	<script src="/Public/BJUI/js/jquery-1.7.2.min.js"></script>
	<script src="/Public/BJUI/js/jquery.cookie.js"></script>

	<link rel="stylesheet" type="text/css" href="/Public/BJUI/themes/css/login_default.css">
	<link rel="stylesheet" type="text/css" href="/Public/BJUI/themes/css/login_styles.css">
	
	<script type="text/javascript">
		$(function() {
			redraw();
			$("#j_username,#j_password").focus(function(){$("#login_status").html("");})
		});

		function genTimestamp(){
			var time = new Date();
			return time.getTime();
		}
		function changeCode(){
			$("#captcha_img").attr("src", "/captcha.jpeg?t="+genTimestamp());
		}

		function login_check(){
			var j_username = $("#j_username").val();
			var j_password = $("#j_password").val();
			if(j_username.length == 0){
				$("#login_status").html("请填写登陆帐号！");
				return false;
			}
			if(j_password.length == 0){
				$("#login_status").html("请填写登陆密码！");
				return false;
			}

			data = {"username":j_username,"password":j_password}

			$.ajax({
				type: "post",
				cache:false,
				data: data,
				url: "/admin.php/Home/Public/login",
				timeout: 20000,
				error: function(){ $("#login_status").html("服务器错误，请联系管理员处理"); },
				success: function(t){
					var dataObj=eval("("+t+")");
					//var dataObj=eval(t);
					$("#login_status").html(dataObj.info);
					if(dataObj.status==1){
						window.location.href= "/admin.php/Home/Admin/index";
					}
				}
			});
			return false;
		}
	</script>
</head>
<body>

	<article class="htmleaf-container">
		<form action="<?php echo U('Login');?>" name="frmlogin" id="login_form" method="post" autocomplete="off" onsubmit="return login_check();">
			<div class="panel-lite">
				<div class="thumbur">
					<img src="/Public/images/logo.png">
				</div>
				<div class="form-group">
					<input class="form-control" id="j_username" name="username"/>
					<label class="form-label">登陆帐号</label>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="j_password" name="password"/>
					<label class="form-label">登陆密码</label>
				</div>
				<a href="#" id="login_status"></a>
				<button class="floating-btn"><i class="icon-arrow"></i></button>
			</div>
		</form>
	</article>

	<div class="container" style="display:none;">
		<div class="box">
			<p>
				<a href="#" id="button" class="btn box"></a>
				<a href="#" id="saveas" target="_blank" class="download btn box" title="right click > save as svg" download="trianglify-background.svg"></a>
			</p>
			<div id="controls" class="hidden">
				<ul>
					<li>Noise: <a href="#" id="subtractnoise" class="btn box small">-</a><span id="noise">0</span><a href="#" id="addnoise" class="btn box small">+</a></li>
					<li>Cellsize: <a href="#" id="subtractcellsize" class="btn box small">-</a><span id="cellsize">0</span><a href="#" id="addcellsize" class="btn box small">+</a></li>
					<li>Cellpadding: <a href="#" id="subtractcellpadding" class="btn box small">-</a><span id="cellpadding">0</span><a href="#" id="addcellpadding" class="btn box small" title="cellpadding must be smaller than cellsize/2">+</a></li>
				</ul>
				<p></p>
			</div>
			<p><a href="#" id="togglecontrols" class="btn box small"></a></p>
		</div>
	</div>

	<script src="/Public/BJUI/js/d3.v3.min.js"></script>
	<script src="/Public/BJUI/js/trianglify.js"></script>
	<script src="/Public/BJUI/js/bg.js"></script>
</body>
</html>