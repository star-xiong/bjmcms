<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class ContentValidate extends AbstractValidate
{
	protected $rule = [
		'__token__'			=> 'token',
		'title|标题'			=> 'require',
		'sort|排序'			=> 'number',
		//'mid|模型ID'       => 'require|min:3|max:20|alpha|unique:content',
		//'description|描叙'       => 'require',
	];
	
	protected $msg = [
            '__token__'  => '请不要重复提交该页面！',
    ];
}