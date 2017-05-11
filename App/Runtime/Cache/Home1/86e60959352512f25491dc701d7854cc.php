<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
	<form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Rule" method="post">
		<input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
		<input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

		<div class="bjui-searchBar">

			<span>
				<a class="btn btn-orange" href="javascript:;" onclick="$(this).navtab('reloadForm', true);" data-icon="undo">刷新</a>
			</span>

			<div class="pull-right">
				<span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
					<a href="/admin.php/Home/Rule/add/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="200" data-id="dialog-mask" data-mask="true" data-icon="plus">新增功能</a>
				</span>
			</div>
		</div>
	</form>
</div>
<div class="bjui-pageContent tableContent">
	<table data-toggle="tablefixed" data-width="100%" data-layout-h="0" data-nowrap="true">
		<thead>
			<tr>
				<th width="100" align="center">功能模块</th>
				<th>菜单 / 权限</th>
			</tr>
		</thead>
		<tbody>

			<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
					<td align="center">
						<a href="/admin.php/Home/Rule/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="300" data-id="dialog-mask" data-mask="true"  ><?php echo ($v["title"]); ?></a>
					</td>
					<td>
						<table width="100%">
							<?php $list1 = M('auth_rule')->where('status=1 and level=1 and pid='.$v['id'])->order('sort')->select(); ?>
							<?php if(is_array($list1)): foreach($list1 as $key=>$v): ?><tr><td height=30 width=200>
									<a href="/admin.php/Home/Rule/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>"  class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="300" data-id="dialog-mask" data-mask="true" ><?php echo ($v["title"]); ?></a>
								</td>
								<td>
									<?php $list2 = M('auth_rule')->where('level=2 and pid='.$v['id'])->order('sort')->select(); ?>
									<?php if(is_array($list2)): foreach($list2 as $key=>$v): ?><span style="width:95px;">
											<a href="/admin.php/Home/Rule/edit/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="200" data-id="dialog-mask" data-mask="true" ><?php echo ($v["title"]); ?></a>
										</span><?php endforeach; endif; ?> 
								</td>
							</tr><?php endforeach; endif; ?> 
					</table>
				</td>
			</tr><?php endforeach; endif; ?>
	</tbody>
</table>
</div>