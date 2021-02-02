<div class="login-main">
	<div class="login-form">
		<div class="login-form-title">账户登录</div>
		<form onsubmit="return loginSubmit(this);">
			<ul>
				<li class="u-input">
					<label for="loginname" class="u-label u-name"></label>
					<input type="text" class="j-input" id="username" name="name" placeholder="邮箱/用户名/手机号">
				</li>
				<li class="u-input">
					<label for="loginpwd" class="u-label u-pwd"></label>
					<input type="password" class="j-input" id="password" name="password" placeholder="密码">
				</li>
				<li class="u-input u-authcode">
					<div class="u-vcode">
						<input type="text" class="j-input" id="captcha" name="captcha" placeholder="验证码">
					</div>
					<div class="u-vcode-img">
						<img src="{:captcha_src()}" id="vdimgck" onclick="verify();">
					</div>
				</li>
				<li class="u-forget">
					<p class="ltxt">
						<input type="checkbox" class="checkbox" name="keeptime" id="keeptime" value="2592000">
						<label for="keeptime">自动登录</label>
						<em>|</em>
						<a href="{:url('password')}{$lang}">忘记密码？</a>
					</p>
					<div class="login-regis"><a href="{:url('register')}{$lang}">免费注册 ></a></div>
				</li>
				<li class="u-btn">
					<button class="btn" type="submit">登  录</button>
				</div>
			</ul>
		</form>
	</div>
</div>

<script type="text/javascript">	
function loginSubmit(object){
	var username = $.trim($('#username').val());
	var password = $.trim($('#password').val());
	var captcha = $.trim($('#captcha').val());
	if(username == ''){
		show_error('邮箱/用户名/手机号不能为空!');
		return false;
	}
	if(password == ''){
		show_error('密码不能为空!');
		return false;
	}
	if(captcha == ''){
		show_error('验证码不能为空!');
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
				show_error(res['msg']);
				window.location.href=res['data']['url'];
			}
			
		}
	});
	return false;
}
</script>
