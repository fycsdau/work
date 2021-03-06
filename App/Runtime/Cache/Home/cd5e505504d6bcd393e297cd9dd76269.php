<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo (C("WEB_SITE_TITLE")); ?></title>
    <!-- bootstrap - css -->
    <link href="/Public/BJUI/themes/css/bootstrap.min.css" rel="stylesheet">
    <!-- core - css -->
    <link href="/Public/BJUI/themes/css/style.css" rel="stylesheet">
    <link href="/Public/BJUI/themes/blue/core.css" id="bjui-link-theme" rel="stylesheet">
    <!-- plug - css -->
    <link href="/Public/BJUI/plugins/kindeditor_4.1.10/themes/default/default.css" rel="stylesheet">
    <link href="/Public/BJUI/plugins/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="/Public/BJUI/plugins/niceValidator/jquery.validator.css" rel="stylesheet">
    <link href="/Public/BJUI/plugins/bootstrapSelect/bootstrap-select.css" rel="stylesheet">
    <link href="/Public/BJUI/themes/css/FA/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lte IE 7]>
    <link href="/Public/BJUI/themes/css/ie7.css" rel="stylesheet">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lte IE 9]>
    <script src="/Public/BJUI/other/html5shiv.min.js"></script>
    <script src="/Public/BJUI/other/respond.min.js"></script>
    <![endif]-->
    <!-- jquery -->
    <script src="/Public/BJUI/js/jquery-1.7.2.min.js"></script>
    <script src="/Public/BJUI/js/jquery.cookie.js"></script>
    <!--[if lte IE 9]>
    <script src="/Public/BJUI/other/jquery.iframe-transport.js"></script>    
    <![endif]-->
    <!-- BJUI.all 分模块压缩版 -->
    <script src="/Public/BJUI/js/bjui-all.js"></script>
    <!-- plugins -->
    <!-- swfupload for uploadify && kindeditor -->
    <script src="/Public/BJUI/plugins/swfupload/swfupload.js"></script>
    <!-- kindeditor -->
    <script src="/Public/BJUI/plugins/kindeditor_4.1.10/kindeditor-all.min.js"></script>
    <script src="/Public/BJUI/plugins/kindeditor_4.1.10/lang/zh_CN.js"></script>
    <!-- colorpicker -->
    <script src="/Public/BJUI/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- ztree -->
    <script src="/Public/BJUI/plugins/ztree/jquery.ztree.all-3.5.js"></script>
    <!-- nice validate -->
    <script src="/Public/BJUI/plugins/niceValidator/jquery.validator.js"></script>
    <script src="/Public/BJUI/plugins/niceValidator/jquery.validator.themes.js"></script>
    <!-- bootstrap plugins -->
    <script src="/Public/BJUI/plugins/bootstrap.min.js"></script>
    <script src="/Public/BJUI/plugins/bootstrapSelect/bootstrap-select.min.js"></script>
    <!-- icheck -->
    <script src="/Public/BJUI/plugins/icheck/icheck.min.js"></script>
    <!-- dragsort -->
    <script src="/Public/BJUI/plugins/dragsort/jquery.dragsort-0.5.1.min.js"></script>
    <!-- HighCharts -->
    <script src="/Public/BJUI/plugins/highcharts/highcharts.js"></script>
    <script src="/Public/BJUI/plugins/highcharts/highcharts-3d.js"></script>
    <script src="/Public/BJUI/plugins/highcharts/themes/gray.js"></script>
    <!-- ECharts -->
    <script src="/Public/BJUI/plugins/echarts/echarts.js"></script>
    <!-- other plugins -->
    <script src="/Public/BJUI/plugins/other/jquery.autosize.js"></script>
    <link href="/Public/BJUI/plugins/uploadify/css/uploadify.css" rel="stylesheet">
    <script src="/Public/BJUI/plugins/uploadify/scripts/jquery.uploadify.min.js"></script>
    
    <!-- init -->
    <script type="text/javascript">
        $(function() {
            BJUI.init({
                JSPATH       : '/Public/BJUI/',
                PLUGINPATH   : '/Public/BJUI/plugins/',
                loginInfo    : {url:'login_timeout.html', title:'登录', width:400, height:200},
                statusCode   : {ok:200, error:300, timeout:301},
                ajaxTimeout  : 5000,
                alertTimeout : 3000,
                pageInfo     : {pageCurrent:'pageCurrent', pageSize:'pageSize', orderField:'orderField', orderDirection:'orderDirection'},
                keys         : {statusCode:'statusCode', message:'message'},
                ui           : {
                    windowWidth      : 0,
                    showSlidebar     : true,
                    clientPaging     : true,
                    overwriteHomeTab : false
                },
                debug        : true,
                theme        : 'sky'
            })
            //时钟
            var today = new Date(), time = today.getTime()
            $('#bjui-date').html(today.formatDate('yyyy/MM/dd'))
            setInterval(function() {
                today = new Date(today.setSeconds(today.getSeconds() + 1))
                $('#bjui-clock').html(today.formatDate('HH:mm:ss'))
            }, 1000)
        })

        // main - menu
        $('#bjui-accordionmenu')
        .collapse()
        .on('hidden.bs.collapse', function(e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')

                if ($a.hasClass('collapsed')) $heading.removeClass('active')
            })
        })
        .on('shown.bs.collapse', function (e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')

                if (!$a.hasClass('collapsed')) $heading.addClass('active')
            })
        })

        $(document).on('click', 'ul.menu-items li > a', function(e) {
            var $a = $(this), $li = $a.parent(), options = $a.data('options').toObj(), $children = $li.find('> .menu-items-children')
            var onClose = function() {                $li.removeClass('active')            }            var onSwitch = function() {                $('#bjui-accordionmenu').find('ul.menu-items li').removeClass('switch')                $li.addClass('switch')            }
            $li.addClass('active')            if (options) {                options.url      = $a.attr('href')                options.onClose  = onClose                options.onSwitch = onSwitch                if (!options.title) options.title = $a.text()                    if (!options.target)                        $a.navtab(options)                    else                        $a.dialog(options)                }                if ($children.length) {                    $li.toggleClass('open')                }                e.preventDefault()
            })

            //菜单-事件
            function MainMenuClick(event, treeId, treeNode) {
                if (treeNode.isParent) {
                    var zTree = $.fn.zTree.getZTreeObj(treeId)

                    zTree.expandNode(treeNode)
                    return
                }

                if (treeNode.target && treeNode.target == 'dialog')
                    $(event.target).dialog({id:treeNode.tabid, url:treeNode.url, title:treeNode.name})
                else
                    $(event.target).navtab({id:treeNode.tabid, url:treeNode.url, title:treeNode.name, fresh:treeNode.fresh, external:treeNode.external})
                event.preventDefault()
            }
        </script>
    </head>
    <body>
    <!--[if lte IE 7]>
    <div id="errorie"><div>您还在使用老掉牙的IE，正常使用系统前请升级您的浏览器到 IE8以上版本 <a target="_blank" href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;强烈建议您更改换浏览器：<a href="http://down.tech.sina.com.cn/content/40975.html" target="_blank">谷歌 Chrome</a></div></div>
    <![endif]-->
    <div id="bjui-window">
        <header id="bjui-header">
            <div class="bjui-navbar-header">
                <button type="button" class="bjui-navbar-toggle btn-default" data-toggle="collapse" data-target="#bjui-navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="/Public/BJUI/themes/css/img/logo.png" height="40" alt="<?php echo (C("WEB_SITE_TITLE")); ?>">
                </a>
            </div>
            <nav id="bjui-navbar-collapse">
                <ul class="bjui-navbar-right">
                    <div class="datetime" style="text-align: right;color: #fff;padding: 0px 0px 10px 10px;">
                        <div>
                            <span id="bjui-date"></span> <span id="bjui-clock"></span>
                        </div>
                    </div>
                    <li><a href="<?php echo U('public/online');?>" data-toggle="dialog" data-id="online" data-mask="true" data-width="600" data-height="300">在线 <span class="badge"><?php $where['update_time']=array('gt',time()-600);echo M('users')->where($where)->count(); ?></span></a></li>
                    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">我的账户 <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo U('public/changepwd');?>" data-toggle="dialog" data-id="changepwd_page" data-mask="true" data-width="400" data-height="260">&nbsp;<span class="glyphicon glyphicon-lock"></span> 修改密码&nbsp;</a></li>
                            <li><a href="<?php echo U('public/changeinfo');?>" data-toggle="dialog" data-id="changepwd_page" data-mask="true" data-width="600" data-height="350">&nbsp;<span class="glyphicon glyphicon-user"></span> 我的资料</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo U('public/logout');?>" class="red">&nbsp;<span class="glyphicon glyphicon-off"></span> 注销登陆</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle theme blue" data-toggle="dropdown" title="切换皮肤"><i class="fa fa-tree"></i></a>
                        <ul class="dropdown-menu" role="menu" id="bjui-themes">
                            <li><a href="javascript:;" class="theme_default" data-toggle="theme" data-theme="default">&nbsp;<i class="fa fa-tree"></i> 黑白分明&nbsp;&nbsp;</a></li>
                            <li><a href="javascript:;" class="theme_orange" data-toggle="theme" data-theme="orange">&nbsp;<i class="fa fa-tree"></i> 橘子红了</a></li>
                            <li><a href="javascript:;" class="theme_purple" data-toggle="theme" data-theme="purple">&nbsp;<i class="fa fa-tree"></i> 紫罗兰</a></li>
                            <li class="active"><a href="javascript:;" class="theme_blue" data-toggle="theme" data-theme="blue">&nbsp;<i class="fa fa-tree"></i> 天空蓝</a></li>
                            <li><a href="javascript:;" class="theme_green" data-toggle="theme" data-theme="green">&nbsp;<i class="fa fa-tree"></i> 绿草如茵</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <div id="bjui-container">

            <div id="bjui-leftside" style="height: 100%;">
                <div id="bjui-sidebar-s" style="height: 100%;">
                    <div class="collapse"></div>
                </div>
                <div id="bjui-sidebar" style="height: 100%;">
                    <div class="toggleCollapse">
                        <h2>
                            <i class="fa fa-bars"></i>                             菜单栏                             <i class="fa fa-bars"></i>                        </h2>                        <a href="javascript:;" class="lock" data-original-title="" title="">                            <i class="fa fa-lock"></i>                        </a>                    </div>                    <div class="panel-group panel-main collapse in" data-toggle="accordion" id="bjui-accordionmenu">                        <?php $cate=M('menu')->where('level=0 and status=1')->order('sort')->select(); ?>
