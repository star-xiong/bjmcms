$(function(){
	placeholder();
	public();
});
function public(){
	layer.config({
		title: "提示",
		resize: false,
		move: false
	});
	$(".hsearch .j-form .btn").click(function(){
		$(".hsearch").toggleClass("open");
	});
	$(".hsearch .j-input").on("input propertychange",function(){
		var text = $(this).val();
		if(text==""||text=="请输入关键词"){
			$(".hsearch .j-form .btn").attr("type","button");
		}
		else{
			$(".hsearch .j-form .btn").attr("type","submit");
		}
	});
}
function placeholder(){
	if(!isPlaceholer()){
		var els = $('input[placeholder],textarea[placeholder]');
		els.each(function(i, el){
			el = $(el);
			var defValue = el.attr('placeholder'),defColor = el.css('color');
			el.bind('focus',function(){
				if(this.value===''||this.value===defValue){
					$(this).css('color', defColor);
					this.value = '';
				}
			});
			el.bind('blur',function(){
				if(this.value === '' || this.value === defValue){
					var color = "#999";
					if($(this).attr("data-color")){
						var color = $(this).attr("data-color");
					}
					$(this).css('color',color);
					this.value = defValue;
				}
			});
			el.triggerHandler('blur');
			el.closest('form').submit(function(){
				var val = el.val();
				if(val === defValue){
					el.val('');
				}
			});
		});  
	}
}
function isPlaceholer(){
	var input = document.createElement('input');
	return "placeholder" in input;
}
//验证码
function verify(){
    $('#vdimgck').attr('src',$("#vdimgck").attr("src")+'?'+Math.random());
}
//layer.alert提示
function show_alert(msg,ico,text){
    layer.alert(msg, {
        icon: ico,
        end: function(){
            eval(text);
        }
    });
}
//登录弹出框
function login(){
	var html = '<div class="u-login">';
	html += '<form onsubmit="return submitFun(this);">';
	html += '<ul>';
	html += '<li><label for="u-name" class="u-name"></label><input type="text" name="name" class="j-input" id="u-name" placeholder="用户名"></li>';
	html += '<li><label for="u-pwd" class="u-pwd"></label><input type="text" name="password" class="j-input" id="u-pwd" placeholder="密码"></li>';
	html += '<li class="u-input u-authcode"><div class="u-vcode"><input type="text" class="j-input" id="captcha" name="captcha" placeholder="验证码"></div><div class="u-vcode-img"><img src="/captcha.html" id="vdimgck" onclick="verify();"></div></li>';
	html += '<li class="u-btn"><button class="btn" type="submit">提交</button></li>';
	html += '</ul>';
	html += '</form>';
	html += '</div>';
	layer.open({
		type: 1,
		title: '<b>登录</b>',
		skin: 'layui-layer-rim',
		shade: 0.5,
		area: ['380px', '360px'],
		content: html
	});
}
//登录验证
function submitFun(object){
	var username = $.trim($(object).find('#u-name').val());
	var password = $.trim($(object).find('#u-pwd').val());
	var captcha = $.trim($(object).find('#captcha').val());
	if(username == ''){
		show_alert('用户名不能为空!');
		return false;
	}
	if(password == ''){
		show_alert('密码不能为空!');
		return false;
	}
	if(captcha == ''){
		show_alert('验证码不能为空!');
		return false;
	}
	var field = $(object).serializeArray();
	$.ajax({
		type : 'post',
		url : '/login.html',
		data : field,		
		dataType : 'json',
		success : function(res){
			if(res['code']) {
				show_alert(res['msg']);
				verify();
			}
			else{
				layer.alert(res['msg'], {time: 2500,end:function(){window.location.reload();}});
			}
		}
	});
	return false;
}