<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Offer" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>会员编号：</label>
      <input type="text" value="<?php echo ($_REQUEST['user_id']); ?>" name="user_id" class="form-control" size="10" />
      <label>状态：</label>
      <select name="status">
      	<option value="" <?php if($_REQUEST['status'] == ''): ?>selected<?php endif; ?>>请选择</option>
      	<option value="1" <?php if($_REQUEST['status'] == 1): ?>selected<?php endif; ?>>正在处理</option>
      	<option value="2" <?php if($_REQUEST['status'] == 2): ?>selected<?php endif; ?>>已经打款</option>
      	<option value="3" <?php if($_REQUEST['status'] == 3): ?>selected<?php endif; ?>>打款成功</option>
      </select>
      <button type="submit" class="btn-default" data-icon="search">查询</button>
      <a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">清空查询</a>
      
    </div> 
  </form>

</div>
<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th align="center" width="60" height="30" align="center" data-order-direction="desc" data-order-field="offer_id">编号</th>
        <th align="center" data-order-direction="desc" data-order-field="user_id">会员编号</th>
        <th align="center" data-order-direction="desc" data-order-field="bank_id">银行编号</th>
        <th align="center" data-order-direction="desc" data-order-field="money">金额</th>
        <th align="center" data-order-direction="desc" data-order-field="status">状态</th>
        <th align="center" data-order-direction="desc" data-order-field="create_time">申请时间</th>
        <th align="center" data-order-direction="desc" data-order-field="complete_time">处理时间</th>
        <th align="center" data-order-direction="desc" data-order-field="operat_user_id">操作用户id</th>
        <th align="center" data-order-direction="desc" data-order-field="operat_username">操作用户</th>
        <th align="center" data-order-direction="desc" data-order-field="operat_memo">操作备注</th>
        
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["offer_id"]); ?></td>
          <td align="center"><?php echo ($v["user_id"]); ?></td>
          <td align="center"><?php echo ($v["bank_id"]); ?></td>
          <td align="center"><?php echo ($v["money"]); ?> </td>
          <td align="center">
          	<?php if($v["status"] == 1 ): ?>正在处理
          	<?php elseif($v["status"] == 2 ): ?>
          		已经打款
        	<?php elseif($v["status"] == 3 ): ?>
        		打款成功
          	<?php else: ?>
          		异常<?php endif; ?>
          	
          </td>
          <td align="center"><?php echo ($v["create_time"]); ?></td>
          <td align="center"><?php echo ($v["complete_time"]); ?></td>
          <td align="center"><?php echo ($v["operat_user_id"]); ?></td>
          <td align="center"><?php echo ($v["operat_username"]); ?></td>
          <td align="center"><?php echo ($v["operat_memo"]); ?></td>
          
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