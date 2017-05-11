<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>我要招聘</title>
		<link rel="stylesheet" href="/Public/pc/assets/css/amazeui.css" />
		<link rel="stylesheet" href="/Public/pc/assets/css/common.min.css" />
		<link rel="stylesheet" href="/Public/pc/assets/css/contact.min.css" />

		<link href="/Public/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="/Public/umeditor/third-party/jquery.min.js"></script>
		<script type="text/javascript" src="/Public/umeditor/third-party/template.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="/Public/umeditor/umeditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="/Public/umeditor/umeditor.min.js"></script>
		<script type="text/javascript" src="/Public/umeditor/lang/zh-cn/zh-cn.js"></script>

		<style type="text/css">
			h1 {
				font-family: "微软雅黑";
				font-weight: normal;
			}
			
			.btn {
				display: inline-block;
				*display: inline;
				padding: 4px 12px;
				margin-bottom: 0;
				*margin-left: .3em;
				font-size: 14px;
				line-height: 20px;
				color: #333333;
				text-align: center;
				text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
				vertical-align: middle;
				cursor: pointer;
				background-color: #f5f5f5;
				*background-color: #e6e6e6;
				background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
				background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
				background-repeat: repeat-x;
				border: 1px solid #cccccc;
				*border: 0;
				border-color: #e6e6e6 #e6e6e6 #bfbfbf;
				border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
				border-bottom-color: #b3b3b3;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
				filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
				filter: progid: DXImageTransform.Microsoft.gradient(enabled=false);
				*zoom: 1;
				-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
				-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
				box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
			}
			
			.btn:hover,
			.btn:focus,
			.btn:active,
			.btn.active,
			.btn.disabled,
			.btn[disabled] {
				color: #333333;
				background-color: #e6e6e6;
				*background-color: #d9d9d9;
			}
			
			.btn:active,
			.btn.active {
				background-color: #cccccc \9;
			}
			
			.btn:first-child {
				*margin-left: 0;
			}
			
			.btn:hover,
			.btn:focus {
				color: #333333;
				text-decoration: none;
				background-position: 0 -15px;
				-webkit-transition: background-position 0.1s linear;
				-moz-transition: background-position 0.1s linear;
				-o-transition: background-position 0.1s linear;
				transition: background-position 0.1s linear;
			}
			
			.btn:focus {
				outline: thin dotted #333;
				outline: 5px auto -webkit-focus-ring-color;
				outline-offset: -2px;
			}
			
			.btn.active,
			.btn:active {
				background-image: none;
				outline: 0;
				-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
				-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
				box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
			}
			
			.btn.disabled,
			.btn[disabled] {
				cursor: default;
				background-image: none;
				opacity: 0.65;
				filter: alpha(opacity=65);
				-webkit-box-shadow: none;
				-moz-box-shadow: none;
				box-shadow: none;
			}
		</style>
	</head>

	<body>
		<div class="layout">
			<!--===========layout-header================-->
			<div class="layout-header am-hide-sm-only">
				<!--topbar start-->
				<div class="topbar">
					<div class="container">
						<div class="am-g">
							<div class="am-u-md-3 topbar-left">
								<div class="am-dropdown" data-am-dropdown></div>
							</div>
							<div class="am-u-md-9 topbar-right am-text-right am-fr">
								<?php if(($_SESSION['username'] != '')): ?><span style="margin-right: 40px;"><?php echo ($_SESSION['username']); ?></span>
								<?php else: ?>
									<a href="/pcshow.php/Index/../Login/index" style="margin-right:10px">登录</a>
									<a href="/pcshow.php/Index/../Login/register" style="margin-right:40px">注册</a><?php endif; ?>
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
										<a href="/pcshow.php/Index/../Index/index"><img src="/Public/pc/images/logo.png" alt="" /></a>
									</div>
								</div>
								<div class="am-u-md-10">
									<div class="header-right am-fr">
										<div class="header-contact">
											<div class="header_contacts--item">
												<div class="contact_mini">
													<i style="color: #7c6aa6" class="contact-icon am-icon-envelope-o"></i> <strong>923383346@qq.com</strong>
													<span>随时欢迎您的来信！</span>
												</div>
											</div>
										</div>
										<a href="html/contact.html" class="contact-btn">
											<button type="button" class="am-btn am-btn-secondary am-radius">联系我们</button>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--header end-->
				</div>

			</div>
		</div>

		<!--===========layout-container================-->

		<div class="section">
			<div class="container">
				<div class="section--header">
					<p class="section--description">
						请如实填写您的招聘信息，我们将进行核实，若核实有误该信息将无效！<br/>
					</p>
				</div>

				<div class="section-container">
					<div class="am-g">
						<!--contact-right start-->
						<div class="am-u-md-7">
							<div class="contact-form">
								<form class="am-form" action="save_job" method="post" onsubmit="return toVaild()">
									<div class="am-g">
										<div class="am-u-md-6">
											公司名称:<input type="text" name="company_name" placeholder="公司名称" required>
										</div>
										<div class="am-u-md-6">
											招聘标题:<input type="text" name="job_name" placeholder="招聘标题，例：急需铲车司机数名，待遇优厚" required>
										</div>
									</div>
									<div class="am-g">
										<div class="am-u-md-6">
											职位名称:<input type="text" name="job_type" placeholder="职位名称，例：铲车司机" required>
										</div>
										<div class="am-u-md-6">
											简介:<input type="text" name="job_desc" placeholder="简介" required>
										</div>
									</div>
									<div class="am-g">
										<div class="am-u-md-6">
											联系人:<input type="text" name="con_name" placeholder="联系人" required>
										</div>
										<div class="am-u-md-6">
											联系电话:<input type="tel" name="con_mobile" placeholder="联系电话" onblur="validatemobile(this.value)" required>
										</div>
									</div>
									<div class="am-g">
										<div class="am-u-md-6">
											工资待遇:<input type="text" name="job_money" placeholder="工资待遇" required>
										</div>
										<div class="am-u-md-6">
											招聘人数:<input type="text" name="job_count" placeholder="招聘人数" required>
										</div>
									</div>
								
									<div class="am-g" style="height:105px;">
										<div class="am-u-md-6" style="height:74px;">
											<div class="am-form-group" style="background: #fcfcfc;">
												学历:<select name="educational" style="width: 100%;font-size: 16px;line-height: 20px;padding: 15px 20px;border-radius: 3px;color: #999999;border: 2px solid #e9e9e9;">
													<option value="0">学历不限</option>
													<option value="1">初中</option>
													<option value="2">高中</option>
													<option value="3">专科</option>
													<option value="4">本科</option>
													<option value="5">硕士</option>
													<option value="6">博士</option>
												</select>
											</div>
										</div>
										<div class="am-u-md-6" style="height:74px;">
											<div class="am-form-group" style="background: #fcfcfc;">
												工作经验:<select name="experience" style="width: 100%;font-size: 16px;line-height: 20px;padding: 15px 20px;border-radius: 3px;color: #999999;border: 2px solid #e9e9e9;">
													<option value="0">经验不限</option>
													<option value="1">一年以下</option>
													<option value="2">一到两年</option>
													<option value="3">两到三年</option>
													<option value="4">三到五年</option>
													<option value="5">五年以上</option>
												</select>
											</div>
										</div>
									</div>
									<div class="am-g">
										<div class="am-u-md-6">
											工作地址:<input type="text" name="job_address" placeholder="工作地址" required>
										</div>
										<div class="am-u-md-6"  style="height:105px;">
											<div class="am-form-group" style="background: #fcfcfc;">
												招聘状态:<select name="use_status" style="width: 100%;font-size: 16px;line-height: 20px;padding: 15px 20px;border-radius: 3px;color: #999999;border: 2px solid #e9e9e9;">
													<option value="0" <?php if($job_info["use_status"] == 0): ?>selected<?php endif; ?>>关闭</option>
													<option value="1" <?php if($job_info["use_status"] == 1): ?>selected<?php endif; ?>>正常</option>
												</select>
											</div>
										</div>
									</div>

									<div class=am-g>
										<div class="am-u-md-12">
											<div class="am-form-group">
												详情介绍:<div id="edit_text" style="display:none">
													<script type="text/plain" id="myEditor" style="width:100%;height:260px;">
														<p>详情介绍</p>
													</script>
												</div>
												<div id="show_text" style="line-height: 10px;display: block;width: 100%;font-size: 16px;padding: 15px 20px;border-radius: 3px;color: #999999;border: 2px solid #e9e9e9;">
													<p>详情介绍</p>
												</div>
												<div id="btns">
													<a class="btn" onclick="setContent()">编辑</a>&nbsp;
												</div>
												<input type="hidden" name="job_content" id="job_content" value="" />
											</div>
										</div>
									</div>
									<div class="am-g" style="padding-left:0.7em;padding-right:0.7em;" id="job_image">
									</div>
									<div class="am-g">
										<div class="am-u-md-9">
											<div class="am-form-group am-form-file">
												<button type="button" class="am-btn am-btn-default am-btn-sm btn-change">
                        							<i class="am-icon-cloud-upload"></i> 上传图片
												</button>
												<input type="file" id="sortPicture" name="file" multiple style="width:120px;">
												<input type="hidden" id="job_image_input" name="job_image_input" value="" >
											</div>
										</div>
										<div class="am-u-md-3">
											<p><button type="submit" class="am-btn am-btn-default btn-reguest am-fr btn-fl">提交</button></p>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!--contact-right end-->
					</div>
				</div>
			</div>
		</div>
		</div>

		<!--===========layout-container================-->
		<div class="layout-footer">
			<div class="footer">
				<div style="background-color:#383d61" class="footer--bg"></div>
				<div class="footer--inner">
					<div class="container">
						<div class="footer_main">
							<div class="am-g">
								<!--
								<div class="am-u-md-3 ">
									<div class="footer_main--column">
										<strong class="footer_main--column_title">关于我们</strong>
										<div class="footer_about">
											<p class="footer_about--text">
												云适配(AllMobilize Inc.) 是全球领先的HTML5企业移动化解决方案供应商，由前微软美国总部IE浏览器核心研发团队成员及移动互联网行业专家在美国西雅图创立.
											</p>
										</div>
									</div>
								</div>

								<div class="am-u-md-3 ">
									<div class="footer_main--column">
										<strong class="footer_main--column_title">产品中心</strong>
										<ul class="footer_navigation">
											<li class="footer_navigation--item">
												<a href="#" class="footer_navigation--link">Enterplorer 企业浏览器</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="am-u-md-3 ">
									<div class="footer_main--column">
										<strong class="footer_main--column_title">技术支持</strong>
										<ul class="footer_navigation">
											<li class="footer_navigation--item">
												<a href="#" class="footer_navigation--link">企业移动信息化白皮书</a>
											</li>
										</ul>
									</div>
								</div>
