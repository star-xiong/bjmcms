<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class SiteValidate extends AbstractValidate
{
	protected $rule = [
		'name|站点名称'         => 'require',
		'mark|站点标记'       => 'require|unique:site',
	];
}