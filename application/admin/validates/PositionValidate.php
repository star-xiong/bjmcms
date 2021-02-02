<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class PositionValidate extends AbstractValidate
{
	protected $rule = [
		'title|标题'         => 'require',
		'param_name|变量名'         => 'require|unique:templete_positions',
		'type|调用数据类型'         => 'require',
	];
}