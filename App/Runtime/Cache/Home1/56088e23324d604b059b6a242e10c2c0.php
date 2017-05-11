<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader">
  <div class="bjui-searchBar">
  <label style="height:24px; line-height:24px;">微信自定义菜单设置</label>
    <div class="pull-right">
      <span >
        <a href="/admin.php/Home/Wechat/syncmenu/navTabId/<?php echo CONTROLLER_NAME;?>" class="btn btn-green" data-toggle="doajax" data-icon="plus">同步菜单到微信</a>
      </span>
    </div>
  </div>
</div>

<div class="bjui-pageContent">
  <form action="/admin.php/Home/Wechat/savemenu/navTabId/<?php echo CONTROLLER_NAME;?>" class="pageForm" data-toggle="validate">
    <div class="pageFormContent" data-layout-h="0">
      <table class="table table-condensed table-hover" width="100%">

        <tbody>
          <tr>
            <td colspan="4" style="padding:10px;">
              最多可以有三个一级菜单，每个一级菜单最多可以有五个菜单。<br>
              一级菜单最多4个汉字，二级菜单最多7个汉字。<br>
              创建自定义菜单后，由于微信客户端缓存，需要24小时微信客户端才会展现出来。<br>
              请设置完所有的菜单后，再进行微信菜单同步操作！
            </td>
          </tr>
          <tr class=""> 
            <td style="white-space:nowrap;" width="80"></td> 
            <td style="white-space:nowrap;" width="220"><label>菜单名称</label></td> 
            <td width="100"><label>菜单类型</label></td>
            <td><label>菜单值</label></td>
          </tr>
          <?php
 $model = D("wechat_menu"); $menu = $model->where("parent='0'")->order("id asc")->select(); for ($i=0; $i < count($menu); $i++) { $id = $menu[$i]['id']; $menu_type = $menu[$i]['menu_type']; $name = $menu[$i]['name']; $value = $menu[$i]['value']; ?>
            <tr class=""> 
              <td style="white-space:nowrap;" width="80" align="center"><label>主菜单</label></td> 
              <td>
                <input type="text" name="title[]" maxlength="4" value="<?php echo $name; ?>">
              </td> 
              <td>
                <select name="menutype[]" data-toggle="selectpicker">
                  <option value="click"
                  <?php
 if($menu_type == 'click'){ echo 'selected'; } ?>
                  >click</option>
                  <option value="view"
                  <?php
 if($menu_type == 'view'){ echo 'selected'; } ?>
                  >view</option>
                </select>
              </td>
              <td>
                <input type="text" name="code[]" size="60" value="<?php echo $value; ?>">
              </td>
            </tr>
            <?php
 $child_menu = $model->where("parent='$id'")->order("id asc")->select(); for ($a=0; $a < count($child_menu); $a++) { $id = $child_menu[$a]['id']; $menu_type = $child_menu[$a]['menu_type']; $name = $child_menu[$a]['name']; $value = $child_menu[$a]['value']; ?>
              <tr class=""> 
                <td style="white-space:nowrap;"></td> 
                <td>
                  <div class="input_tree"></div>
                  <input type="text" name="title[]" maxlength="7" value="<?php echo $name; ?>">
                </td> 
                <td>
                  <select name="menutype[]" data-toggle="selectpicker">
                    <option value="click"
                    <?php
 if($menu_type == 'click'){ echo 'selected'; } ?>
                    >click</option>
                    <option value="view"
                    <?php
 if($menu_type == 'view'){ echo 'selected'; } ?>
                    >view</option>
                  </select>
                </td> 
                <td>
                  <input type="text" name="code[]" size="60" value="<?php echo $value; ?>">
                </td> 
              </tr>
              <?php
 } } ?>
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
<style type="text/css">
  .input_tree{ background: url(/Public/BJUI/themes/default/zTreeStandard.png); background-position: -83px -47px; width: 20px; height: 25px; float: left;}
</style>