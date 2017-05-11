<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="/admin.php/Home/Jobtype/add/navTabId/jobtype" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">类型名称:</label>
                            <input type="text" data-rule="required" size="25" name="type_name" id="j_type_name" value="<?php echo ($Rs['type_name']); ?>" <?php if($id == 0): else: ?>disabled="disabled"<?php endif; ?> >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">使用状态:</label>
                            <select name="status" data-toggle="selectpicker" data-rule="required">
                                <option value="1">使用</option>
                                <option value="0">禁用</option>
                            </select>
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