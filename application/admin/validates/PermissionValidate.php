<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class PermissionValidate extends AbstractValidate
{
	protected $rule = [
		'name|资源名称'         => 'require|min:2|max:10|unique:permissions',
		'controller|控制器名称' => 'require|min:2|max:50|alpha',
		//'action|方法名称'       => 'require|min:2|max:50|alpha',
	];
}