<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true" data-toggle="tablefixed">
        <thead>
            <tr>
                <th align="center" height=30>登陆账号</th>
                <th align="center">真实姓名</th>
                <th align="center">系统角色</th>
                <th align="center">移动电话</th>
                <th align="center">登录IP</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                    <td align="center" height=30><?php echo ($v["username"]); ?></td>
                    <td align="center"><?php echo ($v["truename"]); ?></td>
                    <td align="center"><?php echo (getdepname($v["depid"])); ?> </td>
                    <td align="center"><?php echo ($v["phone"]); ?></td>
                    <td align="center"><a href="http://ip138.com/ips138.asp?ip=<?php echo ($v["loginip"]); ?>&action=2" target="_blank"><?php echo ($v["loginip"]); ?></a></td>
                </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>共 <?php echo ($totalCount); ?> 条  每页 <?php echo ($numPerPage); ?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo ($totalCount); ?>" data-page-size="<?php echo ($numPerPage); ?>" data-page-current="<?php echo ($currentPage); ?>">
    </div>
</div>