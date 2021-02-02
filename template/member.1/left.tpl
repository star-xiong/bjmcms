<div class="personal-card">
	<div class="user-avatar"><img src="{if $user['avatar']}{$user['avatar']}{else}{$web_images}/usernopic.png{/if}"></div>
	<div class="user-name">{$user['name']}</div>
	<div class="user-btn_area">
		<a href="{:url('logout')}{$lang}">退出</a>
	</div>
</div>
<div class="personal-menu">
	<ul>
		<li {if request()->action() == "index"}class="active"{/if}><i></i><a href="{:url('ucenter')}{$lang}">修改资料</a></li>
		<li {if request()->action() == "resetpasswd"}class="active"{/if}><i></i><a href="{:url('resetpasswd')}{$lang}">更改密码</a></li>
		<li {if request()->action() == "myfavorite"}class="active"{/if}><i></i><a href="{:url('myfavorite')}{$lang}">我的收藏</a></li>
		<li><i></i><a href="#" target="_bland">帮助中心</a></li>
	</ul>
</div>