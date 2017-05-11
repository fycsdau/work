<?php if (!defined('THINK_PATH')) exit();?><div class="bjui-pageContent">
    <form action="/admin.php/Home/Goods/save_package/navTabId/goods" class="pageForm" data-toggle="validate">
    	<input type="hidden" name="id" value="<?php echo ($id); ?>">
        <div class="pageFormContent" data-layout-h="0">
            <table class="table table-condensed table-hover" width="100%">
                <tbody>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">供应商:</label>
                            <select name="supplier" id="supplier" data-toggle="selectpicker" data-rule="required" >
                            	<option value="0">阿里大鱼</option>
                            </select>
                        </td>
                    </tr>
                	<tr>
                        <td>
                            <label for="j_title" class="control-label x85">运营商:</label>
                            <select name="operate_name" id="operate_name" data-toggle="selectpicker" data-rule="required" >
                            	<option value="中国移动">中国移动</option>
                            	<option value="中国联通">中国联通</option>
                            	<option value="中国电信">中国电信</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                          	<label for="j_title" class="control-label x85">省份:</label>
                          	<select name="province" id="province" data-toggle="selectpicker" data-rule="required" >
                            	<option value="全国">全国</option>
                            	<option value="北京">北京</option>
                            	<option value="安徽">安徽</option>
                            	<option value="福建">福建</option>
                            	<option value="甘肃">甘肃</option>
                            	<option value="广东">广东</option>
                            	<option value="广西">广西</option>
                            	<option value="贵州">贵州</option>
                            	<option value="贵州">贵州</option>
                            	<option value="河北">河北</option>
                            	<option value="河南">河南</option>
                            	<option value="黑龙江">黑龙江</option>
                            	<option value="湖北">湖北</option>
                            	<option value="湖南">湖南</option>
                            	<option value="吉林">吉林</option>
                            	<option value="江西">江西</option>
                            	<option value="江苏">江苏</option>
                            	<option value="辽宁">辽宁</option>
                            	<option value="内蒙古">内蒙古</option>
                            	<option value="宁夏">宁夏</option>
                            	<option value="青海">青海</option>
                            	<option value="山西">山西</option>
                            	<option value="山东">山东</option>
                            	<option value="陕西">陕西</option>
                            	<option value="上海">上海</option>
                            	<option value="四川">四川</option>
                            	<option value="天津">天津</option>
                            	<option value="西藏">西藏</option>
                            	<option value="新疆">新疆</option>
                            	<option value="云南">云南</option>
                            	<option value="浙江">浙江</option>
                            	<option value="重庆">重庆</option>
                            	<option value="香港">香港</option>
                            	<option value="澳门">澳门</option>
                            	<option value="台湾">台湾</option>
                          	</select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">套餐名称:</label>
                            <input type="text" data-rule="required" size="25" name="package_name" id="package_name" value="<?php echo ($Rs['package_name']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">套餐code:</label>
                            <input type="text" data-rule="required" size="25" name="package_code" id="package_code" value="<?php echo ($Rs['package_code']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">市场价:</label>
                            <input type="text" data-rule="required" size="25" name="market_price" id="market_price" value="<?php echo ($Rs['market_price']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="j_title" class="control-label x85">一级分成:</label>
                            <input type="text" data-rule="required" size="25" name="one_rate" id="one_rate" value="<?php echo ($Rs['one_rate']); ?>">
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <label for="j_title" class="control-label x85">二级分成:</label>
                            <input type="text" data-rule="required" size="25" name="two_rate" id="two_rate" value="<?php echo ($Rs['two_rate']); ?>">
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <label for="j_title" class="control-label x85">三级分成:</label>
                            <input type="text" data-rule="required" size="25" name="three_rate" id="three_rate" value="<?php echo ($Rs['three_rate']); ?>">
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

//添加城市
function city_setting(){
	var province = document.getElementById("province").value;
	var province_name = document.getElementById("province").options[document.getElementById("province").selectedIndex].text;
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
			province_point = province+','+shop_point;
			
	    }else{
	    	select_province = select_province + ',' + province;
	    	province_point = province_point + ';' + province+','+shop_point;
	    }
		document.getElementById("select_province").value = select_province;
		document.getElementById("province_point").value = province_point;
    }
}

function remove_province(province){
	var province_name = document.getElementById("province").options[province].text;
	var province_point = $("#province_point").val();
	var province_num = province_point.split(";");
	set_province= '';
	set_province_point = '';
	for (var i = 0; i < province_num.length; i++) {
		var province_id = province_num[i].split(",");
		if(province_id[0] != province){
			if( set_province != '' ){
				set_province = set_province + ',' + province_id[0];
				set_province_point = set_province_point + ';' + province_id[0]+','+province_id[1];
			}else{
				set_province = province_id[0];
				set_province_point = province_id[0]+','+province_id[1];
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