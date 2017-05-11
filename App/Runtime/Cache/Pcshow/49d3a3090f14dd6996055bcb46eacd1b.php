<?php if (!defined('THINK_PATH')) exit();?><html class="translated-ltr"><head>
<title>首页</title>
<link href="/Public/pc/css/style.css" rel="stylesheet" type="text/css" media="all">
<link href="/Public/pc/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- js -->
<script src="/Public/pc/js/jquery.min.js"></script>
<!-- //js -->
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="Bizz Wow Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design">
<script type="application/x-javascript"> 
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
	function hideURLbar(){ window.scrollTo(0,1); } </script>
<script type="text/javascript" src="/Public/pc/js/move-top.js"></script>
<script type="text/javascript" src="/Public/pc/js/easing.js"></script>

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
		</ul>
	</div>
	<?php if($from_action == 'job_index'): ?><div style="float:right;margin-right:10px;margin-top:2rem;width:25%;">
			<form action="" method="post">
				<input type="text" name="sel_input" value="<?php echo ($sql_input); ?>" style="padding-left:1rem;height:5%;width:65%;border-radius:5px;border:2px solid #e9e9e9;" />
				<button style="height:5%;width:23%;border-radius:5px;border:1px solid #FFAE00;font-size: 1.5rem;background-color:#FFAE00;color:#FFFFFF;">搜索</button>
			</form>
		</div><?php endif; ?>
	<div class="clearfix"> </div>
	<script> 
		$( "span.menu" ).click(function() {
			$( "ul.nav1" ).slideToggle( 300, function() {
			});
		});
	</script>
</div>
		<div class="banner">
			<script src="/Public/pc/js/responsiveslides.min.js"></script>
		 	<script>
			    $(function () {
			      $("#slider3").responsiveSlides({
			        auto: true,
			        pager: false,
			        nav: true,
			        speed: 500,
			        namespace: "callbacks",
			        before: function () {
			          $('.events').append("<li>before event fired.</li>");
			        },
			        after: function () {
			          $('.events').append("<li>after event fired.</li>");
			        }
			      });
			
			    });
		  	</script>
			<div id="top" class="callbacks_container wow fadeInUp" data-wow-delay="0.5s">
				<ul class="rslides callbacks callbacks1" id="slider3">
					<li id="callbacks1_s0" class="" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;">
						<div class="banner1">
							<img style="width:50%;float: left;border:1px solid #FFFFFF;" src="/Public/pc/images/banner1.jpg"/>
							<img style="width:50%;border:1px solid #FFFFFF;" src="/Public/pc/images/banner2.jpg"/>
						</div>
					</li>
					<li id="callbacks1_s1" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;" class="">
						<div class="banner2">
							<img style="width:50%;float: left;border:1px solid #FFFFFF;" src="/Public/pc/images/banner2.jpg"/>
							<img style="width:50%;border:1px solid #FFFFFF;" src="/Public/pc/images/banner3.jpg"/>
						</div>
					</li>
					<li id="callbacks1_s2" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;" class="">
						<div class="banner2">
							<img style="width:50%;float: left;border:1px solid #FFFFFF;" src="/Public/pc/images/banner3.jpg"/>
							<img style="width:50%;border:1px solid #FFFFFF;" src="/Public/pc/images/banner5.jpg"/>
						</div>
					</li>
					<li id="callbacks1_s2" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;" class="">
						<div class="banner2">
							<img style="width:50%;float: left;border:1px solid #FFFFFF;" src="/Public/pc/images/banner5.jpg"/>
							<img style="width:50%;border:1px solid #FFFFFF;" src="/Public/pc/images/banner1.jpg"/>
						</div>
					</li>
				</ul><a href="#" class="callbacks_nav callbacks1_nav prev"><font><font>上一页</font></font></a><a href="#" class="callbacks_nav callbacks1_nav next"><font><font>下一页</font></font></a>
			</div>			
	</div>
<div class="copyrights"><font><font>从</font><a href="http://www.cssmoban.com/"><font>网页模板</font></a><font>收集</font></font><a href="http://www.cssmoban.com/"><font></font></a></div>
	<div id="feature" style="background-color:#ffffff;min-height:100px;">
		<?php if(is_array($job_list)): $i = 0; $__LIST__ = $job_list;if( count($__LIST__)==0 ) : echo "暂时没有工作岗位" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="/pcshow.php/Index/job_info?job_id=<?php echo ($v["job_id"]); ?>" onmouseover="javascript:onjob(<?php echo ($v["job_id"]); ?>)" onmouseout="outjob()">
				<div class="banner-bottom-grids">
					<div class="banner-bottom-grid" id="job_<?php echo ($v["job_id"]); ?>">
						<div class="rod-fig">
							<span> </span>
						</div>
						<div class="um-text">
							<h4 style="font-weight: bold;"><?php echo ($v["job_name"]); ?></h4>
							<p><font><font><?php echo ($v["desc"]); ?></font></font></p>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
			</a><?php endforeach; endif; else: echo "暂时没有工作岗位" ;endif; ?>
		<div class="clearfix"> </div>
	</div>
	<div id="about" class="work" style="margin: 0em 0;height: 95px;">
		<div class="buy">
			<div class="buy-text">
				<h3>你还在为找工作犹豫不决吗？你还在为找不到合适的伙伴发愁吗？加入我们，你会得到意想不到的收获！</h3>
			</div>
			<div class="buy-now">
				<?php if(($_SESSION['username'] != '')): ?><a href="index?more=1" class="hvr-bounce-to-left">查看更多</a>
				<?php else: ?>
					<a href="/pcshow.php/Index/../Login/index" class="hvr-bounce-to-left">立即加入</a><?php endif; ?>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<div class="footer-top">
		<div class="footer-top-grid">
			<h3>关于我们</h3>
			<p>
				仅提供信息交流平台，如有任何问题请与客服联系
			</p>
		</div>
		<div class="footer-top-grid">
			<h3>联系客服</h3>
			<div class="unorder">
				<ul class="tag2">
					<li><a>QQ：923383346</a></li>
					
				</ul>
				<ul class="tag2">
					<li><a>微信：18854883621</a></li>
				</ul>
				<ul class="tag2">
					<li><a>邮箱：923383346@qq.com</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-top-grid">
			<h3>涉及内容</h3>
			<div class="unorder">
				<ul class="tag2">
					<li><a>招聘</a></li>
					
				</ul>
				<ul class="tag2">
					<li><a>二手交换</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-top-grid">
			<h3>FLICKR <span>FEED</span></h3>
			<div class="flickr-grids">
				<div class="flickr-grid">
					<img src="/Public/pc/images/yixue.png" alt=" " title="CEO">
				</div>
				<div class="clearfix"> </div>
				
			</div>
		</div>
		<div class="clearfix"> </div>
	</div>

	</div>
	</div>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({ easingType: 'easeOutQuart' });
		});
		
		function onjob(id){
			var job=document.getElementsByClassName('banner-bottom-grid');
				document.getElementById("job_"+id).style.background="#ccc";
		}
		function outjob(){
			var job=document.getElementsByClassName('banner-bottom-grid');
			for(var i=0;i<job.length;i++){
				job[i].style.background="#fff";
			}
		}
	</script>
<!-- //here ends scrolling icon -->