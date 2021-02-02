$(function(){
	public();
});
function public(){
	layer.config({
		title: "提示",
		resize: false,
		move: false
	});
	var _footer_scroll = 0;
    $(window).scroll(function () {
        if (_footer_scroll == 0) {
            if ($(this).scrollTop() > 150) {
                $(".finner-bdbox").css({"left": "0"});
                $(".finner-btn").css({"left": "-220px"});
                $(".finner-box").fadeIn();
            } else {
                $(".finner-bdbox").css({"left": "-100%"});
                $(".finner-btn").css({"left": "100%"});
                $(".finner-box").fadeOut();
            }
        }
    });
    $(".finner-close").click(function () {
        _footer_scroll = 1;
        $(".finner-bdbox").animate({"left": "-100%"}, {duration: 300, easing: 'easeOutQuad'});
        $(".finner-btn").delay(100).animate({"left": "100%"}, {duration: 200, easing: 'easeInQuad'});
        $(".finner-box").fadeOut();
    });
    $(".finner-btn").click(function () {
        _footer_scroll = 0;
        $(".finner-bdbox").animate({"left": "0"}, {duration: 300, easing: 'easeInQuad'});
        $(".finner-btn").animate({"left": "-220px"}, {duration: 200, easing: 'easeOutQuad'});
        $(".finner-box").fadeIn();
    });
}
//收藏
function favorite(id,object) {
	if(id == '') {
		show_error('参数错误!');
		return false;
	}
	$.ajax({
		type : 'get',
		url : '/favorite/' + id + '.html',
		dataType : 'json',
		success : function(res){
			show_error(res.msg);
			$(object).parents("li").remove();	
		}
	});
	return false;
}
function show_error(msg,ico){
	layer.alert(msg, {icon: ico});
	return false;
}
//登录弹出层
function pop_login(){
	layer.open({
		type: 2,
		title: '<b>登录野河狸交易网</b>',
		skin: 'layui-layer-rim',
		shadeClose: true,
		shade: 0.5,
		area: ['490px', '460px'],
		content: "pop_login.html"
	});
}
//验证码
function verify(){
    $('#vdimgck').attr('src',$("#vdimgck").attr("src")+'?'+Math.random());
}
//验证手机号码
function isPhoneNo(phone){
	var pattern = /^1[34578]\d{9}$/;
	return pattern.test(phone);
}
//验证邮箱
function isEmailNo(email){
	var pattern = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	return pattern.test(email);
}
function footerSubmit(object){
	var phone = $.trim($('#finner-phone').val());
	if(phone == ''){
		show_error('请输入电话号码!');
		return false;
	}
	if(isPhoneNo(phone)==false){
		show_error('手机号码不正确!');
		return false;
	}
	var field = $(object).serializeArray();
	/*$.ajax({
		type : 'post',
		url : '',
		data : field,		
		dataType : 'json',
		success : function(res){

		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			show_error('网络失败，请刷新页面后重试');
		}
	});*/
	return false;
}