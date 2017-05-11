<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Menu" method="post">
        <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
        <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

        <div class="bjui-searchBar">
            <span>
                <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">刷新</a>
            </span>

            <div class="pull-right">
                <span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
                    <a href="/admin.php/Home/Menu/add/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="210" data-id="dialog-mask" data-mask="true" data-icon="plus">添加菜单</a>
                </span>
            </div>
        </div> 
    </form>

</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
            <tr>
            <th height="30" width="60" align="center">编号</th>
                <th>菜单名称</th>
                <th align="center" width="60">排序</th>
                <th>链接网址</th>
                <th align="center" width="60" <?php echo display(CONTROLLER_NAME.'/del'); ?> >状态</th>
                <th width="100" align="center" <?php echo display(CONTROLLER_NAME.'/edit'); ?> >操作</th> 
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                    <td height="25" align="center"><?php echo ($v["id"]); ?></td>
                    <td>
                        <?php switch($v["level"]): case "0": ?><b><?php echo ($v["catename"]); ?></b><?php break;?> 
                            <?php case "1": ?>　<?php echo ($v["catename"]); break;?>
                            <?php case "2": ?>　　<?php echo ($v["catename"]); break;?>
                            <?php case "3": ?>　　　<?php echo ($v["catename"]); break;?>
                            <?php default: endswitch;?>
                    </td>
                    <td align="center"><?php echo ($v["sort"]); ?></td>
                    <td><?php echo ($v["alink"]); ?></td>
                    <td align="center" <?php echo display(CONTROLLER_NAME.'/del'); ?> >
                        <a href="/admin.php/Home/Menu/status/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" data-toggle="doajax" data-confirm-msg="确定要操作吗？">
                            <?php if($v["status"] == 1 ): ?><img src="/Public/images/ok.gif" border="0"/>
                                <?php else: ?>
                                <img src="/Public/images/locked.gif" border="0"/><?php endif; ?>
                        </a>
                    </td>
                    <td > 
                        <span <?php echo display(CONTROLLER_NAME.'/edit'); ?>>
                            <a href="/admin.php/Home/Menu/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="210" data-id="dialog-mask" data-mask="true" >编辑</a>
                        </span>

                        <span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
                            <a href="/admin.php/Home/Menu/del/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
                        </span>
                    </td>
                </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
</div>