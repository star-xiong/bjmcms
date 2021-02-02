<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------


namespace app\admin\validates;

class AttributeValidate extends AbstractValidate
{
	protected $rule = [
		'title|属性名称'   	=> 'require',
		'class|属性类型'    	=> 'require',
		'type_id|所属类型'  	=> 'require',
	];
}