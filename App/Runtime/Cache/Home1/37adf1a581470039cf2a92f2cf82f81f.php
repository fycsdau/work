<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="/admin.php/Home/Config/add/navTabId/config" class="pageForm" data-toggle="validate">
        <input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <label  class="control-label x85">分类：</label>
                            <select name="fenlei"  data-toggle="selectpicker" >
                                <?php if(is_array(C("CONFIG_CLASS"))): $i = 0; $__LIST__ = C("CONFIG_CLASS");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><option value="<?php echo ($class); ?>" <?php if($class == $Rs['fenlei'] ): ?>selected<?php endif; ?> ><?php echo ($class); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label  class="control-label x85">名称:</label>
                            <input type="text" data-rule="required" size="20" name="name"  value="<?php echo ($Rs["name"]); ?>" <?php if($id == 0): else: ?>disabled="disabled"<?php endif; ?> >
                            <span>用于C函数调用，只能使用英文且不能重复</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label  class="control-label x85">标题:</label>
                            <input type="text" data-rule="required" size="20"  name="title"  value="<?php echo ($Rs["title"]); ?>" >
                            <span>用于后台显示的配置标题</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label  class="control-label x85">类型：</label>
                            <select name="type"  data-toggle="selectpicker" >
                             <?php if(is_array(C("CONFIG_TYPE_LIST"))): $i = 0; $__LIST__ = C("CONFIG_TYPE_LIST");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == $Rs['type'] ): ?>selected<?php endif; ?> ><?php echo ($type); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td>
                    <label  class="control-label x85">配置值：</label>
                    <textarea cols="20" rows="2" data-toggle="autoheight" name="value"><?php echo ((isset($Rs["value"]) && ($Rs["value"] !== ""))?($Rs["value"]):''); ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label  class="control-label x85">配置项：</label>
                    <textarea cols="20" rows="2" data-toggle="autoheight" name="extra"><?php echo ((isset($Rs["extra"]) && ($Rs["extra"] !== ""))?($Rs["extra"]):''); ?></textarea>
                    <span>如果是枚举型 需要配置该项</span>
                </td>
            </tr>
            <tr>
                <td>
                    <label  class="control-label x85">说明：</label>
                    <textarea cols="20" rows="2" data-toggle="autoheight" name="remark"><?php echo ((isset($Rs["remark"]) && ($Rs["remark"] !== ""))?($Rs["remark"]):''); ?></textarea>
                    <span>配置详细说明</span>
                </td>
            </tr>
            <tr>
                <td>
                    <label  class="control-label x85">排序：</label>
                    <input type="text" size="5" data-toggle="spinner" data-min="0" data-max="100" data-step="1" data-rule="integer" name="sort" value="<?php echo ($Rs["sort"]); ?>" >
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