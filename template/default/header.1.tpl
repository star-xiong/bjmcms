<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{$seo_title}</title>
		<meta name="keywords" content="{$seo_keywords}">
		<meta name="description" content="{$seo_content}">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link href="{$web_css}/style.css" rel="stylesheet" type="text/css" />
		<link href="{$web_css}/index.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$web_js}/common.js"></script>
		<script type="text/javascript" src="{$web_js}/easydialog.min.js"></script>
		<script type="text/javascript" src="{$web_js}/index.js"></script>
		<script type="text/javascript" src="{$web_js}/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="{$web_js}/jquery.json.js"></script>
		<script type="text/javascript" src="{$web_js}/jquery-lazyload.js"></script>
		<script type="text/javascript" src="{$web_js}/transport_jquery.js"></script>
		<script type="text/javascript" src="{$web_js}/utils.js"></script>
		<script type="text/javascript" src="{$web_js}/jquery.superslide.js"></script>
		<script type="text/javascript" src="{$web_js}/lizi_common.js"></script>
	</head>
	<body>

		<div id="header" class="new_header">
			<div class="site-topbar">
				<div class="container">
					<div class="topbar-nav">{$current_site['notice']|raw}
						<a href="myship.php" class="snc-link snc-order">配送方式</a>
						<span class="sep">&nbsp;</span><a href="article_cat.php?id=16" class="snc-link snc-order">公司动态</a>
						<span class="sep">&nbsp;</span><a href="activity.php" class="snc-link snc-order">优惠活动</a>
						<span class="sep">&nbsp;</span><a href="package.php" class="snc-link snc-order">超值礼包</a>
					</div>
					<ul class="sn-quick-menu">
						<li class="sn-mytaobao menu-item j_MyTaobao">
							<div class="sn-menu">
								<a aria-haspopup="menu-2" tabindex="0" class="menu-hd" href="{:url('ucenter.index')}{$lang}" target="_top" rel="nofollow">我的信息<b></b></a>
								<div id="menu-2" class="menu-bd">
									<div class="menu-bd-panel" id="myTaobaoPanel">
										<a href="user.php?act=order_list" target="_top" rel="nofollow">已买到的宝贝</a>
										<a href="user.php?act=address_list" target="_top" rel="nofollow">我的地址管理</a>
										<a href="user.php?act=collection_list" target="_top" rel="nofollow">我收藏的宝贝</a>
									</div>
								</div>
							</div>
						</li>
					</ul>
					<div class="topbar-info J_userInfo" id="ECS_MEMBERZONE">
						{if $member.id}
						<a class="link" href="/logout.html" rel="nofollow">退出</a>
						{else}
						<a class="link" href="{:url(" login")}{$lang}" rel="nofollow">会员登录</a>
						<span class="sep">&nbsp;</span>
						<a class="link" href="{:url('register')}{$lang}" rel="nofollow">会员注册</a>
						{/if}
					</div>
				</div>
			</div>
			<script type="text/javascript">
				<!--
				function checkSearchForm() {
					if (document.getElementById('keyword').value) {
						return true;
					} else {
						alert("请输入搜索关键词！");
						return false;
					}
				}
				-->
			</script>
			<div class="logo-search">
				<a class="logodiv" href="./" title="">
					<div class="c-logo" style="background: url() left center no-repeat;"></div>
				</a>
				<div class="search-tab">
					<div class="search-form">
						<form action="search.php" method="get" id="searchForm" name="searchForm" onSubmit="return checkSearchForm()">
							<div class="so-input-box">
								<input type="text" name="keywords" id="keyword" value="" class="soinput" placeholder="请输入关键词" autocomplete="off" />
								<input type="hidden" value="k1" name="dataBi">
							</div>
							<input id="searchBtn" type="submit" class="sobtn sogoods" value="" />
							<div class="clear"></div>
						</form>
					</div>
					<div class="search-tags">
						<span>热搜榜：</span>
						<a href="search.php?keywords=T%E6%81%A4" rel="nofollow">T恤</a>
						<a href="search.php?keywords=%E8%A1%AC%E8%A1%AB" rel="nofollow">衬衫</a>
						<a href="search.php?keywords=%E8%BF%9E%E8%A1%A3%E8%A3%99" rel="nofollow">连衣裙</a>
						<a href="search.php?keywords=%E5%8C%85%E5%8C%85" rel="nofollow">包包</a>
						<a href="search.php?keywords=%E5%A5%97%E8%A3%85" rel="nofollow">套装</a>
					</div>
				</div>

				<div class="mobile_ewm">
					<a href="javascript:void(0);" class="btn-qrcode-mobile">
						<span>手机扫码购买</span>
						<div class="qrcode-popover">
							<em class="arrow"></em>
							<em class="arrow border"></em>
							<div class="title">
								<p>扫一扫手机购买</p>
							</div>
							<div class="img">
								<img src="{$web_images}/picture/erweima_mobile.php" alt="">
							</div>
						</div>
					</a>
				</div>


				<div class="topbar-cart" id="ECS_CARTINFO_TOP">
					<a class="cart-mini" href="flow.php">
						<i class="iconfont">&#xe601;</i>
						购物车
						<span class="mini-cart-num J_cartNum" id="hd_cartnum">(0)</span>
					</a>
					<div id="J_miniCartList" class="cart-menu">
						<p class="loading_top">购物车中还没有商品，赶紧选购吧！</p>

					</div>
					<script type="text/javascript">
						function deleteCartGoods(rec_id) {
							Ajax.call('delete_cart_goods.php', 'id=' + rec_id, deleteCartGoodsResponse_top, 'POST', 'JSON');
						}

						/**
						 * 接收返回的信息
						 */
						function deleteCartGoodsResponse_top(res) {
							if (res.error) {
								alert(res.err_msg);
							} else {
								$('.ECS_CARTINFO').html(res.content);
								$('.cart-panel-content').height($(window).height() - 90);
								$("#ECS_CARTINFO_TOP").html(res.content_top);
							}
						}
					</script>
				</div>
			</div>
			<div class="w-nav">
				<div class="t-nav">
					<div class="nav-categorys j-allCate">
						<div class="catetit">
							<h2><a href="javascript:;" rel="nofollow">商品分类<i class="c-icon"></i></a></h2>
						</div>
						
						<ul class="cate-item j-extendCate dis-n" style="height:auto;">
							{volist name="goods_cats" id="gc_1" key="i"}
							{volist name="$gc_1[$gc_1.id]" id="gc_2" key="ii"}
							<li>
								<div class="cateone cate1">
									<a href="category.php?id=1">{$gc_2.title}<i class="iconfont">&#xe600;</i></a>
								</div>
								<div class="catetwo catetwo1">
									<div class="topMap clearfix">
										<div class="subCat clearfix">
											{volist name="$gc_2[$gc_2.id]" id="gc_3" key="iii"}
											<dl>
												<dt><a href="{:url('category', 'id='.$gc_3.id)}{$lang}">{$gc_3.title}</a></dt>
												<dd class="goods-class">
													{volist name="$gc_3[$gc_3.id]" id="gc_4" key="iiii"}
														<a href="{:url('category', 'id='.$gc_4.id)}{$lang}" {if $gc_4.ishot == 1}class="hot_cat"{/if}><img src="{$gc_4.icon}" border="0" style="vertical-align:middle;">{$gc_4.title}</a>
													{/volist}
												</dd>
											</dl>
											{/volist}
											
										</div>
									</div>
								</div>
							</li>
							{/volist}
							{/volist}
							
							<li>
								<div class="cateone cate8">
									<a href="catalog.php">查看所有分类<i class="iconfont">&#xe600;</i></a>
								</div>
							</li>
						</ul>
					</div>
					<ul class="nav-items">
						
						<li>
							<a href="{:url('/')}{$lang}" rel="nofollow">首页</a>
						</li>
						{volist name="nav_pctop" id="pctop" key="i"}
							<li {if $pctop.id == $cType.id}class="active"{/if}>
								<a href="{if $pctop.jumpurl}{$pctop.jumpurl}{else}{:url($pctop.path, 'id='.$pctop.id)}{/if}{$lang}" rel="nofollow">{$pctop.title}</a>
							</li>
						{/volist}
					</ul>
				</div>
			</div>
		</div>
