<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Help" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <input type="hidden" name="type" value="<?php echo ($_REQUEST['type']); ?>">
      <label>标题：</label>
      <input type="text" value="<?php echo ($_REQUEST['title']); ?>" name="title" class="form-control" size="15" />
      <button type="submit"  class="btn-default" data-icon="search">查询</button>

      <div class="pull-right">
        <span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
          <a href="/admin.php/Home/Help/add/type/<?php echo ($infotypeid); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="670" data-height="430" data-id="dialog-mask" data-mask="true" data-maxable="false" data-icon="plus">添加<?php echo ($infotypename); ?></a>
        </span>
      </div>
    </div> 
  </form>
</div>

<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th width="50" height="30" align="center" data-order-direction='desc' data-order-field='id'>ID</th>
        <th align="center">标题</th>
        <th width="120" align="center">操作</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["id"]); ?></td>
          <td>
            <a href="/admin.php/Home/Help/view/id/<?php echo ($v['id']); ?>"  data-toggle="dialog" data-width="670" data-height="430" data-id="dialog-mask" data-mask="true" >
              <?php echo (msubstr($v["title"],0,20)); ?>
            </a>
          </td>
          
          <td>
            <span <?php echo display(CONTROLLER_NAME.'/edit'); ?>>
              <a href="/admin.php/Home/Help/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green btn-sm" data-toggle="dialog" data-width="670" data-height="430" data-id="dialog-mask" data-maxable="false" data-mask="true" >编辑</a>
            </span>

            <span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
              <a href="/admin.php/Home/Help/del/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
            </span>
          </td>
        </td>
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