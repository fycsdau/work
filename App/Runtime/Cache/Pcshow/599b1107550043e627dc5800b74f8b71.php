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
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="/Public/pc/js/move-top.js"></script>
<script type="text/javascript" src="/Public/pc/js/easing.js"></script>

<!-- start-smoth-scrolling -->
	
<body>
	<div class="psd">
	<div class="container">
		<!-- header -->
		<div class="top" style="background-color:#fff;height:3em;text-align: right;padding-top: 10px; font-weight: bold;">
	<?php if(($_SESSION['username'] != '')): ?><span style="margin-right: 40px;"><?php echo ($_SESSION['username']); ?></span>
	<?php else: ?>
		<a href="/pcshow.php/Index/../Login/index" style="margin-right:10px">登录</a>
		<a href="/pcshow.php/Index/../Login/register" style="margin-right:40px">注册</a><?php endif; ?>
</div>
<div class="header">
	<div class="logo">
		<a href="index.html"><img src="/Public/pc/images/logo.png" alt=" "></a>
	</div>
	<div class="logo-right">
		<span class="menu"><img src="/Public/pc/images/menu.png" alt=" "></span>
		<ul class="nav1">
			<li class="cap"><a href="/pcshow.php/Index/../Index/add_job" class="act"><font><font>发布招聘</font></font><span><font><font></font></font></span></a></li>
			<!--
			<li><a href="#about" class="act1 scroll"><font><font>关于我们</font></font><span><font><font>知道我们</font></font></span></a></li>
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
		<!-- //header -->
	
		<!-- banner -->
		<div class="banner">
			<!-- Slider-starts-Here -->
			<script src="/Public/pc/js/responsiveslides.min.js"></script>
		 	<script>
			    // You can also use "$(window).load(function() {"
			    $(function () {
			      // Slideshow 4
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
			<!--//End-slider-script -->
			<div id="top" class="callbacks_container wow fadeInUp" data-wow-delay="0.5s">
				<ul class="rslides callbacks callbacks1" id="slider3">
					<li id="callbacks1_s0" class="" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;">
						<div class="banner1">
							<img style="width:50%;float: left;" src="/Public/pc/images/ban.jpg"/>
							<img style="width:50%" src="/Public/pc/images/bann.jpg"/>
						</div>
					</li>
					<li id="callbacks1_s1" style="display: block; float: none; position: absolute; opacity: 0; z-index: 1; transition: opacity 500ms ease-in-out;" class="">
						<div class="banner2">
							<img style="width:50%;float: left;" src="/Public/pc/images/bann.jpg"/>
							<img style="width:50%" src="/Public/pc/images/ban.jpg"/>
						</div>
					</li>
				</ul><a href="#" class="callbacks_nav callbacks1_nav prev"><font><font>上一页</font></font></a><a href="#" class="callbacks_nav callbacks1_nav next"><font><font>下一页</font></font></a>
			</div>			
	</div>
<!-- //banner -->
<div class="copyrights"><font><font>从</font><a href="http://www.cssmoban.com/"><font>网页模板</font></a><font>收集</font></font><a href="http://www.cssmoban.com/"><font></font></a></div>
<!-- banner-bottom -->
	<div id="feature" class="banner-bottom">
		<?php if(is_array($job_list)): $i = 0; $__LIST__ = $job_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="/pcshow.php/Index/job_info?job_id=<?php echo ($v["job_id"]); ?>" onmouseover="javascript:onjob(<?php echo ($v["job_id"]); ?>)" onmouseout="outjob()">
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
			</a><?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="clearfix"> </div>
	</div>
<!-- //banner-bottom -->
<!-- work -->
	<div id="about" class="work" style="margin: 0em 0;">
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
	<!--
	<div id="about" class="work">
		<div class="line">
			<span> </span>
		</div>
		<div class="awesome">
			<div class="awesome-left">
				<h3>THE AWESOME <span>WORK.</span></h3>
				<p>Too many of us look upon Americans as dollar chasers.
					This is a cruel libel, even if it is reiterated thoughtlessly.</p>
				<a href="single.html" class="hvr-bounce-to-left1">ALL WORK.</a>
			</div>
			<div class="awesome-right">
				<div class="awesome-right-grid">
					<a href="/Public/pc/images/3-.jpg" class="b-link-stripe b-animate-go   swipebox" title="">
						<img class="one" src="/Public/pc/images/3.jpg" alt=" " title="Science Laboratory">
						<div class="b-wrapper">
							<h2 class="b-animate b-from-left    b-delay03 ">
								<img class="img-responsive" src="/Public/pc/images/plus.png" alt="">
							</h2>
						</div>
					</a>
					<h4>HAVING SOME LAUNCH</h4>
					<p>Webdesign // Photography</p>				
					<div class="social">
						<ul>
							<li><a href="single.html" class="cam"> </a></li>
							<li><a href="single.html" class="gal"> </a></li>
							<li><a href="single.html" class="lin"> </a></li>
						</ul>
					</div>
				</div>
				<div class="awesome-right-grid">
					<a href="/Public/pc/images/1-.jpg" class="b-link-stripe b-animate-go   swipebox" title="">
						<img class="one" src="/Public/pc/images/1.jpg" alt=" " title="Science Laboratory">
						<div class="b-wrapper">
							<h2 class="b-animate b-from-left    b-delay03 ">
								<img class="img-responsive" src="/Public/pc/images/plus.png" alt="">
							</h2>
						</div>
					</a>
					<h4>TAKE YOUR TIME AND RELAX</h4>
					<p>Webdesign // Photography</p>				
					<div class="social">
						<ul>
							<li><a href="single.html" class="cam"> </a></li>
							<li><a href="single.html" class="gal"> </a></li>
							<li><a href="single.html" class="lin"> </a></li>
						</ul>
					</div>
				</div>
				<div class="awesome-right-grid">
					<a href="/Public/pc/images/2-.jpg" class="b-link-stripe b-animate-go   swipebox" title="">
						<img class="one" src="/Public/pc/images/2.jpg" alt=" " title="Science Laboratory">
						<div class="b-wrapper">
							<h2 class="b-animate b-from-left    b-delay03 ">
								<img class="img-responsive" src="/Public/pc/images/plus.png" alt="">
							</h2>
						</div>
					</a>
					<h4>WIRES...WIRES EVERYWHERE</h4>
					<p>Webdesign // Photography</p>				
					<div class="social">
						<ul>
							<li><a href="single.html" class="cam"> </a></li>
							<li><a href="single.html" class="gal"> </a></li>
							<li><a href="single.html" class="lin"> </a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
	<link rel="stylesheet" href="/Public/pc/css/swipebox.css">
				<script src="/Public/pc/js/jquery.swipebox.min.js"></script> 
					<script type="text/javascript">
						jQuery(function($) {
							$(".swipebox").swipebox();
						});
					</script>
				<script type="text/javascript" src="/Public/pc/js/jquery.mixitup.min.js"></script>
					<script type="text/javascript">
					$(function () {
						
						var filterList = {
						
							init: function () {
							
								// MixItUp plugin
								// http://mixitup.io
								$('#portfoliolist').mixitup({
									targetSelector: '.portfolio',
									filterSelector: '.filter',
									effects: ['fade'],
									easing: 'snap',
									// call the hover effect
									onMixEnd: filterList.hoverEffect()
								});				
							
							},	
							hoverEffect: function () {
							
								// Simple parallax effect
								$('#portfoliolist .portfolio').hover(
									function () {
										$(this).find('.label').stop().animate({bottom: 0}, 200, 'easeOutQuad');
										$(this).find('img').stop().animate({top: -30}, 500, 'easeOutQuad');				
									},
									function () {
										$(this).find('.label').stop().animate({bottom: -40}, 200, 'easeInQuad');
										$(this).find('img').stop().animate({top: 0}, 300, 'easeOutQuad');								
									}		
								);				

							}
				
						};		
						// Run the show!
						filterList.init();					
					});	
					</script>
			<div class="clearfix"> </div>
		</div>
	</div>
	-->
<!-- //work -->
<!-- footer-top -->
	<div class="footer-top">
		<div class="footer-top-grid">
			<h3>ABOUT <span>BIZZWOW</span></h3>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque 
				id arcu neque, at convallis est felis. 
			</p>
		</div>
		<div class="footer-top-grid">
			<h3>THE <span>TAGS</span></h3>
			<div class="unorder">
				<ul class="tag2">
					<li><a href="#">awesome</a></li>
					<li><a href="#">strategy</a></li>
					<li><a href="#">development</a></li>
				</ul>
				<ul class="tag2">
					<li><a href="#">css</a></li>
					<li><a href="#">photoshop</a></li>
					<li><a href="#">photography</a></li>
					<li><a href="#">html</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-top-grid">
			<h3>LATEST <span>TWEETS</span></h3>
			<ul class="twi">
				<li>I like this awesome freebie. Check it out <a href="mailto:info@example.com" class="mail">
				@http://t.co/9vslJFpW</a> <span>ABOUT 15 MINS</span></li>
			</ul>
		</div>
		<div class="footer-top-grid">
			<h3>FLICKR <span>FEED</span></h3>
			<div class="flickr-grids">
				<div class="flickr-grid">
					<img src="/Public/pc/images/15.png" alt=" " title="CEO">
				</div>
				<div class="flickr-grid">
					<img src="/Public/pc/images/16.png" alt=" " title="GM">
				</div>
				<div class="flickr-grid">
					<img src="/Public/pc/images/17.png" alt=" " title="CEO">
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