<?php if (!defined('THINK_PATH')) exit();?><html class="translated-ltr"><head>
<title>详情</title>
<link href="/Public/pc/css/style.css" rel="stylesheet" type="text/css" media="all">
<link href="/Public/pc/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!--<link href="http://fonts.useso.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet" type="text/css">
<link href="http://fonts.useso.com/css?family=Titillium+Web:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900" rel="stylesheet" type="text/css">-->
<!-- js -->
<script src="/Public/pc/js/jquery.min.js"></script>
<!-- //js -->
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="Bizz Wow Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="/Public/pc/js/move-top.js"></script>
<script type="text/javascript" src="/Public/pc/js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
<link type="text/css" rel="stylesheet" charset="UTF-8" href="https://translate.googleapis.com/translate_static/css/translateelement.css"></head>
	
<body>
	<div class="psd">
	<div class="container">
		<div class="top" style="background-color:#fff;height:3em;text-align: right;padding-top: 10px; font-weight: bold;">
	<?php if(($_SESSION['username'] != '')): ?><span style="margin-right: 40px;"><?php echo ($_SESSION['username']); ?></span>
	<?php else: ?>
		<a href="/pcshow.php/Index/../Login/index" style="margin-right:10px">登录</a>
		<a href="/pcshow.php/Index/../Login/register" style="margin-right:40px">注册</a><?php endif; ?>
</div>
<div class="header">
	<div class="logo">
		<a href="index.html"><img style="" src="/Public/pc/images/logo.png" alt=" "></a>
	</div>
	<div class="logo-right">
		<span class="menu"><img src="/Public/pc/images/menu.png" alt=" "></span>
		<ul class="nav1">
			<li class="cap"><a href="/pcshow.php/Index/../Index/add_job" class="act"><font><font>发布招聘</font></font><span><font><font></font></font></span></a></li>
			<li class="cap"><a href="/pcshow.php/Index/../Index/myjob" class="act1 scroll"><font><font>我的招聘</font></font><span><font><font></font></font></span></a></li>
			<!--
			<li><a href="#feature" class="act2 scroll"><font><font>特点</font></font><span><font><font>我们提供</font></font></span></a></li>
			<li><a href="blog.html" class="act3"><font><font>博客</font></font><span><font><font>阅读新闻</font></font></span></a></li>
			<li><a href="portfolio.html" class="act4"><font><font>PORTFOLIO </font></font><span><font><font>我们的工作</font></font></span></a></li>
			<li><a href="contact.html" class="act5"><font><font>联系我们</font></font><span><font><font>获取触摸</font></font></span></a></li>
			-->
		</ul>
	</div>
	<div class="clearfix"> </div>
	<!-- script for menu -->
		<script> 
			$( "span.menu" ).click(function() {
			$( "ul.nav1" ).slideToggle( 300, function() {
			 // Animation complete.
			});
			});
		</script>
	<!-- //script for menu -->
