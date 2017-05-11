$(function(){
	$('.user-icon-fanhui').on('tap',function(){
		history.back();
	});

	$("a, .loading").on('click', function(){
		$("#loading").show();
	})

	$("a.noloading").on('click', function(){
		$("#loading").hide();
	})
})