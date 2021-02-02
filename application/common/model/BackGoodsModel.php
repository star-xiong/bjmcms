<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class BackGoodsModel extends BaseModel
{
    protected $name = 'back_goods';
	
	public function backorder()
	{
		return $this->belongsTo('BackOrderModel', 'back_id');
	}
	
}