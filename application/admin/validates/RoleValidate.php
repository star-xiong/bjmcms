<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class RoleValidate extends AbstractValidate
{
	protected  $rule = [
		'name|角色名'   => 'require|min:3|max:15|chs|unique:roles',
	];
}