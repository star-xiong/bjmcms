<div class="register-main">
	<div class="register-wp">
		<div class="register-tabtit">
			<ul>
				<li class="active"><a href="javascript:;">重置密码</a></li>
			</ul>
			<div class="regis-log">我已注册，马上<a href="{:url('login')}{$lang}">登录></a></div>
		</div>
		<div class="register-tabcont">
			<div class="register-form">
				<form onsubmit="return registerSubmit(this);">
					<ul>						
						<li>
							<label><em>*</em><span>手机号码：</span></label>
							<div class="input">
								<input type="text" class="j-input" name="phone" id="txtUserphone" placeholder="请输入手机号码">
							</div>
						</li>
						<li>
							<label><em>*</em><span>图形验证码：</span></label>
							<div class="input imgCode-input">
								<input type="text" class="j-input" name="captcha" id="txtUsercaptcha" placeholder="手机验证码">
								<img src="{:captcha_src()}" id="vdimgck" onclick="verify();">
							</div>
						</li>
						<li>
							<label><em>*</em><span>短信验证码：</span></label>
							<div class="input phoneCode-input">
								<input type="text" class="j-input" name="phoneCode" id="txtUserphoneCode" placeholder="手机验证码">
								<button type="button" class="icode" id="phone-code" onclick="sendCode();">发送</button>
							</div>
						</li>
						<li>
							<label><em>*</em><span>设置密码：</span></label>
							<div class="input">
								<input type="password" class="j-input" name="password" id="txtUserPassword" placeholder="6-16位大小写英文字母、数字或符号的组合">
							</div>
						</li>
						<li>
							<label><em>*</em><span>确认密码：</span></label>
							<div class="input">
								<input type="password" class="j-input" name="repassword" id="txtUserPwd" placeholder="请再次输入密码">
							</div>
						</li>						
						
						<li class="liney">
							<label>&nbsp;</label>
							<div class="input">
								<input type="hidden" name="__token__" value="{$Request.token}" />
								<button class="regbtn" type="submit">重置密码</button>
							</div>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">	
function registerSubmit(object){
	var username = $.trim($('#txtUsername').val());
	var phone = $.trim($('#txtUserphone').val());
	var password = $.trim($('#txtUserPassword').val());
	var pwd = $.trim($('#txtUserPwd').val());
	var captcha = $.trim($('#txtUsercaptcha').val());

	if(phone == ''){
		show_error('手机号码不能为空!');
		return false;
	}
	if(isPhoneNo(phone)==false){
		show_error('请输入正确的手机号码!');
		return false;
	}
	if(password.length<6||password.length>16){
		show_error('密码有效长度为6-16位,并且不得有空格!');
		return false;
	}
	if(pwd == ''){
		show_error('请再次输入密码!');
		return false;
	}
	if(password!=pwd){
		show_error('两次密码输入不一致，请重新输入!');
		return false;
	}
	if(captcha == ''){
		show_error('图形验证码不能为空!');
		return false;
	}
	
	var field = $(object).serializeArray();
	$.ajax({
		type : 'post',
		url : '{:url("password")}',
		data : field,		
		dataType : 'json',
		success : function(res){
			if(res['code']) {
				show_error(res['msg']);
			}
			else{
				show_error(res['msg']);
				window.location.href=res['url'];
			}
			
		}
	});
	return false;
}


function sendCode(){
	var phone = $.trim($('#txtUserphone').val());
	var captcha = $.trim($('#txtUsercaptcha').val());
	if(phone == ''){
		show_error('手机号码不能为空!');
		return false;
	}
	if(isPhoneNo(phone)==false){
		show_error('请输入正确的手机号码!');
		return false;
	}
	if(captcha == ''){
		show_error('图形验证码不能为空!');
		return false;
	}
	$.ajax({
		url: '{:url("sendpwd")}',
		type: 'POST',
		data: {'captcha':captcha,'phone':phone},
		dataType: 'json',
		beforeSend: function(){
			$("#phone-code").attr('disabled',"true");
		},
		success:function(res){
			if(res['code']) {
				show_error(res['msg']);
				$("#phone-code").removeAttr("disabled");
				verify();
			}
			else{
				intAs = 60; // 手机短信超时时间
				jsInnerTimeout('phone-code',intAs);
				show_error(res['msg']);
			}
		}
	});

}

//倒计时函数
function jsInnerTimeout(id,intAs){
	var codeObj=$("#"+id);
	intAs--;
	if(intAs<=-1){
		codeObj.removeAttr("disabled");
		codeObj.html("发送");
		return true;
	}
	codeObj.text(intAs+'秒');
	setTimeout("jsInnerTimeout('"+id+"',"+intAs+")",1000);
}

</script>
