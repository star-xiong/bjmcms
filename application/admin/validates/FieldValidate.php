<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class FieldValidate extends AbstractValidate
{
	protected $rule = [
		'class|字段类型'			=> 'require|alpha',
		'mid|模型ID'			=> 'require|number',
		'title|字段名称'         => 'require',
		'field|字段名'       => 'require|min:3|max:30|alpha',
		'maxlength|长度'       => 'require|number',
	];
}