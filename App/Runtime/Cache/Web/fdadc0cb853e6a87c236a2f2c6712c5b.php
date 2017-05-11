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

<body class="has-footer">

	<header class="page-header">
		<div class="header-title">充值</div>
	</header>
	<form action="/admin.php/Web/Index/create_order" class="pageForm" id="indexform" data-toggle="validate">
		<section class="container">
			<section>
				<div class="sectio n-box-white section-padded" style="background:#fff;">
					<div class="section-box-item">
						<div class="phone-number">
							<input type="tel" style="border:none; padding:0px;" id="mobile" name="mobile" value="<?php echo ($phone); ?>" onkeyup="javascript:mobile_belong()" placeholder="请输入手机号" maxlength="13">
						</div>
						<div class="phone-number-info"><span id="mobile_belong"><span id="belong"><?php if($phone != ''): echo ($mobile_info); else: ?>全国流量直充，当天到账~<?php endif; ?></span></span></div>
						<input type="hidden" id="mobile_info" name="mobile_info" value="" >
					</div>
					<a href="/index.php/index/phone_list" class="user-icon-p num-history" style="position: absolute; top: 2rem; right: 20px;"></a>
				</div>
			</section>
			<section>
				<div id="mobile_package">
				</div>
			</section>
			<section>
				<div class="section-box-white section-padded pay-box">
					<div class="section-box-item price-item">
						<div class="price-info">应付金额：
							<span id="shop_price">
								<span id="shop_price_value">
									48.85
								</span>
							</span>元
						</div>
						<div class="other-info">
							<span id="market_count">已售出：
								<label id="market_count_value">
									658份
								</label>
							</span>
							
							<span id="market_price">原价：
								<s id="market_price_value">
									50.00元
								</s>
							</span>
						</div>
					</div>
					<div class="pay-item">
						<input type="hidden" id="select_good_id" name="select_good_id" value="" >
						<button type="button" class="mui-btn mui-btn-block mui-btn-mini mui-btn-success btn-block create_order_btn">立即支付</button>
					</div>
				</div>
			</section>
		</section>
	</form>
	<div style="width:100%; height:60px; display:block;"></div>

	<script src="/Public/web/js/layer/layer.js"></script>
	<div id="order_pay_box" style="display:none;"></div>

	<script type="text/javascript">var navid =0;</script>
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

		$('.mb-list').on('tap','.mb-list-item',function(){
			$('.mb-list-item').removeClass('active');
			$(this).addClass('active');
		})

		$(".create_order_btn").click(function(){
			return create_order_check();
		});

		function create_order_check(){
			mobile = $("#mobile").val().replace(/\s/g, "");
			mobile_info = $("#mobile_info").val();
			select_good_id = $("#select_good_id").val();
			if(mobile == ''){
				mui.alert('请输入您要充值的手机号码', 'Error');
				$("#mobile").focus();
				return false;
			}

			if(select_good_id == ''){
				mui.alert('请选择要充值的流量套餐', 'Error');
				$("#select_good_id").focus();
				return false;
			}
			$("#loading").show();
			$.ajax({
				type: "get",
				dataType: "json",
				url: "/admin.php/Web/Index/create_order",
				data: {
					"mobile": mobile,
					"mobile_info": mobile_info,
					"select_good_id": select_good_id
				},
				success: function(data) {
					orderid = data.order_id;
					$.ajax({
						type: "get",
						url: "/index.php/user/index_order_pay/id/"+orderid,
						success: function(data) {
							$("#order_pay_box").html(data);
							htmlcontent = $("#order_pay_box").html();
							layer.open({
								type: 1,
								shadeClose: 0,
								content: htmlcontent,
								anim: 0,
								style: 'position:fixed; bottom:0; left:0; width:100%; max-height:400px; padding:0px; border:none;'
							});

							$("#loading").hide();
						}
					});
				}
			});

		//$("#indexform").submit();


	}

	mobile_null();
	var price_array = [];
	function mobile_belong(){
		var mobile = document.getElementById("mobile").value.replace(/\s/g, "");
		if(mobile.length == 7){
			$.ajax({
				type:"POST",
				url:"<?php echo U('Index/ajaxGetMobileBelong');?>",
				data:{"mobile":mobile},
				//成功返回之后调用的函数   
				success:function(data){
					$("#belong").remove();
					$(".mb-list").remove();
					$("#shop_price_value").remove();
					$("#market_price_value").remove();
					$("#market_count_value").remove();
					var obj = new Function("return" + data)();//转换后的JSON对象
					if(obj.province==undefined){
						alert("暂不支持该号段");
					}else{
						
						var html = '<span id="belong">'+obj.province+obj.city+'-'+obj.corp+'</span>';
						$("#mobile_info").val(obj.province+obj.city+'-'+obj.corp);
						var html1 = '';
						var packages = new Function("return" + obj.goods)();
						price_array = packages;
						for(var i=0;i<packages.length;i++){
							var good = packages[i];
							var good_id = good.goods_id;
							if(i == 0){
								html1 += '<div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item active" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}else if(i%3 == 0 && i != 1 ){
								html1 += '</div><div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}else{
								html1 += '<div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}
						}
						html1 += '</div>';
						$("#mobile_belong").append(html);
						$("#mobile_package").append(html1);
						$('#select_good_id').val(packages[0].goods_id);
						var shop_point = packages[0].shop_point;
						var market_price = parseFloat(packages[0].market_price).toFixed(2);
						var shop_price = shop_point * market_price;
						//alert(shop_price);
						$("#shop_price").append('<label id="shop_price_value">'+parseFloat(shop_price).toFixed(2)+'</label>');
						$("#market_price").append('<s id="market_price_value">'+market_price+'元</s>');
						$("#market_count").append('<label id="market_count_value">'+packages[0].count+'份</label>');
					}
				},
				
			});
		}		//
	}
	function select_package(good_id){

		$("#shop_price_value").remove();
		$("#market_price_value").remove();
		$("#market_count_value").remove();

		for(var i=0;i<price_array.length;i++){
			if(price_array[i].goods_id == good_id){
				var shop_price = price_array[i].shop_point * price_array[i].market_price;
				//alert(shop_price);
				$("#shop_price").append('<label id="shop_price_value">'+parseFloat(shop_price).toFixed(2)+'</label>');
				$("#market_price").append('<s id="market_price_value">'+parseFloat(price_array[i].market_price).toFixed(2)+'元</s>');
				$("#market_count").append('<label id="market_count_value">'+price_array[i].count+'份</label>');
			}
		}
		$('.mb-list-item').removeClass('active');
		$('#goods_'+good_id).addClass('active');
		$('#select_good_id').val(good_id);

	}

	function mobile_null(){
		var mobile = document.getElementById("mobile").value;
		if(mobile == ''){
			$.ajax({
				type:"POST",
				url:"<?php echo U('Index/ajaxGetMobileNull');?>",
				data:{"mobile":mobile},
				//成功返回之后调用的函数   
				success:function(data){
					//$("#belong").remove();
					$(".mb-list").remove();
					$("#shop_price_value").remove();
					$("#market_price_value").remove();
					$("#market_count_value").remove();
					var packages = new Function("return" + data)();//转换后的JSON对象
					var html1 = '';
					price_array = packages;
					for(var i=0;i<packages.length;i++){
						var good = packages[i];
						var good_id = good.goods_id;
						if(i == 0){
							html1 += '<div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item active" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
						}else if(i%3 == 0 && i != 1 ){
							html1 += '</div><div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
						}else{
							html1 += '<div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
						}
					}
					html1 += '</div>';
					$("#mobile_package").append(html1);
					$('#select_good_id').val(packages[0].goods_id);
					//$("#mobile_belong").append(html);
					var shop_point = packages[0].shop_point;
					var market_price = parseFloat(packages[0].market_price).toFixed(2);
					var shop_price = shop_point * market_price;
					//alert(shop_price);
					$("#shop_price").append('<label id="shop_price_value">'+parseFloat(shop_price).toFixed(2)+'</label>');
					$("#market_price").append('<s id="market_price_value">'+market_price+'元</s>');
					$("#market_count").append('<label id="market_count_value">'+packages[0].count+'份</label>');
					
					//$("#mobile_belong").append('<span id="belong">流量直冲，当天到账</span>');
				},
			});
		}else{
			$.ajax({
				type:"POST",
				url:"<?php echo U('Index/ajaxGetMobileBelong');?>",
				data:{"mobile":mobile},
				//成功返回之后调用的函数   
				success:function(data){
					$("#belong").remove();
					$(".mb-list").remove();
					$("#shop_price_value").remove();
					$("#market_price_value").remove();
					$("#market_count_value").remove();
					var obj = new Function("return" + data)();//转换后的JSON对象
					if(obj.province==undefined){
						alert("暂不支持该号段");
					}else{
						
						var html = '<span id="belong">'+obj.province+obj.city+'-'+obj.corp+'</span>';
						$("#mobile_info").val(obj.province+obj.city+'-'+obj.corp);
						var html1 = '';
						var packages = new Function("return" + obj.goods)();
						price_array = packages;
						for(var i=0;i<packages.length;i++){
							var good = packages[i];
							var good_id = good.goods_id;
							if(i == 0){
								html1 += '<div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item active" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}else if(i%3 == 0 && i != 1 ){
								html1 += '</div><div class="mb-list"><div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}else{
								html1 += '<div onclick="javascript:select_package('+good_id+')" class="mb-list-item" id="goods_'+good_id+'"><span>'+good.package_name + '</span></div>';
							}
						}
						html1 += '</div>';
						$("#mobile_belong").append(html);
						$("#mobile_package").append(html1);
						$('#select_good_id').val(packages[0].goods_id);
						var shop_point = packages[0].shop_point;
						var market_price = parseFloat(packages[0].market_price).toFixed(2);
						var shop_price = shop_point * market_price;
						//alert(shop_price);
						$("#shop_price").append('<label id="shop_price_value">'+parseFloat(shop_price).toFixed(2)+'</label>');
						$("#market_price").append('<s id="market_price_value">'+market_price+'元</s>');
						$("#market_count").append('<label id="market_count_value">'+packages[0].count+'份</label>');
					}
				},
				
			});
		}
		
	}	
	
	$("#mobile").keyup(function () {
        //如果输入非数字，则替换为''，如果输入数字，则在每4位之后添加一个空格分隔
        this.value = this.value.replace(/[^\d]/g, '').replace(/(\d{3})(?=\d)/, "$1 ").replace(/(\d{4})(?=\d)/, "$1 ");
    })


</script>
</body>
</html>