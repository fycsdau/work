<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo ($page_title); ?></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="http://oss.llian.com.cn/lib/css/mui.css">
	<link rel="stylesheet" href="http://oss.llian.com.cn/css/style.css">
	<link rel="stylesheet" href="http://oss.llian.com.cn/css/icons.css">
	<link rel="stylesheet" href="http://oss.llian.com.cn/css/spinner/spinners.css" type="text/css">
</head>

<div id="loading">
	<span class="flower-loader" style="z-index: 10;">Loading&#8230;</span>
	<div style="background: #000; width: 120%; height: 120%;opacity: 0.4; position: fixed; z-index: 9; margin: -10px 0px 0px 0px;"></div>
</div>

<body>
    <header class="page-header">
        <i class="header-left icon-func user-icon-fanhui"></i>
        <div class="header-title">用户信息</div>
    </header>
    <section class="container">
        <section class="member">
            <div class="member-header">
                <div class="mask"></div>
                <div class="member-info">
                    <div class="member-name"><?php echo ($userinfo["nickname"]); ?></div>
                    <div>会员ID：<?php echo ($userinfo["user_id"]); ?></div>
                    <div>级别：会员</div>
                </div>
                <div class="member-avatar" style="top:2rem; bottom:0rem;"><img src="<?php echo ($userinfo["headimgurl"]); ?>" alt=""></div>
            </div>
        </section>
        <section>
            <ul class="mui-table-view">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <div class="section-box">
                            <div class="form-key">手机号码</div>
                            <div class="section-box-item"><?php echo ($userinfo["phone"]); ?></div>
                        </div>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <div class="section-box">
                            <div class="form-key">电子邮箱</div>
                            <div class="section-box-item"><?php echo ($userinfo["email"]); ?></div>
                        </div>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <div class="section-box">
                            <div class="form-key">性别</div>
                            <div class="section-box-item">
                                <?php echo ($userinfo['sex']?'男':'女'); ?>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <div class="section-box">
                            <div class="form-key">生日</div>
                            <div class="section-box-item"><?php echo ($userinfo["birthday"]); ?></div>
                        </div>
                    </a>
                </li>
            </ul>
        </section>
        <section class="mui-content-padded form-op-section">
            <a href="member_infoedit/id/<?php echo ($userinfo['user_idd']); ?>" type="button" class="mui-btn mui-btn-block mui-btn-success btn-action" onclick="$(this.form).submit();">修改我的信息</a>
        </section>
    </section>
    <script type="text/javascript">var navid =2;</script>
    <footer class="navigation">
	<div class="nav-group">
		<a href="/index.php" class="active"><i class="user-icon-shouye"></i><span>首页</span></a>
		<a href="/index.php/user/qrcode"><i class="user-icon-kongjianxiu"></i><span>秀一秀</span></a>
		<a href="/index.php/user/member"><i class="user-icon-30"></i><span>我</span></li></a>
		<a href="/index.php/help"><i class="user-icon-weibiaoti1"></i><span>帮助</span></a>
	</div>
</footer>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="http://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
<script src="http://oss.llian.com.cn/js/mui.min.js"></script>
<script src="http://oss.llian.com.cn/js/common.js"></script>
<script type="text/javascript">
	<?php if(($is_wechat_browser) == "1"): ?>$('.page-header').hide();
		$('.container').css("padding-top","0rem");<?php endif; ?>
	$('.nav-group a').removeClass('active');
	$('.nav-group a').eq(navid).addClass('active');

	Zepto(function($){
		$("#loading").hide();
	})
</script>

<script>
	wx.config({
		appId: '<?php echo ($signPackage["appId"]); ?>',
		timestamp: <?php echo ($signPackage["timestamp"]); ?>,
		nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
		signature: '<?php echo ($signPackage["signature"]); ?>',
		jsApiList: [
		'checkJsApi',
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo'
		]
	});             
	wx.ready(function () {
		wx.checkJsApi({
			jsApiList: [
			'getNetworkType',
			'previewImage',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo'
			],            
		});

		var shareData = {
			title: '我为流联代言',
			desc: '联合百万用户为你的流量省钱！我们提供中国移动，中国联通，中国电信三大运营商2/3/4G手机流量充值服务。充值优惠、快速，同时提供向个人用户流量代理分销业务，让您躺着都能赚钱！',
			link: 'http://m.llian.com.cn/index.php/user/qrcode/user_id/<?php echo ($user_id); ?>',
			imgUrl: 'http://m.llian.com.cn/WeChat/logo01.png', 
		};
		wx.onMenuShareAppMessage(shareData);
		wx.onMenuShareTimeline(shareData);
		wx.onMenuShareQQ(shareData);
		wx.onMenuShareWeibo(shareData);
	});
</script>

</body>
</html>