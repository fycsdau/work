<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageHeader" style="padding:10px 10px;">
  <label>用户关注后自动推送消息</label>
</div>

<div class="bjui-pageContent">
  <form action="/admin.php/Home/Wechat/savesubscribe/navTabId/<?php echo CONTROLLER_NAME;?>" class="pageForm" data-toggle="validate">
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div class="pageFormContent" data-layout-h="0">
      <table class="table table-condensed table-hover" width="100%">
        <tbody>
          <tr>
            <td width="160" align="right">
              <label for='subscribe_msgtype' class='control-label x185'>回复消息类型:</label>
            </td>
            <td>
              <select name="msgtype" id="subscribe_msgtype" data-toggle="selectpicker" onchange="check_msgtype();">
                <option value="text" <?php if($subscribe["msgtype"] == 'text' ): ?>selected="selected"<?php endif; ?> >文本消息</option>
                <option value="newsitems" <?php if($subscribe["msgtype"] == 'newsitems' ): ?>selected="selected"<?php endif; ?> >图文消息</option>
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
              <textarea name="text" id="text" style="width:80%;height:200px;"><?php echo ($subscribe["text"]); ?></textarea>
            </td>
          </tr>
          <tr class="newsitemsinputs">
            <td align="right" valign="top">
              <label for='appsecret' class='control-label x185'>请填写图文消息内容</label>
            </td>
            <td></td>
          </tr>
          <?php $newsitem = $subscribe['newsitems']; $newsitems = unserialize($newsitem); $items_title = $newsitems['title']; $items_pic_url = $newsitems['pic_url']; $items_url = $newsitems['url']; for ($i=0; $i < 5; $i++) { if(isset($items_title[$i])){ $title = $items_title[$i]; $pic_url = $items_pic_url[$i]; $url = $items_url[$i]; }else{ $title = ''; $pic_url = ''; $url = ''; } ?>
      <!--文章开始-->
      <tr class="newsitemsinputs">
        <td align="right" valign="top">
          <label for='appsecret' class='control-label x185'>文章 <?php echo ($i+1); ?></label>
        </td>
        <td></td>
      </tr>
      <tr class="newsitemsinputs">
        <td align="right">
          <label for='items_title<?php echo ($i); ?>' class='control-label x185'>显示标题:</label>
        </td>
        <td>
          <input type='text' id='items_title<?php echo ($i); ?>' name='items_title[]' size='40' value="<?php echo ($title); ?>">
        </td>
      </tr>
      <tr class="newsitemsinputs">
        <td align="right">
          <label for='items_pic_url<?php echo ($i); ?>' class='control-label x185'>图片地址:</label>
        </td>
        <td>
          <input type='text' id='items_pic_url<?php echo ($i); ?>' name='items_pic_url[]' size='40' value="<?php echo ($pic_url); ?>">
          <a class="btn btn-default thumbnail_btn" id="items_pic_url<?php echo ($i); ?>">上传图片</a>
        </td>
      </tr>
      <tr class="newsitemsinputs">
        <td align="right">
          <label for='items_url<?php echo ($i); ?>' class='control-label x185'>连接地址:</label>
        </td>
        <td>
          <input type='text' id='items_url<?php echo ($i); ?>' name='items_url[]' size='40' value="<?php echo ($url); ?>">
        </td>
      </tr>
      <!--文章结束-->
      <?php } ?>
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
  <?php if($subscribe["msgtype"] == 'newsitems'): ?>.textinputs{display: none;}
    <?php else: ?>
    .newsitemsinputs{display: none;}<?php endif; ?>
  .newsitemsinputs input{ width: 60%;}
</style>
<script type="text/javascript">
  function check_msgtype(){
    msgtype = $("#subscribe_msgtype").val();
    if(msgtype == 'text'){
      $(".newsitemsinputs").hide();
      $(".textinputs").show();
    }else{
      $(".newsitemsinputs").show();
      $(".textinputs").hide();
    }
  }

  var editor = KindEditor.editor({
    allowFileManager : true
  });
  KindEditor('.thumbnail_btn').click(function() {
    var toid = $(this).attr('id');
    editor.loadPlugin('image', function() {
      editor.plugin.imageDialog({
        imageUrl : KindEditor('#url1').val(),
        clickFn : function(url, title, width, height, border, align) {
          KindEditor('#'+toid).val(url);
          editor.hideDialog();
        }
      });
    });
  });
</script>