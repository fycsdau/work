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
	<link rel="stylesheet" href="/Public/web/lib/css/mui.css">
	<link rel="stylesheet" href="/Public/web/css/style.css">
	<link rel="stylesheet" href="/Public/web/css/icons.css">
</head>
<body>
	<header class="page-header">
		<i class="header-left icon-func user-icon-fanhui"></i>
		<div class="header-title">支付</div>
	</header>
	<section class="container">
		<section>
			<div class="section-box-white section-padded">
				<div class="section-box-item pay-amount-key">
					<i class="user-icon-qianbi"></i> 需支付
				</div>
				<div class="section-box-item pay-amount">
					<span><?php echo ($order_money); ?></span> 元
				</div>
			</div>
		</section>
		<section>
			<div class="section-title">请选择支付方式</div>
			<ul class="mui-table-view pay-type-list">
				<?php if(($is_wechat) == "1"): ?><li class="mui-table-view-cell">
						<a class="mui-navigate-right" id="chooseWXPay">
							<i class="user-icon-weixin pay-type-weixin"></i> 微信支付
						</a>
					</li><?php endif; ?>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right">
						<div class="section-box">
							<div class="section-box-item"><i class="user-icon-iconhqb01 pay-type-linqian"></i> 零钱支付</div>
							<div class="section-box-item linqian-balance">余额：<?php echo ((isset($user_money) && ($user_money !== ""))?($user_money):"0.00"); ?>元</div>
						</div>
					</a>
				</li>
			</ul>
		</section>
	</section>
    <div style="width:100%; height:60px; display:block;"></div>
	<script src="/Public/web/js/zepto.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="/Public/web/lib/js/mui.min.js"></script>
	<script src="/Public/web/js/common.js"></script>
	<script type="text/javascript">
	<?php if(($is_wechat) == "1"): ?>$("#chooseWXPay").click(function(){
		callpay();
	})

	//调用微信JS api 支付
	function jsApiCall(){
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo ($jsApiParameters); ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				if(res.err_msg == 'get_brand_wcpay_request:cancel') {
					//window.location.href="/index.php/user/order_detail/id/<?php echo ($last_id); ?>";
					//alert("您已取消了此次支付");
					return;
				} else if(res.err_msg == 'get_brand_wcpay_request:fail') {
					window.location.href="/index.php/user/order_detail/id/<?php echo ($last_id); ?>";
					alert("支付失败");
					return;
				} else if(res.err_msg == 'get_brand_wcpay_request:ok') {
					window.location.href="/index.php/user/orders";
                    //alert("支付成功！");//跳转到订单页面
                    
                } else {
                	alert("未知错误"+res.error_msg);
                	return;
                }
            }
            );
	}
	function callpay(){
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
				document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			}
		}else{
			jsApiCall();
		}
	}<?php endif; ?>
	</script>
</body>
</html>