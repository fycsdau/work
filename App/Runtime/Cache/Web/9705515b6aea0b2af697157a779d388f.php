<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title></title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="icon" type="image/png" href="/Public/Amazeui/i/favicon.png">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="icon" sizes="192x192" href="/Public/Amazeui/i/app-icon72x72@2x.png">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="乐透河南"/>
	<link rel="apple-touch-icon-precomposed" href="/Public/Amazeui/i/app-icon72x72@2x.png">
	<meta name="msapplication-TileImage" content="/Public/Amazeui/i/app-icon72x72@2x.png">
	<meta name="msapplication-TileColor" content="#0e90d2">
	<link rel="stylesheet" href="/Public/Amazeui/css/amazeui.min.css">
	<link rel="stylesheet" href="/Public/Amazeui/css/app.css">
</head>

<body>
    <style type="text/css">
        body{background:url(/Public/Amazeui/i/sign_bg.gif);}
        .sign_div{ position: absolute; top: 0px; left: 0px; background: #000; opacity: 0.6; width: 100%; height: 100%; z-index: -1; }
        .sign_btn{ width: 200px; height: 200px; padding: 35px 30px 20px 30px; margin:40px auto; color: #fff; background: #cc0228; border:7px solid #920E0E; border-radius: 50%; font-size: 4rem; position: relative; text-align: center;-moz-box-shadow:2px 2px 5px #333333; -webkit-box-shadow:2px 2px 5px #333333; box-shadow:2px 2px 5px #333333;-moz-text-shadow:2px 2px 5px #333333; -webkit-text-shadow:2px 2px 5px #333333; text-shadow:2px 2px 5px #333333; }
        .sign_btn span{font-size: 1.3rem; position: absolute; left: 30px;}
        .sign_btn hr{ margin: 8px 0px; -webkit-box-shadow:2px 2px 5px #333333; box-shadow:2px 2px 5px #333333;}
        .layermanim .layermend:before, .layermanim .layermend:after{background-color: #fff;}
        .layermanim h3{}
    </style>

    <header data-am-widget="header" class="am-header am-header-default"  data-am-sticky="{animation: 'slide-top'}" style="z-index: 9;">
        <div class="am-header-left am-header-nav">
            <a href="/index.php/" class="">
                <i class="am-header-icon am-icon-chevron-left"></i>
            </a>
        </div>
        <h1 class="am-header-title"><?php echo ($page_title); ?></h1>

    </header>

    <!--内容显示-->
    <div class="sign_div"></div>

    <div class="sign_btn">
        <a style="color:#fff;" class="sign_btn_a"><?php echo ($sign_text); ?></a>
        <hr>
        <span>每日签到, 积分享好礼</span>
    </div>

    <div class="am-container">
        <div class="am-g">
            <ul class="am-list am-list-static am-list-border am-list-striped" style=" margin-bottom: 0rem; min-height:10px;">
                <li style="background: #cc0228; color:#fff;">
                    我的积分: <?php echo ((isset($point) && ($point !== ""))?($point):0); ?>
                </li>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <div class="am-cf">
                            <span class=" am-fl am-text-sm"><?php echo ($list["adddate"]); ?></span>
                            <span class="am-fr">
                                <?php echo ($list["content"]); ?>
                            </span>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>

    <div class="am-hide">
        <div id="login_html">
            <form class="am-form">
              <fieldset >
                <div class="am-form-group" style="text-align:left;">
                    <label for="doc-ds-ipt-1">请输入您的手机号</label>
                    <input type="text" id="user_phone" class="am-form-field" placeholder="请输入您的手机号" value="">
                </div>

                <div class="am-form-group" style="text-align:left;">
                    <label for="oc-ds-select-1">请输入验证码</label>
                    <div class="am-cf"></div>
                    <input type="text" id="verifcode" class="am-form-field" placeholder="请输入验证码" value="" style="width:50%; float:left;">
                    <button type="button" class="am-btn am-btn-default" style="width:50%; float:left;" onclick="sendsms('sendagain');" id="sendagain">发送验证码</button>
                </div>

                <div class="am-cf">
                    <button type="submit" class="am-btn am-btn-danger am-radius am-btn-block am-margin-top">登陆</button>
                </div>

            </fieldset>
        </form>
    </div>
</div>

<!--内容显示-->

<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default nav4" id="nav4_ul">
	<ul class="am-navbar-nav am-cf am-avg-sm-4">
		<li >
			<a class="nav_menu">
				<span class="am-icon-futbol-o"></span>
				<span class="am-navbar-label">开奖查询</span>
			</a>
			<dl >
				<!--dd>
					<a href="/index.php/chart"><span>走势图</span></a>
				</dd-->
				<dd>
					<a href="/index.php/lottery/"><span>开奖公告</span></a>
				</dd>
				<dd>
					<a href="http://prev.rabbitpre.com/appDesktop/54ee794e1c3cbe000096b526"><span>玩法介绍</span></a>
				</dd>
				<dd>
					<a href="http://prev.rabbitpre.com/appDesktop/5514f6c3c11790518d410940"><span>开奖流程</span></a>
				</dd>
			</dl>
		</li>
		<li>
			<a class="nav_menu">
				<span class="am-icon-user-secret"></span>
				<span class="am-navbar-label">彩民中心</span>
			</a>
			<dl>
				<dd>
					<a href="/index.php/activity/"><span>优惠活动</span></a>
				</dd>
				<dd>
					<a href="/index.php/dealerships/"><span>网点查询</span></a>
				</dd>
				<dd>
					<a href="/index.php/user/sign/id/<?php echo ($user_id); ?>"><span>每日签到</span></a>
				</dd>
				<dd>
					<a href="/index.php/user/info/id/<?php echo ($user_id); ?>"><span>我的信息</span></a>
				</dd>
				<!--dd>
					<a href="/index.php/game/"><span>游戏中心</span></a>
				</dd-->
			</dl>
		</li>
		<li>
			<a class="nav_menu">
				<span class="am-icon-heart"></span>
				<span class="am-navbar-label">乐透河南</span>
			</a>
			<dl>
				<dd>
					<a href="/index.php/about/hotline/"><span>客服热线</span></a>
				</dd>
				<dd>
					<a href="/index.php/commonweal/"><span>体彩公益</span></a>
				</dd>
				<dd>
					<a href="http://shequ.yunzhijia.com/thirdapp/forum/network/569a3c19e4b04104d5d67448"><span>彩民互动</span></a>
				</dd>
				<?php if($user_auth == '3'): ?><dd>
						<a href="/index.php/dealerships/verif"><span>网点管理</span></a>
					</dd><?php endif; ?>
				<dd>
					<a href="/index.php/dealerships/verifsubmit"><span>网点认证</span></a>
				</dd>
			</dl>
		</li>
	</ul>
</div>


<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
	<div class="am-footer-switch">
		<span class="am-footer-ysp" data-rel="mobile">
			
		</span>
	</div>
	<div class="am-footer-miscs ">
		<p>由 <a href="javascript:;" title="" target="_blank" class=""></a> 提供技术支持
		</p>
		<p>CopyRight © 2016  Inc.</p>
		<p>豫ICP备88888888</p>
	</div>
</footer>


<script src="/Public/Amazeui/js/jquery.min.js"></script>
<script src="/Public/Amazeui/js/amazeui.min.js"></script>
<script src="/Public/Amazeui/js/layer/layer.js"></script>
<script src="/Public/Amazeui/js/app.js"></script>
</body>
</html>
<?php if($today_signed != '1'): ?><script type="text/javascript">
        $(".sign_btn_a").click(function(){
            $(".sign_btn").html('签到中<hr><span>每日签到, 积分享好礼</span>');
            $.ajax({
                type: "get",
                url: "/index.php/user/sign/act/sign/id/<?php echo ($user_id); ?>",
                timeout: 20000,
                error: function(){ alert("签到失败,请联系管理员处理") },
                success: function(t){
                    $(".sign_btn").html('已签到<hr><span>每日签到, 积分享好礼</span>');
                    window.location.reload(true);
                }
            });
        })
    </script><?php endif; ?>

<?php if($needlogin == '1'): ?><script type="text/javascript">
        $(".sign_btn_a").click(function(){
            window.location.href='/index.php/user/login';
        })

        function sendsms(obj){
            var tel = $("#user_phone").val();
            console.log(tel);
            if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(tel))){ 
                alert("手机号码填写不正常，请检查"); 
                return false; 
            } 
            settime(obj);
            $.ajax({
                type: "get",
                cache:false,
                url: "/index.php/sms/sendverifsms/RecNum/"+tel,
                timeout: 20000,
                error: function(){ alert("出错了, 请联系管理员处理")},
                success: function(t){
                    $('#msg').html(t);
                }
            });
        }

        var countdown=60; 
        function settime(obj) { 
            if (countdown == 0) {
                $("#"+obj).attr('disabled',false);
                $("#"+obj).html("重新发送");
                countdown = 60; 
                return;
            } else { 
                $("#"+obj).attr('disabled',true);
                $("#"+obj).html("重新发送(" + countdown + ")");
                countdown--; 
            } 
            setTimeout(function() { 
                settime(obj) }
                ,1000) 
        }
    </script><?php endif; ?>