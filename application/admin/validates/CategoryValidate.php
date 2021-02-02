<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class CategoryValidate extends AbstractValidate
{
	protected $rule = [
		'title|栏目名称'         => 'require',
		'pid|上级栏目'       => 'require',
		'mid|所属模型'       => 'require',
	];
}