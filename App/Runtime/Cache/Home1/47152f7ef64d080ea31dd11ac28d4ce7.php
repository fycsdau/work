<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="/admin.php/Home/User/add/navTabId/user" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">登陆账号:</label>
                            <input type="text" data-rule="required" size="25" name="username" id="j_username" value="<?php echo ($Rs['username']); ?>" <?php if($id == 0): else: ?>disabled="disabled"<?php endif; ?> >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">登陆密码:</label>
                            <input type="password" data-rule="required" size="25"  name="pwd" id="j_pwd" value="<?php echo ($Rs['password']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">真实姓名：</label>
                            <input type="text" data-rule="required" size="25" name="truename" id="j_truename" value="<?php echo ($Rs['truename']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">性别：</label>
                            <select name="sex"  data-toggle="selectpicker" data-rule="required">
                                <option value="男">男</option>
                                <option value="女">女</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_name" class="control-label x85">系统角色：</label>
                            <select name="depid" data-toggle="selectpicker" data-rule="required">
                                <option value="0">请选择</option>
                                <?php $listz=M('auth_group')->where(array('type'=>1,'status'=>1))->select(); ?>
                                <?php if(is_array($listz)): foreach($listz as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if($v["id"] == $Rs['posname'] ): ?>selected<?php endif; ?> >
                                        <?php echo ($v["title"]); ?>
                                    </option><?php endforeach; endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">手机号码：</label>
                            <input type="text" data-rule="required" size="25" name="phone" id="j_phone" value="<?php echo ($Rs['phone']); ?>" >
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