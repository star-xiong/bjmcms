<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class TempleteValidate extends AbstractValidate
{
	protected $rule = [
		'title|模板标题'   	=> 'require',
		'position_id|模板位置'   	=> 'require',
		//'url|链接地址'       	=> 'require',
	];
}