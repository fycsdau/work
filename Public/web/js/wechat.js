/*
 * 注意：
 * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
 * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
 * 3. 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
 *
 * 如有问题请通过以下渠道反馈：
 * 邮箱地址：weixin-open@qq.com
 * 邮件主题：【微信JS-SDK反馈】具体问题
 * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
 */
wx.ready(function () {

  var images = {
    localId: [],
    serverId: []
  };
  document.querySelector('#chooseImage').onclick = function () {
    alert('请对您的纸质订单进行拍照!\n拍照时请选择明亮的环境，拍摄清晰的照片，发送给我们。');
    wx.chooseImage({
      success: function (res) {
    	if(res.localIds.length == 1){
    		images.localId = res.localIds;
            uploadImage();
    	}
    	else{
    		alert('只允许选择一张图片');
    	}
      }
    });
  };


  document.querySelector('#chooseImage_ice').onclick = function () {
    alert('请对您的纸质订单进行拍照!\n拍照时请选择明亮的环境，拍摄清晰的照片，发送给我们。');
    wx.chooseImage({
      success: function (res) {
        if(res.localIds.length == 1){
            images.localId = res.localIds;
            uploadImage();
        }
        else{
            alert('只允许选择一张图片');
        }
      }
    });
  };
  function uploadImage() {
    if (images.localId.length == 0) {
      alert('请先使用 chooseImage 接口选择图片');
      return;
    }
    var i = 0, length = images.localId.length;
    images.serverId = [];
    
    function upload() {
    	setTimeout(function(){
    		wx.uploadImage({
    	        localId: images.localId[i],
    	        success: function (res) {
    	          i++;
    	          images.serverId.push(res.serverId);
    	          if (i < length) {
    	            upload();
    	          }
    	          var domain = $("#domain").val();
    	          var imageId = images.serverId[0];
    	          $.ajax({
    	        	  type: 'POST',
    	        	  url: domain+'/wechat/createOrder/',
    	        	  data: {'imageId':imageId},
    	        	  success: function(){
    	        		  //$("#message").css('display','block');
    	        		  location.href='/wechat/order/list/?banner';
    	        	  },
    	        	  error:function(){alert('error')}

    	        	});     

    	        },
    	        fail: function (res) {
    	          alert(JSON.stringify(res));
    	        }
    	      });
    		
    	}, 100);
      
    }
    upload();
  };

});

wx.error(function (res) {
  alert(res.errMsg);
});