</div>
	<div class="single">
		<div class="blog-left">
			<div class="blog-left-info">
				<h3><font><font><?php echo ($job_info["job_name"]); ?></font></font></h3>
				<img src="/<?php echo ($job_info["img"]); ?>" alt=" ">
				<ul>
					<li class="date"><font><font>发布时间：<?php echo ($job_info["add_time"]); ?></font></font></li>
					<li class="com"><a href="javascript:void(0)" class="cmn"><font><font><?php echo ($job_info["browse_count"]); ?>次浏览</font></font></a></li>
				</ul>
				<?php echo ($job_content); ?>
			</div>
			
			<div class="author">
				<h3><font><font>联系方式（客服）</font></font></h3>
				<div class="author-right">
					<p><font><font>姓名：<?php echo ($boss_info["name"]); ?></font></font></p>
					<p><font><font>电话：<?php echo ($boss_info["mobile"]); ?></font></font></p>
					<p><font><font>Q Q：<?php echo ($boss_info["qq"]); ?></font></font></p>
					<p><font><font>微信：<?php echo ($boss_info["weixin"]); ?></font></font></p>
					<p><font><font>邮箱：<?php echo ($boss_info["email"]); ?></font></font></p>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="blog-left-bottom">
				<h3><font><font>相关招聘</font></font></h3>
				<div class="blog-left-bottom-grids">
					<?php if(is_array($con_job)): $i = 0; $__LIST__ = $con_job;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="blog-left-bottom-grid" id="job_<?php echo ($v["job_id"]); ?>" onclick="window.location.href='job_info?job_id=<?php echo ($v["job_id"]); ?>'" onmouseover="javascript:onjob(<?php echo ($v["job_id"]); ?>)" onmouseout="outjob()">
							<img src="/<?php echo ($v["img"]); ?>" alt=" " title="<?php echo ($type_name["type_name"]); ?>">
							<h4 style="color:#F9A801"><font><font><?php echo ($v["job_name"]); ?></font></font></h4>
							<p><font><font><?php echo ($v["desc"]); ?></font></font></p>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<div class="clearfix"> </div>
				</div>
			</div>
			
		</div>
		<div class="blog-right">
			<h3><font><font>简介</font></font></h3>
			<ul>
				<li><a href="javascript:void(0)"><font><font>公司：红专复印</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>职位：打字员</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>要求：分钟打字100</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>招聘人数：8人</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>学历：不限</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>经验：不限</font></font></a></li>
				<li><a href="javascript:void(0)"><font><font>地址：山东即墨</font></font></a></li>
				
			</ul>
			<div class="recent">
				<h3><font><font>最新评论</font></font></h3>
				<div class="recent-grids">
					<?php if(is_array($commit_list)): $i = 0; $__LIST__ = $commit_list;if( count($__LIST__)==0 ) : echo "暂无评论" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="recent-grid">
							<div class="wom">
								<a href="#"><img src="/Public/pc/images/ff.jpg" alt=" "></a>
							</div>
							<div class="wom-right">
								<h4><a href="#"><font><font>整个足球妆前</font></font></a></h4>
								<p><font><font>最大的热身是伟大的。</font><font>SEM狮子。</font><font class="">该地区的痛苦，减少电网，烟花运行抵扣服务。</font></font></p>
							</div>
							<div class="clearfix"> </div>
						</div><?php endforeach; endif; else: echo "暂无评论" ;endif; ?>
				</div>
			</div>
			
			<div class="poll">
				<h3><font><font>高需求工作</font></font></h3>
				<div class="clearfix"> </div>
				<?php if(is_array($high_demand_job)): $i = 0; $__LIST__ = $high_demand_job;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="bar">
						<p><font><font><?php echo ($v["type_name"]); ?></font></font></p>
					</div>
					<div class="bar1">
						<p><?php echo ($v["now_count"]); ?>份</p>
					</div>
					<div class="clearfix"> </div>
					<div class="skills">
					 	<div class="skill1" style="width:<?php echo ($v["now_count_rate"]); ?>%"> </div>							
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<div class="clearfix"> </div>
	</div>
<!-- single -->
<!-- footer -->
	<div class="footer">
		<div class="footer-left">
			<ul>
				<li><a href="index.html"><img src="/Public/pc/images/logo_bottom.png" alt=" "></a><span><font><font> |</font></font></span></li>
				<li><p><font><font>旭日升信息交流平台</font></font><span><font></font></span></p></li>
			</ul>
		</div>
		<div class="footer-right">
			<p><font><font>版权所有©2015.Company名称的所有权利reserved.More设计</font></font><a href="http://www.cssmoban.com/" target="_blank" title="模板之家"><font><font>模板之家</font></font></a><font><font> -从收藏</font></font><a href="http://www.cssmoban.com/" title="网页模板" target="_blank"><font><font>网页模板</font></font></a></p>
		</div>
		<div class="clearfix"> </div>
	</div>
<!-- //footer -->
	</div>
	</div>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({ easingType: 'easeOutQuart' });
		});
		function onjob(id){
			var job=document.getElementsByClassName('blog-left-bottom-grid');
				document.getElementById("job_"+id).style.background="#f5f5f5";
		}
		function outjob(){
			var job=document.getElementsByClassName('blog-left-bottom-grid');
			for(var i=0;i<job.length;i++){
				job[i].style.background="#fff";
			}
		}
	</script>
<!-- //here ends scrolling icon -->