<?php if(is_array($cate)): foreach($cate as $key=>$c): if(authcheck('Home/'.$c[alink],session('uid'))): ?><div class="panel panel-default collapse in">    
            <div class="panel-heading">        
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#bjui-accordionmenu" href="#bjui-collapse<?php echo ($c["id"]); ?>" class="collapsed">
                    <i class="fa <?php echo ($c["cate_icon"]); ?>"></i>&nbsp;<span style="font-size:15px;"><?php echo ($c["catename"]); ?></span>
                        <b><i class="fa fa-angle-down"></i></b>
                    </a>
                </h4>    
            </div>
            <div id="bjui-collapse<?php echo ($c["id"]); ?>" class="panel-collapse collapse" style="height: 0px;">        
                <div class="panel-body">        
                    <ul class="menu-items" data-faicon="hand-o-up">
                        <?php $cate1=M('menu')->where('level=1 and status=1 and pid='.$c['id'])->order('sort')->select(); ?>
                        <?php if(is_array($cate1)): foreach($cate1 as $key=>$v): if(authcheck('Home/'.$v[alink],session('uid'))): $pid = $v['id']; $child_menu=M('menu')->where("level=2 and status=1 and pid='$pid'")->count(id); ?>
                                <?php if($child_menu == 0 ): ?><li>
                                        <a href="/admin.php/home/<?php echo ($v["alink"]); ?>" data-toggle="navtab" data-options="{id:'<?php echo ($v["cate_navtabid"]); ?>'}" title="<?php echo ($v["catename"]); ?>">
                                            <i class="fa <?php echo ($v["cate_icon"]); ?>"></i>
                                            <?php echo ($v["catename"]); ?>
                                        </a>
                                    </li>

                                    <?php else: ?>
                                    
                                    <li class="switch">
                                        <a style="cursor: pointer;" data-toggle="navtab" data-options="{id:'<?php echo ($v["cate_navtabid"]); ?>'}" title="<?php echo ($v["catename"]); ?>">
                                            <i class="fa <?php echo ($v["cate_icon"]); ?>"></i>
                                            <?php echo ($v["catename"]); ?>
                                        </a>
                                        <b><i class="fa fa-angle-down"></i></b>
                                        <ul class="menu-items-children">
                                            <?php $cate2=M('menu')->where('level=2 and status=1 and pid='.$v['id'])->order('sort')->select(); ?>
                                            <?php if(is_array($cate2)): foreach($cate2 as $key=>$vv): if(authcheck('Home/'.$vv[alink],session('uid'))): ?><li>
                                                        <a href="/admin.php/home/<?php echo ($vv["alink"]); ?>" data-toggle="navtab" data-options="{id:'<?php echo ($vv["cate_navtabid"]); ?>'}" title="<?php echo ($vv["catename"]); ?>">
                                                            <i class="fa <?php echo ($vv["cate_icon"]); ?>"></i>
                                                            <?php echo ($vv["catename"]); ?>
                                                        </a>
                                                    </li><?php endif; endforeach; endif; ?>
                                        </ul>
                                    </li><?php endif; endif; endforeach; endif; ?>
                    </ul>
                </div>    
            </div>
        </div><?php endif; endforeach; endif; ?>                    </div>                </div>            </div>            <div id="bjui-navtab" class="tabsPage">                <div class="tabsPageHeader">                    <div class="tabsPageHeaderContent">                        <ul class="navtab-tab nav nav-tabs">                            <li><a href="javascript:;"><span><i class="fa fa-home"></i> #maintab#</span></a></li>                        </ul>                    </div>                    <div class="tabsLeft"><i class="fa fa-angle-double-left"></i></div>                    <div class="tabsRight"><i class="fa fa-angle-double-right"></i></div>                    <div class="tabsMore"><i class="fa fa-angle-double-down"></i></div>                </div>                <ul class="tabsMoreList">                    <li><a href="javascript:;">#maintab#</a></li>                </ul>                <div class="navtab-panel tabsPageContent">                    <div class="navtabPage unitBox">                        <div class="bjui-pageContent" style="background:#FFF; padding:20px 0px;">                            <!--数量统计-->                            <div class="col-md-12">                                <div class="panel panel-default">                                    <div style="min-height:170px" class="index_count">                                        <ul>                                            <li class="num fans_yesterday" style="color:#000000;font-size:18px;"><?php echo ($fans_yesterday); ?></li>                                            <li>昨日充值笔数</li>                                        </ul>                                        <ul>                                            <li class="num fans_today" style="color:#000000;font-size:18px;"><?php echo ($fans_today); ?></li>                                            <li>今日充值笔数</li>                                        </ul>                                        <ul>                                            <li class="num pointv" style="color:#000000;font-size:18px;"><?php echo ($pointv); ?></li>                                            <li>昨日新增粉丝</li>                                        </ul>                                        <ul>                                            <li class="num point" style="color:#000000;font-size:18px;"><?php echo ($point); ?></li>                                            <li>今日新增粉丝</li>                                        </ul>                                        <ul>                                            <li class="num y_sms" style="color:#000000;font-size:18px;"><?php echo ($y_sms); ?></li>                                            <li>昨日发送短信</li>                                        </ul>                                        <ul>                                            <li class="num sms" style="color:#000000;font-size:18px;"><?php echo ($sms); ?></li>                                            <li>今日发送短信</li>                                        </ul>                                    </div>                                </div>                            </div>
                            <!--数量统计-->
                            <!--待充值序列-->
                            <div class="col-md-6">                                <div class="panel panel-default">                                    <div class="panel-heading">                                    	 <div class="panel-title" style="display:inline">												<div ><a style="float:right;" href="/admin.php/home/order/index" data-toggle="navtab" data-options="{id:'order'}">订单列表</a></div>                                         </div>                                        <h3 class="panel-title">											待充值序列(<?php echo ($lottery_count); ?>)                                        </h3>                                    </div>                                    <div class="panel-body bjui-doc" style="padding:0;">                                        <ul>                                            <?php if(is_array($lottery)): $i = 0; $__LIST__ = $lottery;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lottery): $mod = ($i % 2 );++$i;?><li class="am-g" style="height:30px;">	                                                <div class="am-cf" style="display:inline">													<div style="float:left;width:25%;"><?php echo ($lottery["order_id"]); ?></div>													<div style="float:left;width:25%;"><?php echo ($lottery["operate_name"]); ?></div>													<div style="float:left;width:25%;"><?php echo ($lottery["package_name"]); ?></div>													<div style="float:left;width:25%;"><?php echo ($lottery["phone"]); ?></div>	                                                </div>	                                            </li><?php endforeach; endif; else: echo "" ;endif; ?>                                    	</ul>                                	</div>                            </div>
                        </div>
                        <!--待充值序列-->
                        <!--佣金排行榜-->
                        <div class="col-md-6">                            <div class="panel panel-default">                                <div class="panel-heading">                                    <h3 class="panel-title">										佣金排行榜                                    </h3>                                </div>
                                <div class="panel-body bjui-doc" style="padding:0;">                                    <ul>										<?php if(is_array($commission)): $i = 0; $__LIST__ = $commission;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$commission): $mod = ($i % 2 );++$i;?><li class="am-g" style="height:30px;">                                                <div class="am-cf" style="display:inline">												<div style="float:left;width:20%;"><?php echo ($commission["user_id"]); ?></div>												<div style="float:left;width:35%;"><?php echo ($commission["nickname"]); ?></div>												<div style="float:right;width:35%;"><?php echo ($commission["commission"]); ?>元</div>                                                </div>                                            </li><?php endforeach; endif; else: echo "" ;endif; ?>                                    </ul>                                </div>                            </div>
                        </div>
                        <!--佣金排行榜-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer id="bjui-footer" style="text-align:left">
        您好！<?php echo (getdepname(session('depid'))); ?>  <?php echo (session('truename')); ?> (<?php echo (session('username')); ?>) 您的IP:<?php echo (session('loginip')); ?>   登录时间:<?php echo (session('logintime')); ?>

        <span style="float:right"><?php echo (C("WEB_SITE_TITLE")); ?></span>
    </footer>
</div>
</body>
</html>