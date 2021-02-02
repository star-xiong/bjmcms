<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class GoodsTagModel extends BaseModel
{
    protected $name = 'goods_tags';
	
	public function goods()
	{		
		return $this->belongsTo('GoodsModel','goods_id');
	}
}