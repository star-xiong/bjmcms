$(function(){
	public();
});

function public(){
	layer.config({
		title: "提示",
		resize: false,
		move: false
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
		title: '<b>登录</b>',
		skin: 'layui-layer-rim',
		shadeClose: true,
		shade: 0.5,
		area: ['490px', '460px'],
		content: "pop_login.html"
	});
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
// function footerSubmit(object){
// 	var phone = $.trim($('#finner-phone').val());
// 	if(phone == ''){
// 		show_error('请输入电话号码!');
// 		return false;
// 	}
// 	if(isPhoneNo(phone)==false){
// 		show_error('手机号码不正确!');
// 		return false;
// 	}
// 	var field = $(object).serializeArray();
// 	/*$.ajax({
// 		type : 'post',
// 		url : '',
// 		data : field,		
// 		dataType : 'json',
// 		success : function(res){

// 		},
// 		error : function(XMLHttpRequest, textStatus, errorThrown){
// 			show_error('网络失败，请刷新页面后重试');
// 		}
// 	});*/
// 	return false;
// }

//写cookies 

function setCookie(name,value) 
{ 
    var Days = 30; 
    var exp = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 

//读取cookies 
function getCookie(name) 
{ 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
    if(arr=document.cookie.match(reg))
 
        return unescape(arr[2]); 
    else 
        return null; 
} 

//删除cookies 
function delCookie(name) 
{ 
    var exp = new Date(); 
    exp.setTime(exp.getTime() - 1); 
    var cval=getCookie(name); 
    if(cval!=null) 
        document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
} 