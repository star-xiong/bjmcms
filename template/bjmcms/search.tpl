<div class="breadcrumb">
	<div class="location container">
		<a href="{:url('/')}{$lang}">首页</a>&nbsp;>&nbsp;搜索
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