<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>登录</title>
  <link rel="stylesheet" href="/Public/pc/assets/css/amazeui.css" />
  <link rel="stylesheet" href="/Public/pc/assets/css/other.min.css" />
	<link rel="stylesheet" href="/Public/pc/assets/css/common.min.css" />
</head>
<body class="login-container register-container">
	<div class="layout-header am-hide-sm-only">
			<!--topbar start-->
			<div class="topbar">
				<div class="container">
					<div class="am-g">
						<div class="am-u-md-3">
							<div class="topbar-left">
								<!-- 
								 <i class="am-icon-globe"></i>
								 -->
								<div class="am-dropdown" data-am-dropdown></div>
							</div>
						</div>
						<div class="am-u-md-9">
							<div class="topbar-right am-text-right am-fr">
								<a href="/pcshow.php/Login/index">登录</a> 
								<a href="/pcshow.php/Login/register">注册</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--topbar end-->

			<div class="header-box" data-am-sticky>
				<!--header start-->
				<div class="container">
					<div class="header">
						<div class="am-g">
							<div class="am-u-lg-2 am-u-sm-12">
								<div class="logo">
									<a href="/pcshow.php/Login/../Index/index"><img src="/Public/pc/images/logo.png"
										alt="" /></a>
								</div>
							</div>
							<div class="am-u-md-10">
								<div class="header-right am-fr">
									<div class="header-contact">
										<div class="header_contacts--item">
											<div class="contact_mini">
												<i style="color: #7c6aa6"
													class="contact-icon am-icon-envelope-o"></i> <strong>923383346@qq.com</strong>
												<span>随时欢迎您的来信！</span>
											</div>
										</div>
										<!-- 
										<div class="header_contacts--item">
											<div class="contact_mini">
												<i style="color: #7c6aa6"
													class="contact-icon am-icon-map-marker"></i> <strong>天使大厦,</strong>
												<span>海淀区海淀大街27</span>
											</div>
										</div>
										 -->
									</div>
									<a href="html/contact.html" class="contact-btn">
										<button type="button"
											class="am-btn am-btn-secondary am-radius">联系我们</button>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--header end-->

			</div>
			</div>
  <div class="login-box">
    <form action="/pcshow.php/Login/login" class="am-form" data-am-validator method="POST">
      <div class="am-form-group">
        <label for="doc-vld-name-2"><i class="am-icon-user"></i></label>
        <input type="text" name="username" id="doc-vld-name-2" minlength="6" placeholder="输入用户名（至少 6 个字符）" required/>
      </div>

      <div class="am-form-group">
        <label for="doc-vld-email-2"><i class="am-icon-key"></i></label>
        <input type="password" name="password" id="doc-vld-email-2" placeholder="输入密码" required/>
      </div>
      <div class="am-form-group">
      	<input type="text" name="code" id="doc-vld-email-2" placeholder="输入验证码" style="width:150px;" required/>
        <img src='/pcshow.php/Login/verify/' id="verifyImg" onclick="fleshVerify()" />
      </div>
     
      <button class="am-btn am-btn-secondary"  type="submit">登录</button>
      <button class="am-btn am-btn-cancel" type="button" onclick="javascript:history.go(-1)" >取消</button>
    </form>
  </div>
</body>
<script language="JavaScript">
	function fleshVerify(){ 
	  //重载验证码
	  var time = new Date().getTime();
	  document.getElementById('verifyImg').src= '/pcshow.php/Login/verify/'+time;
	}
</script>
</html>