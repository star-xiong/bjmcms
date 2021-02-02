<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class PriceModel extends BaseModel
{
    protected $name = 'prices';

	public function goods()
	{
		return $this->belongsTo('GoodsModel','goods_id');
	}
}