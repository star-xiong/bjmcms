<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class MemberFavoriteModel extends BaseModel
{
	protected $name = 'member_favorites';

	public function content()
	{
		return $this->belongsTo('ContentModel','content_id');
	}
}