<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class ContentTagModel extends BaseModel
{
    protected $name = 'content_tags';
	
	public function content()
	{		
		return $this->belongsTo('ContentModel','content_id');
	}
}