<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
  <form action="/admin.php/Home/Dep/add/navTabId/dep" class="pageForm" data-toggle="validate">
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <input type="hidden" name="type" value="<?php echo ($type); ?>">
    <div class="pageFormContent" data-layout-h="0">
      <table class="table table-condensed table-hover" width="100%">
        <tbody>
          <tr>
            <td>
              <label for="j_title" class="control-label x85">角色名称：</label>
              <input type="text" data-rule="required" size="30" name="title" id="j_title" value="<?php echo ($Rs['title']); ?>" >
            </td>
          </tr>

          <tr>
            <td>
              <label for="j_sort" class="control-label x85">排序：</label>
              <input type="text"  size="5" data-toggle="spinner" data-min="0" data-max="100" data-step="1" data-rule="integer" name="sort" id="j_sort" value="<?php echo ($sortcount); ?>" >
            </td>
          </tr>
        </tbody>
      </table>
      </div>
  </form>
</div>

<div class="bjui-pageFooter">
  <ul>
    <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
    <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
  </ul>
</div>