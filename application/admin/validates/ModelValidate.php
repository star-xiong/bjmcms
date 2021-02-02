<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class ModelValidate extends AbstractValidate
{
	protected $rule = [
		'title|模型名称'         => 'require',
		'table_name|表名'       => 'require|min:3|max:20|alphaDash|unique:model',
		//'description|描叙'       => 'require',
	];
}