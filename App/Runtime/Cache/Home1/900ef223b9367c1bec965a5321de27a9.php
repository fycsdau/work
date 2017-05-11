<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
	<form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Dep" method="post">

		<input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
		<input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

		<div class="bjui-searchBar">

			<label>角色名称：</label><input type="text" value="<?php echo ($_REQUEST['title']); ?>" name="title" class="form-control" size="20" />
            <button type="submit"  class="btn-default" data-icon="search">查询</button>

			<div class="pull-right">
				<span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
					<a href="/admin.php/Home/Dep/add/type/1/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="150" data-id="dialog-mask" data-mask="true" data-icon="plus">新增角色</a>
				</span>
			</div>
		</div> 
	</form>

</div>

<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
		<thead>
			<tr>
				<th width="60" height="30" align="center">编号</th>
				<th>角色名称</th>
				<th width="60" align="center">排序</th>
				<th width="160" <?php echo display(CONTROLLER_NAME.'/edit'); ?> >操作</th>
			</tr>
		</thead>
		<tbody>

			<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
					<td height="25" align="center"><?php echo ($v["id"]); ?></td>
					<td><?php echo ($v["title"]); ?></td>
					<td align="center"><?php echo ($v["sort"]); ?></td>
					<td <?php echo display(CONTROLLER_NAME.'/edit'); ?> >
						<span <?php echo display(CONTROLLER_NAME.'/edit'); ?>>
							<a href="/admin.php/Home/Dep/edit/id/<?php echo ($v['id']); ?>/type/1/navTabId/<?php echo CONTROLLER_NAME;?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="150" data-id="dialog-mask" data-mask="true" >编辑</a>
							<a href="/admin.php/Home/Dep/EditRule/id/<?php echo ($v['id']); ?>/type/1/navTabId/<?php echo CONTROLLER_NAME;?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="950" data-height="500" data-id="dialog-mask" data-mask="true" >权限</a>
						</span>

						<span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
							<a href="/admin.php/Home/Dep/del/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
						</span>
					</td>
				</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
</div>