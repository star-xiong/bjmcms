<div class="breadcrumb">
	<div class="screen container">
		<span>筛选：</span>
		<a class="active" href="#">1200px</a>
		<a href="#">其他尺寸</a>
	</div>
	
</div>

<div class="box-style container">
	<dl class="column-style">
	
	
	{volist name="menus" id="menu" offset="0" length="1"}
		{if $menu.status == 1 && ($menu.nav == 1 || $menu.nav == 3)}
			<dt>{$menu.title}</dt>	
			{if !empty($menu[$menu.id])}
				<dd>
					<ul>
						{foreach $menu[$menu.id] as $k=>$nav }
							{if $member.id}
								<li><a href="{:url($nav.path, 'id='.$nav.id)}{$lang}">{$nav.title}</a></li>
							{else}
								<li><a href="javascript:;" onclick="login();">{$nav.title}</a></li>
							{/if}
						{/foreach}
					</ul>
				</dd>
			{/if}
		{/if}
	{/volist}
	</dl>
	<dl class="layout-style">
		{volist name="menus" id="menu" offset="1" length="1"}
			{if $menu.status == 1 && ($menu.nav == 1 || $menu.nav == 3)}
				<dt>{$menu.title}</dt>	
				{if !empty($menu[$menu.id])}
					<dd>
						<ul>
							{foreach $menu[$menu.id] as $k=>$nav }
								{if $member.id}
									<li><a href="{:url($nav.path, 'id='.$nav.id)}{$lang}">{$nav.title}</a></li>
								{else}
									<li><a href="javascript:;" onclick="login();">{$nav.title}</a></li>
								{/if}
							{/foreach}
						</ul>
					</dd>
				{/if}
			{/if}
		{/volist}
	</dl>
</div>
