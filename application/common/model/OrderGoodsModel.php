<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class OrderGoodsModel extends BaseModel
{
    protected $name = 'order_goods';
	
	public function order()
	{
		return $this->belongsTo('OrderInfoModel', 'order_id');
	}
	
}