<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class ContentAttributeModel extends BaseModel
{
    protected $name = 'content_attributes';
	
	public function attribute()
	{		
		return $this->belongsTo('AttributeModel','attr_id');
	}
}