<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Log" method="post">
        <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
        <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

        <div class="bjui-searchBar">
            <label>关键词：</label><input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" class="form-control" size="20" />
            <button type="submit"  class="btn-default" data-icon="search">查询</button>
            <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
            <span <?php echo display(CONTROLLER_NAME.'/del'); ?> style="float:right;" ><a href="/admin.php/Home/Log/del/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要清理吗？" data-icon="remove">清理</a></span>
        </div>
    </form>

</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
        <thead>
            <tr>
                <th width="60" height="30" align="center">编号</th>
                <th>操作人</th>
                <th>操作内容</th>
                <th>相关网址</th>
                <th data-order-direction="desc" data-order-field="addtime">操作时间</th>
                <th>操作IP</th> 
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr data-id="<?php echo ($vo['id']); ?>">
                    <td align="center"><?php echo ($vo['id']); ?></td>
                    <td><?php echo ($vo['username']); ?></td>
                    <td><?php echo ($vo['content']); ?></td>
                    <td><?php echo str_replace("/admin.php/Home", "", $vo['url'])?></td>
                    <td><?php echo ($vo['addtime']); ?></td>
                    <td><A href="http://www.ip138.com/ips138.asp?ip=<?php echo ($vo['ip']); ?>&action=2" target=_blank><?php echo ($vo['ip']); ?></a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>共 <?php echo ($totalCount); ?> 条  每页 <?php echo ($numPerPage); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($totalCount); ?>" data-page-size="<?php echo ($numPerPage); ?>" data-page-current="<?php echo ($currentPage); ?>">
    </div>
</div>