-->
								<div class="am-u-md-3 ">
									<div class="footer_main--column">
										<strong class="footer_main--column_title">联系详情</strong>
										<ul class="footer_contact_info">
											<li class="footer_contact_info--item"><i class="am-icon-phone"></i><span>服务专线：400 069 0309</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="/Public/pc/assets/js/jquery-2.1.0.js" charset="utf-8"></script>
		<script src="/Public/pc/assets/js/amazeui.js" charset="utf-8"></script>
		<script type="text/javascript">
			//实例化编辑器
			var um = UM.getEditor('myEditor');
			um.addListener('blur', function() {
				$('#focush2').html('编辑器失去焦点了')
			});
			um.addListener('focus', function() {
				$('#focush2').html('')
			});
			var cc = 1;

			um.setWidth("100%");
			$(".edui-body-container").css("width", "100%");

			function validatemobile(mobile) { 
		       	if(mobile.length==0) { 
		          	alert('请输入手机号码！'); 
		          	document.form1.mobile.focus(); 
		          	return false; 
		       	}     
		       	if(mobile.length!=11) { 
		        	alert('请输入有效的手机号码！'); 
		        	document.form1.mobile.focus(); 
		           	return false; 
		       	} 
		        
		       	var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
		       	if(!myreg.test(mobile)) { 
		           	alert('请输入有效的手机号码！'); 
		           	document.form1.mobile.focus(); 
		           	return false; 
		       	} 
		   	} 
			function toVaild(){
				var job_image_input = document.getElementById("job_image_input").value;
				var job_content = document.getElementById("job_content").value;
				var con_mobile = document.getElementById("con_mobile").value;
				validatemobile(con_mobile);
				if(job_content.length <= 0){
					alert("请查看您输入的详情，并点击确定");
					return false;
				}
				if(job_image_input.length <= 0){
					alert("请上传至少一张图片");
					return false;
				}
			}
			function getContent() {
				var arr = [];
				//      arr.push(UM.getEditor('myEditor').getContent());
				//      alert(arr.join("\n"));
				//alert(UM.getEditor('myEditor').getContent());
				document.getElementById("show_text").innerHTML = UM.getEditor('myEditor').getContent();
				document.getElementById("edit_text").style.display = "none";
				document.getElementById("show_text").style.display = "block";
				document.getElementById("btns").innerHTML = '<a class="btn" onclick="setContent()">编辑</a>';
				document.getElementById("job_content").value = UM.getEditor('myEditor').getContent();
				cc = 1;
			}

			function setContent() {
				var html = document.getElementById("show_text").innerHTML;
				UM.getEditor('myEditor').setContent('', html);
				document.getElementById("edit_text").style.display = "block";
				document.getElementById("show_text").style.display = "none";
				document.getElementById("btns").innerHTML = '<a class="btn" onclick="getContent()">确定</a>';
				cc = 0;
			}
			
			var haveimage = <?php echo ($haveimage); ?>;
			//上传图片
			var photo = $('#sortPicture');
			photo.on('change', function(event){
				if(haveimage >= 6){
					alert("对不起，您最多上传6张图片");
					return false;
				}
				compress(event, function(base64Img){
					$.ajax({
						'url' : 'upload',
						'type' : 'post',
						'data' : {'base64Img' : base64Img},
						'success' : function(ret){
							var obj = eval( "(" + ret + ")" );
							var imgurl = obj.url + obj.imageName;
							var job_image = document.getElementById("job_image").innerHTML
							var job_image_input = document.getElementById("job_image_input").value;
							if(imgurl){
								job_image = job_image + '<img src="/'+imgurl+'" alt=" " style="width: 16.3%;margin-top: 5px;">';
								document.getElementById("job_image").innerHTML = job_image;
								haveimage = haveimage + 1;
								if(job_image_input == ""){
									job_image_input = imgurl;
								}else{
									job_image_input = job_image_input + "," + imgurl;
								}
								document.getElementById("job_image_input").value = job_image_input;
							}
						}
					});
				});
			});
			 
			function compress(event, callback){
			    var file = event.currentTarget.files[0];
			    var reader = new FileReader();
			    reader.onload = function (e) {
			        var image = $('<img/>');
			        image.on('load', function () {
					var square = 700;
					var canvas = document.createElement('canvas');
					canvas.width = square;
					canvas.height = square;
					var context = canvas.getContext('2d');
					context.clearRect(0, 0, square, square);
					var imageWidth;
					var imageHeight;
					var offsetX = 0;
					var offsetY = 0;
			            if (this.width > this.height) {
			                imageWidth = Math.round(square * this.width / this.height);
			                imageHeight = square;
			                offsetX = - Math.round((imageWidth - square) / 2);
			           	} else {
			                imageHeight = Math.round(square * this.height / this.width);
			                imageWidth = square; 
			                offsetY = - Math.round((imageHeight - square) / 2); 
			           	}
			 
			            context.drawImage(this, offsetX, offsetY, imageWidth, imageHeight);
			            var data = canvas.toDataURL('image/jpeg');
			            callback(data);
			        });
			 
			        image.attr('src', e.target.result);
			    };
			  
			    reader.readAsDataURL(file);
			}
		</script>

	</body>

</html>