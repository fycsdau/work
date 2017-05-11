<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
  <form action="/admin.php/Home/Wechat/keywordsave/navTabId/wechat_keyword" class="pageForm" data-toggle="validate">
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div class="pageFormContent" data-layout-h="0">
      <table class="table table-condensed table-hover" width="100%">
        <tbody>
          <tr>
            <td align="right" width="125">
            <label for='title_input' class='control-label'>用户发送关键字:</label>
            </td>
            <td>
              <input type='text' id='keywords' name='keywords' data-rule='required;' size='20' value='<?php echo ($keyword); ?>'  >
            </td>
          </tr>
          <tr>
            <td align="right">
              <label for='title_input' class='control-label x85'>回复消息类型:</label>
            </td>
            <td>
              <select name="msgtype" id="replaytype" data-toggle="selectpicker" onchange="check_replaytype();">
                <option value="text" <?php if($msgtype == 'text' ): ?>selected="selected"<?php endif; ?> >文本消息</option>
                <option value="newsitems" <?php if($msgtype == 'newsitems' ): ?>selected="selected"<?php endif; ?> >图文消息</option>
              </select>
            </td>
          </tr>
          <tr class="textinputs">
            <td align="right" valign="top">
              <label for='appsecret' class='control-label x185'>请填写文本消息内容</label>
            </td>
            <td></td>
          </tr>
          <tr class="textinputs">
            <td align="right" valign="top">
              <label for='text' class='control-label x185'>文本消息内容:</label>
            </td>
            <td>
              <textarea name="text" id="text" style="width:80%;height:100px;"><?php echo ($text); ?></textarea>
            </td>
          </tr>
          <tr class="newsitemsinputs">
            <td align="right" valign="top">
              <label for='appsecret' class='control-label x185'>请填写图文消息内容</label>
            </td>
            <td></td>
          </tr>
          <tr class="newsitemsinputs">
            <td align="right">
              <label for='items_title' class='control-label x185'>显示标题:</label>
            </td>
            <td>
              <input type='text' id='items_title' name='items_title' size='40' value="<?php echo ($newsitems_title); ?>">
            </td>
          </tr>
          <tr class="newsitemsinputs">
            <td align="right">
              <label for='items_pic_url' class='control-label x185'>图片地址:</label>
            </td>
            <td>
              <input type='text' id='items_pic_url' name='items_pic_url' size='40' value="<?php echo ($newsitems_pic_url); ?>">
            </td>
          </tr>
          <tr class="newsitemsinputs">
            <td align="right">
              <label for='items_url' class='control-label x185'>连接地址:</label>
            </td>
            <td>
              <input type='text' id='items_url' name='items_url' size='40' value="<?php echo ($newsitems_url); ?>">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>

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
<style type="text/css">
<?php if($msgtype == 'newsitems'): ?>.textinputs{display: none;}
  .newsitemsinputs{display: table-row;;}
  <?php else: ?>
  .textinputs{display:table-row;}
  .newsitemsinputs{display: none;}<?php endif; ?>
.newsitemsinputs input{ width: 60%;}
</style>
<script type="text/javascript">
  function check_replaytype(){
    msgtype = $("#replaytype").val();
    if(msgtype == 'text'){
      $.CurrentDialog.find(".newsitemsinputs").hide();
      $.CurrentDialog.find(".textinputs").show();
    }else{
      $.CurrentDialog.find(".newsitemsinputs").show();
      $.CurrentDialog.find(".textinputs").hide();
    }
  }
</script>