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
        <div class="header-title">提现</div>
    </header>
    <section class="container" style="margin:0px 0px 100px 0px">
        <form>
            <section class="mui-input-group">
                <ul class="mui-table-view">
                    <li class="mui-table-view-cell">
                        <div class="section-box">
                            <div class="section-box-item withdrawal-amount-key">
                                可提现金额(元)
                            </div>
                            <div class="section-box-item withdrawal-amount">
                                ¥ <span><?php echo ((isset($userinfo['user_money']) && ($userinfo['user_money'] !== ""))?($userinfo['user_money']):'0.00'); ?></span>
                            </div>
                        </div>
                    </li>
                    <li class="mui-table-view-cell">
                        <div class="section-box">
                            <div class="section-box-item withdrawal-amount-key">
                                已冻结金额(元)
                            </div>
                            <div class="section-box-item withdrawal-amount">
                                ¥ <span><?php echo ((isset($userinfo['frozen_money']) && ($userinfo['frozen_money'] !== ""))?($userinfo['frozen_money']):'0.00'); ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>
            <section class="mui-input-group">
                
                <div id="showUserPicker" class="mui-input-row">
                    <label>提现银行：</label>
                    
                    <?php if(($banknum) == "0"): ?><input onclick="window.location.href='addcard';" type="text"  placeholder="请先添加提现银行卡" style="ime-mode:disabled">
                        <?php else: ?>
                        <div class="section-box" style="padding:10px 0px;">
                            <div class="section-box-item withdrawal-amount-key withdrawal-bank">
                                <div class="cards-account-name"><?php echo ($bank[0]['bankusername']); ?></div>
                                <div class="cards-account-info">
                                    <?php echo ($bank[0]['bankname']); ?><br>
                                    <?php echo ($bank[0]['bankcode']); ?>
                                </div>
                            </div>
                        </div><?php endif; ?>

                    <input type="hidden" name="bank_id" id="userResult" value="<?php echo ($bank[0]['bank_id']); ?>">
                </div>

                <ul class="mui-table-view cards-list" style="display:none;">
                    <?php if(is_array($bank)): $i = 0; $__LIST__ = $bank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div id="showUserPicker" class="mui-input-row" bankid="<?php echo ($v['bank_id']); ?>">
                            <li class="mui-table-view-cell">
                                <div class="section-box">
                                    <i class="<?php echo ($v['bankico']); ?>"></i>
                                    <div class="section-box-item">
                                        <div class="cards-account-name"><?php echo ($v['bankusername']); ?></div>
                                        <div class="cards-account-info">
                                            <?php echo ($v['bankname']); ?><br>
                                            <?php echo ($v['bankcode']); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>

                <div class="mui-input-row">
                    <label>提现金额(元)：</label>
                    <input type="number" name="money" id="money" class="mui-input-clear" placeholder="您最多可以提现 <?php echo ($userinfo['user_money']); ?> 元">
                </div>

            </section>
            <section class="section-tips">
                由于银行跨省跨行都会产生手续费，提现将按照5元/笔扣除汇款手续费！每笔申请也不能低于50元！
            </section>
            <div id="msg"></div>
            <section class="mui-content-padded form-op-section">
                <button type="button" class="mui-btn mui-btn-block mui-btn-success btn-action withdrawal_submit">提交申请</button>
            </section>
        </form>
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

    <script type="text/javascript">
        $("#money").on("keyup",function(){
            if( $("#money").val() > <?php echo ($userinfo['user_money']); ?> ){
                $("#money").val("<?php echo ($userinfo['user_money']); ?>");
            }
        })

        $(".withdrawal-bank").click(function(){
            $(".cards-list").toggle();
        });

        $(".cards-list .mui-input-row").click(function(){
            var bank_info = $(this).find('.section-box-item').html();
            var bank_id = $(this).attr("bankid");
            $("#userResult").val(bank_id);
            $(".withdrawal-bank").html(bank_info);
            $(".cards-list").toggle();
        })

        $(".withdrawal_submit").click(function(){
            if($("#money").val()==''){
                mui.alert('请填写要提现的金额', 'Error');
                $("#money").focus();
                return false;
            }
            if($("#money").val()<=50){
                mui.alert('每笔申请不能低于50元哦～', 'Error');
                $("#money").focus();
                return false;
            }

            $("。withdrawal_submit").hide();
            $("#msg").html("正在提交...");
            $.ajax({
                type: "post",
                cache:false,
                data: {bank_id:$("#userResult").val(), money:$("#money").val()},
                url: "/index.php/user/withdrawal",
                timeout: 20000,
                error: function(){
                    mui.alert('服务器故障，请联系管理员处理', 'Error');
                },
                success: function(t){
                    if(t=='success'){
                        window.location.href="/index.php/user/record";
                    }else{
                        alert(t);
                        $(".withdrawal_submit").show();
                        $("#msg").html("");
                    }
                }
            });

        })

        <?php if(($banknum) == "0"): ?>var btnArray = ['是', '否'];
            mui.confirm('未设置提现银行卡信息，是否现在去设置？', '提现银行卡设置提醒', btnArray, function(e) {
                if (e.index == 0) {
                    window.location.href="addcard";
                }
            })<?php endif; ?>
    </script>
</body>
</html>