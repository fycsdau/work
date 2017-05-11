<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
	<form action="/admin.php/Home/Goods/save_goods/navTabId/goods" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                	<tr>
                        <td>
                            <label for="j_title" class="control-label x85">运营商:</label>
                            <select name="operate_name" id="operate_name" data-toggle="selectpicker" data-rule="required" onchange="javascript:add_package()">
                            	<option value="中国移动">中国移动</option>
                            	<option value="中国联通">中国联通</option>
                            	<option value="中国电信">中国电信</option>
                            </select>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <label for="j_title" class="control-label x85">供应商:</label>
                            <select name="supplier_id" id="supplier_id" data-toggle="selectpicker" data-rule="required" onchange="javascript:add_package()">
                            	<option value="0">阿里大鱼</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">套餐名称:</label>
                          <label id="package_select"></label>
                        <!-- 
                         -->   
                        </td>
                    </tr>
                   
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">进折:</label>
                            <input type="text" data-rule="required" size="15" name="supplier_point" id="supplier_point" value="<?php echo ($Rs['supplier_point']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">优先级:</label>
                            <input type="text" data-rule="required" size="15" name="level" id="level" value="<?php echo ($Rs['level']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                          	<label for="j_title" class="control-label x85">省份:</label>
                          	<select name="province_select" id="province_select" data-toggle="selectpicker" data-rule="required" >
                            	<option value="0">全国</option>
                            	<option value="1">北京</option>
                            	<option value="2">安徽</option>
                            	<option value="3">福建</option>
                            	<option value="4">甘肃</option>
                            	<option value="5">广东</option>
                            	<option value="6">广西</option>
                            	<option value="7">贵州</option>
                            	<option value="8">海南</option>
                            	<option value="9">河北</option>
                            	<option value="10">河南</option>
                            	<option value="11">黑龙江</option>
                            	<option value="12">湖北</option>
                            	<option value="13">湖南</option>
                            	<option value="14">吉林</option>
                            	<option value="15">江西</option>
                            	<option value="16">江苏</option>
                            	<option value="17">辽宁</option>
                            	<option value="18">内蒙古</option>
                            	<option value="19">宁夏</option>
                            	<option value="20">青海</option>
                            	<option value="21">山西</option>
                            	<option value="22">山东</option>
                            	<option value="23">陕西</option>
                            	<option value="24">上海</option>
                            	<option value="25">四川</option>
                            	<option value="26">天津</option>
                            	<option value="27">西藏</option>
                            	<option value="28">新疆</option>
                            	<option value="29">云南</option>
                            	<option value="30">浙江</option>
                            	<option value="31">重庆</option>
                            	<option value="32">香港</option>
                            	<option value="33">澳门</option>
                            	<option value="34">台湾</option>
                          	</select>
                          	<label for="j_title" class="control-label x85">平台折扣:</label>
                            <input type="text" data-rule="required" name="shop_point" id="shop_point" value="<?php echo ($Rs['shop_point']); ?>" size="8" />
                            <input type="button" value="添加" class="btn btn-green"  data-toggle="dialog" onclick="javascript:city_setting()" />
                        </td>
                        <input type="hidden" id="select_province" name="select_province" value="">
                        <input type="hidden" id="province_point" name="province_point" value="">
                    </tr>
                   	<tr>
                        <td id="city_setting">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
		<div class="bjui-pageFooter">
		    <ul>
		        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
		        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
		    </ul>
		</div>
    </form>
</div>


<script type="text/javascript">
add_package();
function add_package(){
	
	$("#package_id").remove();
	var operate_name = document.getElementById("operate_name").value;
	var supplier = document.getElementById("supplier_id").value;
	var html = '<select name="package_id" id="package_id" data-toggle="selectpicker" data-rule="required" >';
	$.ajax({
   		type:"POST",
   		url:"<?php echo U('Goods/ajaxGetPackage');?>",
   		data:{"operate_name":operate_name,"supplier":supplier},
 		//  datatype: "html",//"xml", "html", "script", "json", "jsonp", "text".
   		//成功返回之后调用的函数            
   		success:function(data){
 			for(var i=0;i<data.length;i++){
 				html += '<option value='+data[i]["package_id"]+'>'+data[i]["package_name"]+'</option>';
 			}
			html += '</select>';
			$("#package_select").append(html);
  		},
	});
}
//添加城市
function city_setting(){
	var province = document.getElementById("province_select").value;
	var province_name = document.getElementById("province_select").options[document.getElementById("province_select").selectedIndex].text;
	var shop_point = document.getElementById("shop_point").value;
	var select_province = document.getElementById("select_province").value;
	var province_point = document.getElementById("province_point").value;
	
	if( select_province.indexOf(province) != -1){
        alert('您已添加此省份！');
    }else if(shop_point == ''){
    	alert('请输入折扣！');
    }else{
		$("#city_setting").append('<div id=province'+province+' onclick=remove_province('+province+')><label for="j_title" class="control-label x85">省份:</label>'+ province_name +'<label for="j_title" class="control-label x85">折扣：</label>'+shop_point+'</div>');                        
		if(select_province == ''){
			select_province = province;
			province_point = province+','+shop_point+','+province_name;
			
	    }else{
	    	select_province = select_province + ',' + province;
	    	province_point = province_point + ';' + province+','+shop_point+','+province_name;
	    }
		document.getElementById("select_province").value = select_province;
		document.getElementById("province_point").value = province_point;
    }
}

function remove_province(province){
	var province_name = document.getElementById("province_select").options[province].text;
	var province_point = $("#province_point").val();
	var province_num = province_point.split(";");
	set_province= '';
	set_province_point = '';
	for (var i = 0; i < province_num.length; i++) {
		var province_id = province_num[i].split(",");
		if(province_id[0] != province){
			if( set_province != '' ){
				set_province = set_province + ',' + province_id[0];
				set_province_point = set_province_point + ';' + province_id[0]+','+province_id[1]+','+province_id[2];
			}else{
				set_province = province_id[0];
				set_province_point = province_id[0]+','+province_id[1]+','+province_id[2];
			}
		}
	}
	if(confirm('确定要移除'+ province_name +'?')){
		$("#province"+province).remove();
		$("#select_province").val(set_province);
		$("#province_point").val(set_province_point);
	}
}

</script>