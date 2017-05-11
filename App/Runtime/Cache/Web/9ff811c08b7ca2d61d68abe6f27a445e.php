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
<link href="/Public/web/css/mui.picker.min.css" rel="stylesheet" />
<link href="/Public/web/css/mui.poppicker.css" rel="stylesheet" />
<body>
    <header class="page-header">
        <i class="header-left icon-func user-icon-fanhui"></i>
        <div class="header-title"><?php echo ($page_title); ?></div>
    </header>
    <section class="container">
        <form method="post" id="addcard" onsubmit="return addcard_check()">
            <input type="hidden" name="bank_id" id="bank_id" class="mui-input-clear" value="<?php echo ($bank['bank_id']); ?>">
            <section class="mui-input-group">
                <div id="showUserPicker" class="mui-input-row">
                    <label>开户银行：</label>
                    <input type="text" name="bankname" id="userResult" class="mui-input-clear" placeholder="请选择开户银行" value="<?php echo ($bank['bankname']); ?>" style="ime-mode:disabled" >
                </div>
                <div class="mui-input-row">
                    <label>户主姓名：</label>
                    <input type="text" name="bankusername" id="bankusername" class="mui-input-clear" placeholder="请填写户主姓名"value="<?php echo ($bank['bankusername']); ?>">
                </div>
                <div class="mui-input-row">
                    <label>银行卡号：</label>
                    <input type="number" name="bankcode" id="bankcode" class="mui-input-clear" placeholder="请填写银行卡号"value="<?php echo ($bank['bankcode']); ?>">
                </div>
                <div class="mui-input-row">
                    <label>手机号码：</label>
                    <input type="number" name="phone" id="phone" class="mui-input-clear" placeholder="请填写银行预留手机号"  value="<?php echo ($bank['phone']); ?>">
                </div>
            </section>
            <section class="mui-content-padded form-op-section">
                <button type="button" class="mui-btn mui-btn-block mui-btn-success btn-action" id="addcard_btn">保存</button>

                <?php if(($show_delete) == "1"): ?><button type="button" class="mui-btn mui-btn-block mui-btn-danger btn-action" id="delcard_btn">删除</button><?php endif; ?>
                <span id="addcard_span"></span>
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

    <script src="/Public/web/lib/js/mui.picker.min.js"></script>
    <script src="/Public/web/lib/js/mui.poppicker.js"></script>
    <script>
        (function($, doc) {
            $.init();
            $.ready(function() {
                //普通示例
                var userPicker = new $.PopPicker();
                userPicker.setData([{
                    value: '中国银行',
                    text: '中国银行'
                }, {
                    value: '中国建设银行',
                    text: '中国建设银行'
                }, {
                    value: '中国农业银行',
                    text: '中国农业银行'
                }, {
                    value: '中国工商银行',
                    text: '中国工商银行'
                }]);
                var showUserPickerButton = doc.getElementById('showUserPicker');
                var userResult = doc.getElementById('userResult');
                showUserPickerButton.addEventListener('tap', function(event) {
                    userPicker.show(function(items) {
                        userResult.value = items[0].text;
                        //返回 false 可以阻止选择框的关闭
                        //return false;
                    });
                }, false);
            });
        })(mui, document);

        $('#addcard_btn').on('click',function(){
            return addcard_check();
        })

        <?php if(($show_delete) == "1"): ?>$('#delcard_btn').on('click',function(){
                var btnArray = ['是', '否'];
                mui.confirm('删除后不可恢复，确定要删除吗？', '删除确认', btnArray, function(e) {
                    if (e.index == 0) {
                        $.ajax({
                            type: "get",
                            cache:false,
                            data: {bank_id:<?php echo ($bank['bank_id']); ?>},
                            url: "/index.php/user/delbank",
                            timeout: 20000,
                            error: function(){
                                mui.alert('服务器故障，请联系管理员处理', 'Error');
                            },
                            success: function(t){
                                if(t=='success'){
                                    window.location.href="/index.php/user/cards";
                                }else{
                                    alert(t);
                                }
                            }
                        });
                    }
                })
            })<?php endif; ?>

        function addcard_check(){
            //判断用户输入是否完整
            if($("#userResult").val() == ''){
                mui.alert('请选择开户银行', 'Error');
                $("#userResult").focus();
                return false;
            }
            if($("#bankusername").val() == ''){
                mui.alert('请填写户主姓名', 'Error');
                $("#bankusername").focus();
                return false;
            }
            if($("#bankcode").val() == '' || $("#bankcode").val().length != 19){
                mui.alert('银行卡号填写错误，请检查', 'Error');
                $("#bankcode").focus();
                return false;
            }
            if($("#phone").val() == ''){
                mui.alert('请填写银行预留手机号', 'Error');
                $("#phone").focus();
                return false;
            }
            $("#addcard_btn").hide();
            $("#delcard_btn").hide();
            $("#addcard_span").html("正在提交...");
            $.ajax({
                type: "post",
                cache:false,
                data: {bank_id:$("#bank_id").val(), bankname:$("#userResult").val(), bankusername:$("#bankusername").val(), bankcode:$("#bankcode").val(), phone:$("#phone").val()},
                url: "/index.php/user/addcard",
                timeout: 20000,
                error: function(){
                    mui.alert('服务器故障，请联系管理员处理', 'Error');
                },
                success: function(t){
                    if(t=='success'){
                        window.location.href="/index.php/user/cards";
                    }else{
                        alert(t);
                        $("#addcard_btn").show();
                        $("#delcard_btn").show();
                        $("#addcard_span").html("");
                    }
                }
            });
        }
    </script>
</body>
</html>