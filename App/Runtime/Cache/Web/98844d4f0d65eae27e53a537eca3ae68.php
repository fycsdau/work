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
</div><body>    <header class="page-header">        <i class="header-left icon-func user-icon-fanhui"></i>        <div class="header-title">订单详情</div>    </header>    <section class="container">        <section>            <ul class="mui-table-view">                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">订单编号</div>                        <div class="section-box-item"><?php echo ($order["order_sn"]); ?></div>                    </div>                </li>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">充值状态</div>                        <div class="section-box-item"><?php echo ($order["statusname"]); ?></div>                    </div>                </li>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">手机号码</div>                        <div class="section-box-item"><?php echo ($order["phone"]); ?></div>                    </div>                </li>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">套餐名称</div>                        <div class="section-box-item"><?php echo ($order["package_name"]); ?></div>                    </div>                </li>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">订单金额</div>                        <div class="section-box-item">¥ <?php echo ($order["order_money"]); ?> 元</div>                        <input type="hidden" id="order_money" value="<?php echo ((isset($order["order_money"]) && ($order["order_money"] !== ""))?($order["order_money"]):'0.00'); ?>">                        <input type="hidden" id="amount" value="<?php echo ((isset($user_money) && ($user_money !== ""))?($user_money):'0.00'); ?>">                        <input type="hidden" id="order_id" value="<?php echo ($order["order_id"]); ?>">                    </div>                </li>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">创建时间</div>                        <div class="section-box-item"><?php echo ($order["create_time"]); ?></div>                    </div>                </li>                <?php if(($order['status']) == "2"): ?><li class="mui-table-view-cell">                        <div class="section-box">                            <div class="form-key">支付时间</div>                            <div class="section-box-item"><?php echo ($order["pay_time"]); ?></div>                        </div>                    </li><?php endif; ?>                <?php if(($order['status']) == "3"): ?><li class="mui-table-view-cell">                        <div class="section-box">                            <div class="form-key">充值时间</div>                            <div class="section-box-item"><?php echo ($order["complete_time"]); ?></div>                        </div>                    </li><?php endif; ?>                <li class="mui-table-view-cell">                    <div class="section-box">                        <div class="form-key">支付方式</div>                        <div class="section-box-item"><?php echo ((isset($order["pay_name"]) && ($order["pay_name"] !== ""))?($order["pay_name"]):"微信支付"); ?></div>                    </div>                </li>                <?php if(($order['status']) == "1"): ?><section>                        <ul class="mui-table-view pay-type-list mui-table-view-radio">                            <?php if(($is_wechat) == "1"): ?><li class="mui-table-view-cell <?php if(($defaultpay) == "wechatpay"): ?>mui-selected<?php endif; ?>" onclick="$('#pay_code').val('wechatpay');">                                    <a class="mui-navigate-right noloading" id="chooseWXPay">                                        <i class="user-icon-weixin pay-type-weixin"></i> 微信支付                                    </a>                                </li><?php endif; ?>                            <li class="mui-table-view-cell <?php if(($defaultpay) == "linqian"): ?>mui-selected<?php endif; ?>" onclick="$('#pay_code').val('linqian');">                                <a class="mui-navigate-right noloading">                                    <div class="section-box">                                        <div class="section-box-item"><i class="user-icon-iconhqb01 pay-type-linqian"></i> 零钱支付</div>                                        <div class="section-box-item linqian-balance">余额：<?php echo ((isset($user_money) && ($user_money !== ""))?($user_money):"0.00"); ?>元</div>                                    </div>                                </a>                            </li>                        </ul>                    </section>                    <li class="mui-table-view-cell">                        <div class="section-box">                            <input type="hidden" id="pay_code" value="<?php echo ($defaultpay); ?>">                            <button type="button" class="mui-btn-block mui-btn-mini mui-btn-success btn-block" onclick="pay_check();">立即支付</button>                        </div>                    </li><?php endif; ?>            </ul>        </section>    </section>    <div style="width:100%; height:60px; display:block;"></div>    <script type="text/javascript">var navid =2;</script>    <footer class="navigation">
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
    <script type="text/javascript">        function pay_check(){            var pay_code = $("#pay_code").val();            if(pay_code == 'linqian'){                amount();            }else if(pay_code == 'wechatpay'){                callpay();            }        }        function amount(){            var order_money = document.getElementById('order_money').value;            var amount = document.getElementById('amount').value;            var order_id = document.getElementById('order_id').value;            if(parseFloat(order_money) > parseFloat(amount)){                alert("很抱歉，您的余额不足！！");            }else{                $.ajax({                    type:"POST",                    url:"<?php echo U('Index/ajaxAmountPay');?>",                    data:{"order_money":order_money,'order_id':order_id},                    //成功返回之后调用的函数                                success:function(data){                        window.location.href="/index.php/user/orders";                    },                });            }        }    </script>    <script type="text/javascript">        <?php if(($is_wechat) == "1"): ?>$("#chooseWXPay").click(function(){                callpay();            })    //调用微信JS api 支付    function jsApiCall(){        WeixinJSBridge.invoke(            'getBrandWCPayRequest',            <?php echo ($jsApiParameters); ?>,            function(res){                WeixinJSBridge.log(res.err_msg);                if(res.err_msg == 'get_brand_wcpay_request:cancel') {                    //window.location.href="/index.php/user/order_detail/id/<?php echo ($last_id); ?>";                    //alert("您已取消了此次支付");                    //return;                } else if(res.err_msg == 'get_brand_wcpay_request:fail') {                    //window.location.href="/index.php/user/order_detail/id/<?php echo ($last_id); ?>";                    //alert("支付失败");                    //return;                } else if(res.err_msg == 'get_brand_wcpay_request:ok') {                    window.location.href="/index.php/user/orders";                    //alert("支付成功！");//跳转到订单页面                                    } else {                    alert("未知错误"+res.error_msg);                    return;                }            }            );    }    function callpay(){        if (typeof WeixinJSBridge == "undefined"){            if( document.addEventListener ){                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);            }else if (document.attachEvent){                document.attachEvent('WeixinJSBridgeReady', jsApiCall);                 document.attachEvent('onWeixinJSBridgeReady', jsApiCall);            }        }else{            jsApiCall();        }    }<?php endif; ?></script></body></html>