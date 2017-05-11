<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/User" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>关键词：</label>
      <input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" class="form-control" size="15" />
      <button type="submit" class="btn-default" data-icon="search">查询</button>
      <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
      
      <div class="pull-right">
        <span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
          <a href="/admin.php/Home/User/add/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="290" data-id="dialog-mask" data-mask="true" data-icon="plus">添加帐号</a>
        </span>
      </div>
    </div> 
  </form>

</div>
<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th align="center" width="60" height="30" align="center" data-order-direction="desc" data-order-field="id">编号</th>
        <th align="center" data-order-direction="desc" data-order-field="username">登陆账号</th>
        <th align="center" data-order-direction="desc" data-order-field="truename">真实姓名</th>
        <th align="center" data-order-direction="desc" data-order-field="posname">系统角色</th>
        <th align="center" data-order-direction="desc" data-order-field="phone">联系电话</th>
        <th align="center" data-order-direction="desc" data-order-field="logintime">最后登陆</th>
        <th align="center" <?php echo display(CONTROLLER_NAME.'/del'); ?> >状态</th>
        <th align="center" <?php echo display(CONTROLLER_NAME.'/edit'); ?> >编辑</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["id"]); ?></td>
          <td align="center"><?php echo ($v["username"]); ?></td>
          <td align="center"><?php echo ($v["truename"]); ?></td>
          <td align="center"><?php echo (getdepname($v["depid"])); ?> </td>
          <td align="center"><?php echo ($v["phone"]); ?></td>
          <td align="center"><?php echo ($v["logintime"]); ?></td>
          <td align="center" <?php echo display(CONTROLLER_NAME.'/del'); ?> >
            <a href="/admin.php/Home/User/status/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" data-toggle="doajax" data-confirm-msg="确定要操作吗？">
              <?php if($v["status"] == 1 ): ?><img src="/Public/images/ok.gif" border="0"/>
                <?php else: ?>
                <img src="/Public/images/locked.gif" border="0"/><?php endif; ?>
            </a>
          </td>
          <td align="center"> 
            <span <?php echo display(CONTROLLER_NAME.'/edit'); ?>>
              <a href="/admin.php/Home/User/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="300" data-id="dialog-mask" data-mask="true" >编辑</a>
            </span>

            <span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
              <a href="/admin.php/Home/User/del/id/<?php echo ($v['id']); ?>/navTabId/user" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
            </span> 
          </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
  </table>


  <div class="bjui-pageFooter">
    <div class="pages">
    <span>共 <?php echo ($totalCount); ?> 条，每页 <?php echo ($numPerPage); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($totalCount); ?>" data-page-size="<?php echo ($numPerPage); ?>" data-page-current="<?php echo ($currentPage); ?>">
    </div>
  </div>
</div>