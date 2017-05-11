<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo (C("WEB_SITE_TITLE")); ?></title>
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

	<body class="has-footer">

		<header class="page-header">
		  <div class="header-title">充值</div>
		</header>
		<form action="/admin.php/Web/Index/create_order" class="pageForm" data-toggle="validate">
			<section class="container">
				<section>
				<div class="section-box-white section-padded">
					<div class="section-box-item">
						<div class="phone-number">
							<input type="text" id="mobile" name="mobile" value="" onkeyup="javascript:mobile_belong()" >
							
						</div>
						<div class="phone-number-info"><span id="mobile_belong"></span></div>
					</div>
					<a href="phone_list.html" class="user-icon-p num-history"></a>
				</div>
				</section>
				<section>
					<div id="mobile_package">
						<div class="mb-list">
					        <div class="mb-list-item active"><span>30M</span></div>
					        <div class="mb-list-item"><span>70M</span></div>
					        <div class="mb-list-item"><span>150M</span></div>
				      	</div>
				      	<div class="mb-list">
					        <div class="mb-list-item"><span>500M</span></div>
					        <div class="mb-list-item"><span>1G</span></div>
					        <div class="mb-list-item"><span>2G</span></div>
				      	</div>
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
							<span>已售出：658份</span>
							<span id="market_price">原价：
								<span id="market_price_value">
									50.00
								</span>
							</span>元
						</div>
						</div>
							<div class="pay-item">
							<input type="hidden" id="select_good_id" name="select_good_id" value="" >
							<button class="mui-btn mui-btn-block mui-btn-mini mui-btn-success btn-block">立即支付</button>
						</div>
					</div>
				</section>
			</section>
		</form>
		<footer class="navigation">
	<div class="nav-group">
		<a class="active"><i class="user-icon-shouye"></i><span>首页</span></a>
		<a href="show.html"><i class="user-icon-kongjianxiu"></i><span>秀一秀</span></a>
		<a href="member.html"><i class="user-icon-30"></i><span>我</span></li>
			<a href="help.html"><i class="user-icon-weibiaoti1"></i><span>帮助</span></a>
		</div>
	</footer>
	<script src="http://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
	<script src="/Public/web/lib/js/mui.min.js"></script>
	<script src="/Public/web/js/common.js"></script>
	<script>
		$('.mb-list').on('tap','.mb-list-item',function(){
			$('.mb-list-item').removeClass('active');
			$(this).addClass('active');
		})
	</script>
</body>
</html>
		<script src="http://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
		<script src="/Public/web/lib/js/mui.min.js"></script>
		<script src="/Public/web/js/common.js"></script>
		<script>
			$('.mb-list').on('tap','.mb-list-item',function(){
			  $('.mb-list-item').removeClass('active');
			  $(this).addClass('active');
			})
			var price_array = [];
			function mobile_belong(){
				var mobile = document.getElementById("mobile").value;
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
				   			var obj = new Function("return" + data)();//转换后的JSON对象
				   			var html = '<span id="belong">'+obj.province+obj.city+'-'+obj.corp+'</span>';
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
				   			var market_price = packages[0].market_price;
				   			var shop_price = shop_point * market_price;
				   			//alert(shop_price);
				   			$("#shop_price").append('<span id="shop_price_value">'+shop_price+'</span>');
				   			$("#market_price").append('<span id="market_price_value">'+market_price+'</span>');
				  		},
					});
				}
			}
			function select_package(good_id){
				
				$("#shop_price_value").remove();
				$("#market_price_value").remove();
				
				for(var i=0;i<price_array.length;i++){
					if(price_array[i].goods_id == good_id){
			   			var shop_price = price_array[i].shop_point * price_array[i].market_price;
			   			//alert(shop_price);
			   			$("#shop_price").append('<span id="shop_price_value">'+shop_price+'</span>');
			   			$("#market_price").append('<span id="market_price_value">'+price_array[i].market_price+'</span>');
					}
				}
				$('.mb-list-item').removeClass('active');
				$('#goods_'+good_id).addClass('active');
				$('#select_good_id').val(good_id);
			
			}
		</script>
	</body>
</html>