<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{$seo_title}</title>
<meta name="keywords" content="{$seo_keywords}">
<meta name="description" content="{$seo_content}">
<link rel="stylesheet" type="text/css" href="{$web_css}/style.css">
<script type="text/javascript" src="{$web_js}/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$web_js}/layer/layer.js"></script>
<script type="text/javascript" src="{$web_js}/common.js"></script>
</head>

<body>
<!--header Start-->
<div id="header">
	<div class="header">
		<div class="container">
			{if $current_site.logo}
				<div class="logo">
					<a href="{:buildurl('/')}"><img src="{$current_site.logo}"></a>
				</div>
			{/if}
			<div class="nav">
				<ul>
					<li><a href="#">营销推广</a></li>
					<li><a href="#">品牌创意</a></li>
					<li><a href="#">手机站</a></li>
				</ul>
			</div>
			<div class="head-rt">
				<div class="hsearch">
					<form class="j-form" onsubmit="return searchForm(this);" action="{:buildurl('search')}" method="GET">
						<input class="j-input" type="text" name="keywords" placeholder="请输入关键词">
						<button type="button" class="btn"></button>
					</form>
				</div>
				{if $member.id}
					<a class="hlogin" href="{:buildurl('logout')}">登出</a>
				{else}
					<a class="hlogin" href="javascript:;" onclick="login();">登录</a>
				{/if}
			</div>
		</div>
	</div>
</div>
<!--header end-->