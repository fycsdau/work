<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Users" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>关键词：</label>
      <input type="text" value="<?php echo ($_REQUEST['keys']); ?>" name="keys" class="form-control" size="15" />
      <button type="submit" class="btn-default" data-icon="search">查询</button>
      <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
      
    </div> 
  </form>

</div>
<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th align="center" width="60" height="30" align="center" data-order-direction="desc" data-order-field="id">编号</th>
        <th align="center" data-order-direction="desc" data-order-field="username">用户名</th>
        <th align="center" data-order-direction="desc" data-order-field="nickname">真实姓名</th>
        <th align="center" data-order-direction="desc" data-order-field="mobile">联系电话</th>
        <th align="center" data-order-direction="desc" data-order-field="sex">性别</th>
        <th align="center" data-order-direction="desc" data-order-field="rank">等级</th>
        <th align="center" data-order-direction="desc" data-order-field="balance">账户余额</th>
        <th align="center" data-order-direction="desc" data-order-field="integral">用户积分</th>
        <th align="center" data-order-direction="desc" data-order-field="add_time">注册时间</th>
        <th align="center" data-order-direction="desc" data-order-field="use_count">使用次数</th>
        <th align="center">编辑</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["id"]); ?></td>
          <td align="center"><?php echo ($v["username"]); ?></td>
          <td align="center"><?php echo ($v["nickname"]); ?></td>
          <td align="center"><?php echo ($v["mobile"]); ?></td>
          <td align="center">
          	<?php if($v["sex"] == 0 ): ?>男
          	<?php else: ?>
          		女<?php endif; ?>
          </td>
          <td align="center"><?php echo ($v["rank"]); ?></td>
          <td align="center"><?php echo ($v["balance"]); ?></td>
          <td align="center"><?php echo ($v["integral"]); ?></td>
          <td align="center"><?php echo ($v["add_time"]); ?></td>
          <td align="center"><?php echo ($v["use_count"]); ?></td>
          <td align="center"> 
            <span>
              <a href="/admin.php/Home/Users/del/id/<?php echo ($v['user_id']); ?>/navTabId/users" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
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