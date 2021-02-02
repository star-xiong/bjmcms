<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class DiyformValidate extends AbstractValidate
{
	protected $rule = [
		'name|名称'         => 'require',
		//'mid|模型ID'       => 'require|min:3|max:20|alpha|unique:content',
		//'description|描叙'       => 'require',
	];
	
	protected $msg = [
            '__token__'  => '请不要重复提交该页面！',
    ];
}