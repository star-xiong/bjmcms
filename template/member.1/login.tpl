<div class="reg_warp">
	<div class="reg_header_top clearfix">
		<div class="reg_header center clearfix">
			<div class="lh_logo fl">
				<a class="fl logo_link" href="./"><img src=""></a>
			</div>
			<p class="reg_login_link fr f14">
				<a class="fr t_c login_link" href="{:url('register')}{$lang}">注册</a>
				如未注册， 点此
			</p>
		</div>
	</div>
	<div class="reg_main center">
		<ul class="reg_nav f18 t_c clearfix">
			<li class="user_reg on login_user">
				<font class="iconfont">&#xe610;</font>
				<font id="login_cat">会员登录</font>
			</li>
			<li class="reg_success login_mobile">
				<font class="iconfont">&#xe615;</font>手机动态密码登录
			</li>
		</ul>
		<div class="reg_cont reg_cont1 relative" style="">
			<div class="usertoputong">
				<a class="card_login" href="javascript:void(0)">进入会员卡登录</a>
			</div>
			<div class="usertocard" style="display:none;">
				<a class="putong_login" href="javascript:void(0)">进入普通会员登录</a>
			</div>
			<form name="formLogin" onsubmit="return loginSubmit(this);">
				<div class="register_infor">
					<ul>
						<li class="input_box">
							<span class="t_text user_putong_card">用户名、手机、邮箱</span>
							<input type="text" name="username" id="username" autocomplete="off">
							<span class="error_icon"></span>
						</li>
						<li class="error_box" id="username_notice">
							<em></em>
						</li>
						<li class="input_box">
							<span class="t_text pass_putong_card">密码</span>
							<input type="password" name="password" id="password" autocomplete="off">
							<span class="error_icon"></span>
						</li>
						<li class="error_box" id="password_notice">
							<em></em>
						</li>
						<li class="security_code input_box">
							<span class="t_text">验证码</span>
							<input type="text" class="code_input" name="captcha" maxlength="6" id="yzm_m" value="">
							<span class="error_icon"></span>
							<img src="{:captcha_src()}" id="userlogincaptcha" onclick="javascript:$(this).attr('src', '{:captcha_src()}'+'?'+Math.random());" alt="captcha" style="vertical-align: middle;cursor: pointer;">
						</li>
						<li class="error_box" id="yzm_notice_m">
							<em></em>
						</li>
						<li class="lizi_law"> <a class="forget_psd" href="{:url('password')}{$lang}">短信找回密码?</a> <label>
							<input type="checkbox" value="1" name="remember" id="remember" class="remember-me">请保存我这次的登录信息。</label></li>
						<li class="error_box">
							<em></em>
						</li>
						<li class="go2register">
							<input type="hidden" name="login_type" value="0" id="login_type" />
							<input type="hidden" name="act" value="act_login" />
							<input type="hidden" name="back_act" value="./index.php" />
							<button type="submit" class="btn submit_btn">登 录</button>
						</li>
					</ul>
					<div class="other-form">
						<div class="other-login-tit">使用第三方帐号登录</div>
						<div class="other-login">
							<a class="qq" href="user.php?act=oath&type=qq"></a> 
							<a class="sina" href="user.php?act=oath&type=weibo"></a> 
							<a class="alipay" href="user.php?act=oath&type=alipay"></a> 
							<a class="weixin" href="user.php?act=oath&type=weixin"></a>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		<div class="reg_cont reg_cont2 relative" style="display:none;">
			<form name="formUsermobile"  onsubmit="return loginSubmit(this);">
				<div class="register_infor">
					<ul>
						<li class="input_box">
							<span class="t_text user_putong_card">已注册的手机号</span>
							<input type="text" name="mobile" id="mobile" value="" autocomplete="off">
							<span class="error_icon"></span>
						</li>
						<li class="error_box" id="mobile_notice">
							<em></em>
						</li>
						<li class="security_code input_box">
							<span class="t_text">验证码</span>
							<input type="text" class="code_input" name="captcha" maxlength="6" id="yzm_m" value="">
							<span class="error_icon"></span>
							<img src="{:captcha_src()}" id="mobilelogincaptcha" onclick="javascript:$(this).attr('src', '{:captcha_src()}'+'?'+Math.random());" alt="captcha" style="vertical-align: middle;cursor: pointer;">
						</li>
						<li class="error_box" id="yzm_notice_m">
							<em></em>
						</li>
						<li class="input_box" style="width: 480px;float:left;"> 
							<span class="t_text" id="extend_fieldi">短信验证码</span>
							<input name="extend_field" id="dxyzm_m" type="text" style="width: 145px;" maxlength="6" />
							<span class="error_icon"></span>
						</li>
						<li class="input_box" style="width: 93px;float:left;border-left:none;cursor:pointer;background: #f4f4f4;">
							<input type="button" onclick="return getverifycode_login(document.getElementById('mobile').value,document.getElementById('yzm_m').value)" value="获取验证码" id="zphone" style="width:100%;padding:0;margin:0;height:40px;background:none;cursor:pointer;">
						</li>
						<li class="error_box" id="dxyzm_notice_m" style="clear:both;"> <em></em> </li>
						<li class="lizi_law"> 
							<a class="forget_psd" href="{:url('register')}{$lang}">短信找回密码?</a> 
							<label><input type="checkbox" value="1" name="remember" id="remember" class="remember-me">请保存我这次的登录信息。</label>
						</li>
						<li class="error_box">
							<em></em>
						</li>
						<li class="go2register">
							<input type="hidden" name="login_type" value="0" id="login_type" />
							<input type="hidden" name="act" value="act_sms_login" />
							<input type="hidden" name="back_act" value="./index.php" />
							<input type="Submit" name="Submit" class="btn submit_btn" value="登 录" disabled=""></li>
					</ul>
					<div class="other-form">
						<div class="other-login-tit">使用第三方帐号登录</div>
						<div class="other-login">
							<a class="qq" href="user.php?act=oath&type=qq"></a>
							<a class="sina" href="user.php?act=oath&type=weibo"></a>
							<a class="alipay" href="user.php?act=oath&type=alipay"></a>
							<a class="weixin" href="user.php?act=oath&type=weixin"></a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(function() {
		$(".input_box").click(function() {
			$(this).find(".t_text").hide();
			$(this).find("input").focus();
		})

		$(".input_box").focusin(function() {
			$(this).find(".t_text").hide();
		})

		$(".input_box").focusout(function() {
			if ($(this).find("input").val() == "") {
				$(this).find(".t_text").show();
			}
		})

		$(".card_login").click(function() {
			$(".user_putong_card").html('会员卡号');
			$(".pass_putong_card").html('会员卡密码');

			$(".usertoputong").hide();
			$(".usertocard").show();
			$("#login_cat").html('会员卡用户登录');
			$("#login_type").val('3');
		})

		$(".putong_login").click(function() {
			$(".user_putong_card").html('用户名');
			$(".pass_putong_card").html('密码');

			$(".usertoputong").show();
			$(".usertocard").hide();
			$("#login_cat").html('会员登录');
			$("#login_type").val('0');
		})

		function loginSubmit(object){
			alert("OK")
		// 	var username = $.trim($('#username').val());
		// 	var password = $.trim($('#password').val());
		// 	var captcha = $.trim($('#captcha').val());
		// 	if(username == ''){
		// 		show_error('邮箱/用户名/手机号不能为空!');
		// 		return false;
		// 	}
		// 	if(password == ''){
		// 		show_error('密码不能为空!');
		// 		return false;
		// 	}
		// 	if(captcha == ''){
		// 		show_error('验证码不能为空!');
		// 		return false;
		// 	}
		// 	var field = $(object).serializeArray();
		// 	$.ajax({
		// 		type : 'post',
		// 		url : '{:url("login")}',
		// 		data : field,		
		// 		dataType : 'json',
		// 		success : function(res){
		// 			if(res['code']) {
		// 				show_error(res['msg']);
		// 				verify();
		// 			}
		// 			else{
		// 				show_error(res['msg']);
		// 				window.location.href=res['data']['url'];
		// 			}
					
		// 		}
		// 	});
			return false;
		}

		$(".login_user").click(function() {
			$(".login_user").addClass('on');
			$(".login_mobile").removeClass('on');
			$(".reg_cont1").show();
			$(".reg_cont2").hide();
			$('#userlogincaptcha').attr('src','{:captcha_src()}'+'?'+Math.random());
		});
		
		$(".login_mobile").click(function() {
			$(".login_mobile").addClass('on');
			$(".login_user").removeClass('on');
			$(".reg_cont1").hide();
			$(".reg_cont2").show();
			$('#mobilelogincaptcha').attr('src','{:captcha_src()}'+'?'+Math.random());
		});
		
	})
</script>