<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <form id="pagerForm" data-toggle="ajaxsearch" action="/admin.php/Home/Wechat/keyword" method="post">
    <input type="hidden" name="pageSize" value="<?php echo ($numPerPage); ?>">
    <input type="hidden" name="pageCurrent" value="<?php echo ((isset($_REQUEST['pageNum']) && ($_REQUEST['pageNum'] !== ""))?($_REQUEST['pageNum']):1); ?>">

    <div class="bjui-searchBar">
      <label>关键词：</label>
      <input type="text" value="<?php echo ($_REQUEST['keywords']); ?>" name="keywords" class="form-control" size="15" />
      <button type="submit" class="btn-default" data-icon="search">查询</button>
      
      <div class="pull-right">
        <span <?php echo display(CONTROLLER_NAME.'/add'); ?> >
          <a href="/admin.php/Home/Wechat/keywordadd/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="dialog" data-width="600" data-height="260" data-id="dialog-mask" data-mask="true" data-icon="plus">添加关键字</a>
        </span>
      </div>
    </div> 
  </form>

</div>
<div class="bjui-pageContent tableContent">
  <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
    <thead>
      <tr>
        <th width="60" height="30" align="center">编号</th>
        <th>关键字</th>
        <th width="120">回复类型</th>
        <th>回复内容</th>
        <th width="120" <?php echo display(CONTROLLER_NAME.'/edit'); ?> >编辑</th>
      </tr>
    </thead>
    <tbody>

      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
          <td align="center"><?php echo ($v["id"]); ?></td>
          <td><?php echo ($v["keyword"]); ?></td>
          <td>
            <?php if($v["msgtype"] == 'newsitems' ): ?>图文
              <?php else: ?>
              文本<?php endif; ?>
          </td>
          <td>
            <?php if($v["msgtype"] == 'newsitems' ): $newsitem = $v['newsitems']; $newsitems = unserialize($newsitem); ?>
              <a href="<?php echo ($newsitems["url"]); ?>" target="_blank"><?php echo ($newsitems["title"]); ?></a>
              <?php else: ?>
              <?php echo ($v["text"]); endif; ?>
          </td>
          <td> 
            <span <?php echo display(CONTROLLER_NAME.'/edit'); ?>>
              <a href="/admin.php/Home/Wechat/keywordadd/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>"   class="btn btn-green btn-sm" data-toggle="dialog" data-width="600" data-height="300" data-id="dialog-mask" data-mask="true" >编辑</a>
            </span>

            <span <?php echo display(CONTROLLER_NAME.'/del'); ?>>
              <a href="/admin.php/Home/Wechat/keyworddel/id/<?php echo ($v['id']); ?>/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-red btn-sm" data-toggle="doajax" data-confirm-msg="确定要删除吗？">删除</a>
            </span> 
          </td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
  </table>

<script type="text/javascript">
console.log($.CurrentNavtab);
</script>

  <div class="bjui-pageFooter">
    <div class="pages">
      <span>共 <?php echo ($totalCount); ?> 条，每页 <?php echo ($numPerPage); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($totalCount); ?>" data-page-size="<?php echo ($numPerPage); ?>" data-page-current="<?php echo ($currentPage); ?>">
    </div>
  </div>
</div>