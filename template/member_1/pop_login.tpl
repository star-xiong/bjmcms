<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>登录</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link rel="stylesheet" type="text/css" href="/template/member/skin/css/member.css">
<link rel="stylesheet" type="text/css" href="/template/member/skin/css/pop_login.css">
<script type="text/javascript" src="/template/member/skin/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/template/member/skin/js/layer/layer.js"></script>
<script type="text/javascript" src="/template/member/skin/js/common.js"></script>
</head>
<body>
<div class="u-login">
	<form class="u-login-form" onsubmit="return loginSubmit(this);">
		<div class="u-reg"><a href="{:url('register')}{$lang}" target="_blank">免费注册</a></div>
		<div class="u-error"></div>
		<div class="u-input mb20">
			<label for="loginname" class="u-label u-name"></label>
			<input type="text" class="j-input" id="username" name="name" placeholder="邮箱/用户名/手机号">
		</div>
		<div class="u-input mb14">
			<label for="loginpwd" class="u-label u-pwd"></label>
			<input type="password" class="j-input" id="password" name="password" placeholder="密码">
		</div>
		<div class="u-input u-authcode">
			<div class="u-vcode">
				<input type="text" class="j-input" id="captcha" name="captcha" placeholder="验证码">
			</div>
			<div class="u-vcode-img">
				<img src="{:captcha_src()}" id="vdimgck" onclick="verify();">
			</div>
		</div>
		<div class="u-forget">
			<p class="ltxt">
				<input type="checkbox" class="checkbox" name="keeptime" id="keeptime" value="2592000">
				<label for="keeptime">自动登录</label>
			</p>
			<a href="{:url('password')}{$lang}" target="_blank">忘记密码？</a>
		</div>
		<div class="u-btn">
			<button class="btn" type="submit">登  录</button>
		</div>
	</form>
</div>
<script type="text/javascript">	
function loginSubmit(object){
	$('.u-error').hide().html('');
	var username = $.trim($('#username').val());
	var password = $.trim($('#password').val());
	var captcha = $.trim($('#captcha').val());
	if(username == ''){
		showErrorMsg('邮箱/用户名/手机号不能为空!');
		return false;
	}
	if(password == ''){
		showErrorMsg('密码不能为空!');
		return false;
	}
	if(captcha == ''){
		showErrorMsg('验证码不能为空!');
		return false;
	}
	var field = $(object).serializeArray();
	$.ajax({
		type : 'post',
		url : '{:url("login")}',
		data : field,		
		dataType : 'json',
		success : function(res){
			if(res['code']) {
				show_error(res['msg']);
				verify();
			}
			else{
				layer.alert(res['msg'], {time: 2500,end:function(){parent.location.reload();}});
			}
		}
	});
	return false;
}
function showErrorMsg(msg){
	$('.u-error').show().html(msg);
}
</script>
</body>
</html>
