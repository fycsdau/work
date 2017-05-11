<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="/admin.php/Home/Job/add/navTabId/job" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">名称:</label>
                            <input type="text" data-rule="required" size="25" name="job_name" id="j_name" value="<?php echo ($Rs['job_name']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">简介:</label>
                            <input type="text" data-rule="required" size="25"  name="desc" id="j_desc" value="<?php echo ($Rs['desc']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">公司：</label>
                            <input type="text" data-rule="required" size="25" name="company" id="j_company" value="<?php echo ($Rs['company']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">地址：</label>
                            <input type="text" data-rule="required" size="25" name="address" id="j_address" value="<?php echo ($Rs['address']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">手机号码：</label>
                            <input type="text" data-rule="required" size="25" name="mobile" id="j_mobile" value="<?php echo ($Rs['mobile']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">工资：</label>
                            <input type="text" data-rule="required" size="25" name="salary" id="j_salary" value="<?php echo ($Rs['salary']); ?>" >
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