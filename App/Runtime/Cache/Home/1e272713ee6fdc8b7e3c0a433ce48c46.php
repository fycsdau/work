<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Job" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>关键词：</label>
      <input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" class="form-control" size="15" />
      <button type="submit" class="btn-default" data-icon="search">查询</button>
      <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
      
    </div> 
    <div class="pull-right">
         <span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
             <a href="/admin.php/Home/Job/add/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="210" data-id="dialog-mask" data-mask="true" data-icon="plus">添加工作</a>
         </span>
     </div>
  </form>

</div>
<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th align="center" width="60" height="30" align="center" data-order-direction="desc" data-order-field="id">编号</th>
        <th align="center" data-order-direction="desc" data-order-field="job_name">工作名</th>
        <th align="center" data-order-direction="desc" data-order-field="company">公司</th>
        <th align="center" data-order-direction="desc" data-order-field="mobile">联系电话</th>
        <th align="center" data-order-direction="desc" data-order-field="address">地址</th>
        <th align="center" data-order-direction="desc" data-order-field="salary">工资</th>
        <th align="center" data-order-direction="desc" data-order-field="add_time">添加时间</th>
        <th align="center">编辑</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["job_id"]); ?></td>
          <td align="center"><?php echo ($v["job_name"]); ?></td>
          <td align="center"><?php echo ($v["company"]); ?></td>
          <td align="center"><?php echo ($v["mobile"]); ?></td>
          <td align="center"><?php echo ($v["address"]); ?></td>
          <td align="center"><?php echo ($v["salary"]); ?></td>
          <td align="center"><?php echo ($v["add_time"]); ?></td>
          <td align="center"> 
            <span>
              <a href="/admin.php/Home/Job/del/id/<?php echo ($v['job_id']); ?>/navTabId/jobs" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
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