<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class BrandValidate extends AbstractValidate
{
	protected $rule = [
		'brand_name|品牌名称'         => 'require',
	];
}