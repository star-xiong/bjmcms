<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class PriceValidate extends AbstractValidate
{
	protected $rule = [
		'filename|上传文件'         => 'require',
		'created_at|报价时间'         => 'require|date',
	];
}