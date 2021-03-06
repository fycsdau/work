<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>企业网站模板</title>
<link rel="stylesheet" href="/Public/pc/assets/css/amazeui.css" />
<link rel="stylesheet"
	href="/Public/pc/assets/css/common.min.css" />
<link rel="stylesheet"
	href="/Public/pc/assets/css/other.min.css" />
	
	<style>
		.not-use{
			    border-color: red
		}
		.can-use{
			    border-color: #5eb95e 
		}
	</style>
</head>
<body class="register-container">
	<div class="layout">
		<!--===========layout-header================-->
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
									<a href=""><img src="/Public/pc/images/logo.png"
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



			<!--===========layout-container================-->
			<div class="register-box">
				<form action="/../pcshow.php/Login/add_user" method="post" class="am-form" data-am-validator onsubmit="return toVaild()">
					<fieldset>
						<legend>
							注册用户
							<p class="register-info">账号可以使用手机或者邮箱注册，但是仔细核对后，填入正确信息。</p>
						</legend>

						<div class="am-form-group">
							<div class="am-g">
								<div class="am-u-md-2 am-padding-0 am-text-right">
									<label for="doc-vld-name-2" class="register-name">账号</label>
								</div>
								<div class="am-u-md-10">
									<input type="text" id="doc-vld-name-2" minlength="6" name="user_name" 
										placeholder="输入用户名（至少 6 个字符）" required onblur="name_can_use(this.value)" />
									<div id="can_use" style="display:none">
									</div>
								</div>
							</div>
						</div>

						<div class="am-form-group">
							<div class="am-g">
								<div class="am-u-md-2 am-padding-0 am-text-right">
									<label for="doc-vld-pwd-1" class="register-pwd">密码</label>
								</div>
								<div class="am-u-md-10">
									<input type="password" id="doc-vld-pwd-1" name="password"
										placeholder="输入密码（至少 6 个字符）"  minlength="6" required />
								</div>
							</div>
						</div>

						<div class="am-form-group">
							<div class="am-g">
								<div class="am-u-md-2 am-padding-0 am-text-right">
									<label for="doc-vld-pwd-2">确认密码</label>
								</div>
								<div class="am-u-md-10">
									<input type="password" id="doc-vld-pwd-2"
										placeholder="请与上面输入的值一致" data-equal-to="#doc-vld-pwd-1"
										required />
								</div>
							</div>
						</div>

						<div class="am-g">
							<div class="am-u-md-10">
								<button class="am-btn am-btn-secondary" type="submit">注册</button>
								<a class="am-btn am-btn-cancel" type="button" onclick="javascript:go_login()">立即登陆</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>

		</div>




		<script src="/Public/pc/assets/js/jquery-2.1.0.js"
			charset="utf-8"></script>
		<script src="/Public/pc/assets/js/amazeui.js" charset="utf-8"></script>
		<script>
			var name_u = 0;
			function go_login(){
				window.location.href="/pcshow.php/Login/index";
			}
			function toVaild(){
				if(name_u == 0){
					return false;
				}
			}
			function name_can_use(val){
				$.ajax({
					url:"/pcshow.php/Login/name_can_use",
					data:{"name":val},
					type:"post",
					success:function(data){
						if(data == 1){
							$("#doc-vld-name-2").removeClass("am-field-error");
							$("#doc-vld-name-2").addClass("am-field-valid");
							document.getElementById("can_use").innerHTML="<span style='color:#5eb95e'>可用</span>";
							document.getElementById("can_use").style.display="";
							name_u = 1;
						}else{
							$("#doc-vld-name-2").removeClass("am-field-valid");
							$("#doc-vld-name-2").addClass("am-field-error");
							document.getElementById("can_use").innerHTML="<span style='color:#dd514c'>不可用</span>";
							document.getElementById("can_use").style.display="";
							name_u = 0;
						}
					}
				});
			}
		</script>
</body>

</html>