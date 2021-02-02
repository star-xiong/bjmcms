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

<div class="main-container container">
	<div class="list-database">
		<ul>
			{volist name="data" id="info"}
				<li>
					<a href="{$info.id|url='show','id='.###}{$lang}" title="{$info.title}">
						<div class="pic">
							<img src="{$info.pic}">
							<div class="mask">
								<div class="maskbox">
									<div class="icon"></div>
									<div class="name">查看大图</div>
								</div>
							</div>
						</div>
						<div class="title">{$info.etitle}-{$info.title}</div>
					</a>
				</li>
			{/volist}
		</ul>
	</div>
</div>