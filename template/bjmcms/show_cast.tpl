<div class="breadcrumb">
	<div class="location container">
		<a href="{:url('/')}{$lang}">首页</a>
		{if $cType.parents}
			{foreach $cType.parents as $key=>$cate}
				{if $cate.title}
					&nbsp;>&nbsp;<a href="{:url($cate.path, 'id='.$cate.id)}{$lang}">{$cate.title}</a>
				{/if}
			{/foreach}
		{/if}
		{if $cateInfo.id}
			&nbsp;>&nbsp;<a href="{:url($cateInfo.path, 'id='.$cateInfo.id)}{$lang}">{$cateInfo.title}</a>
		{/if}
	</div>
</div>

<div class="show-tabtit">
	<div class="container">
		<ul>
			<li class="active"><a href="#">全套效果</a></li>
			<li><a href="{$info.more.bigpic}" target="_blank">效果来源</a></li>
			<li><a href="{$info.more.psd}" target="_blank">PSD下载</a></li>
			<li><a href="{$info.more.code}" target="_blank">静态页面</a></li>
			
			<li>
				<a href="#">应用链接</a>
				<div class="subnav">
					{$info.more.links|raw}
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="all-effect">
	<div class="container">
		<div class="all-effect-title">{$info.title}</div>
		<div class="all-effect_body">
			{$info.more.content|raw}
		</div>
		<div class="all-effect-other">
			<div class="name">其他页面效果推荐</div>
			<div class="all-effect-other_list">
				<div class="title">相<br/>关<br/>产<br/>品</div>
				<ul>
				{volist name="info.related_list" id="related"}
				
					<li>
						<a href="{$related.id|url='show','id='.###}{$lang}">
							<div class="pic"><img src="{$related.pic}"></div>
							<div class="stitle">{$related.title}</div>
							<div class="sbot"><span>查看详情</span><span>{$related.category.title}</span></div>
						</a>
					</li>
				{/volist}
					
				</ul>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$(function(){
	var navTop = $(".show-tabtit").offset().top;
	$(window).scroll(function(){
		if($(window).scrollTop()>navTop){
			$(".show-tabtit").addClass("fixed");
		}
		else{
			$(".show-tabtit").removeClass("fixed");
		}
	});
});
</script>