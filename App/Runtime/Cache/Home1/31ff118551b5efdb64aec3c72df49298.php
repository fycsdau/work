<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
 <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Recharge" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>运营商：</label>
      <select name="operate_name">
      	<option value="" <?php if($_REQUEST['operate_name'] == ''): ?>selected<?php endif; ?>>请选择</option>
      	<option value="中国移动" <?php if($_REQUEST['operate_name'] == '中国移动'): ?>selected<?php endif; ?>>中国移动</option>
      	<option value="中国联通" <?php if($_REQUEST['operate_name'] == '中国联通'): ?>selected<?php endif; ?>>中国联通</option>
      	<option value="中国电信" <?php if($_REQUEST['operate_name'] == '中国电信'): ?>selected<?php endif; ?>>中国电信</option>
      </select>
      <label>手机号：</label>
      <input type="text" value="<?php echo ($_REQUEST['phone']); ?>" name="phone" class="form-control" size="10" />
      <label>状态：</label>
      <select name="status">
      	<option value="" <?php if($_REQUEST['status'] == ''): ?>selected<?php endif; ?>>请选择</option>
      	<option value="3" <?php if($_REQUEST['status'] == 3): ?>selected<?php endif; ?>>处理中...</option>
      	<option value="1" <?php if($_REQUEST['status'] == 1): ?>selected<?php endif; ?>>充值成功</option>
      	<option value="2" <?php if($_REQUEST['status'] == 2): ?>selected<?php endif; ?>>充值失败</option>
      </select>
      <label>流水号：</label>
      <input type="text" value="<?php echo ($_REQUEST['out_recharge_id']); ?>" name="out_recharge_id" class="form-control" size="10" />
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
        <th align="center" data-order-direction="desc" data-order-field="operate_name">运营商名称</th>
        <th align="center" data-order-direction="desc" data-order-field="phone">手机号</th>
        <th align="center" data-order-direction="desc" data-order-field="package_name">商品名称</th>
        <th align="center" data-order-direction="desc" data-order-field="status">状态</th>
        <th align="center" data-order-direction="desc" data-order-field="create_time">提交时间</th>
        <th align="center" data-order-direction="desc" data-order-field="finish_time">完成时间</th>
        <th align="center" data-order-direction="desc" data-order-field="out_recharge_id">流水号</th>
        <th align="center" <?php echo display(CONTROLLER_NAME.'/edit'); ?> >编辑</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["id"]); ?></td>
          <td align="center"><?php echo ($v["operate_name"]); ?></td>
          <td align="center"><?php echo ($v["phone"]); ?> </td>
          <td align="center"><?php echo ($v["package_name"]); ?></td> 
          <td align="center">
          	<?php if($v["status"] == 3 ): ?>处理中
          	<?php elseif($v["status"] == 1 ): ?>
          		充值成功
        	<?php elseif($v["status"] == 2 ): ?>
        		充值失败
          	<?php else: ?>
          		异常<?php endif; ?>
          	
          </td>
          <td align="center"><?php echo ($v["create_time"]); ?></td> 
          <td align="center"><?php echo ($v["finish_time"]); ?></td> 
          <td align="center"><?php echo ($v["out_recharge_id"]); ?></td>
          <td align="center"> 
            <span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
              <a href="/admin.php/Home/Recharge/del/id/<?php echo ($v['id']); ?>/navTabId/recharge" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
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