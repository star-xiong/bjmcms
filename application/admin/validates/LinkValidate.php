<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class LinkValidate extends AbstractValidate
{
	protected $rule = [
		'title|网站标题'         => 'require',
		'url|网站地址'       => 'require',
	];
}