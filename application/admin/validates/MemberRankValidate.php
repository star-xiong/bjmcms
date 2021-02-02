<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

class MemberRankValidate extends AbstractValidate
{
	protected $rule = [
		'rank_name|会员等级'         => 'require',
	];
}