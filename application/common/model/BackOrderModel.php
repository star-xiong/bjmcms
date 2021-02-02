<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class BackOrderModel extends BaseModel
{
    protected $name = 'back_order';
	
	public static $status = ['未审核', '拒绝', '同意', '已退款', '收到退货'];
	
	public function goods()
	{
		return $this->hasMany('BackGoodsModel', 'back_id');
	}
	
}