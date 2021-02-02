<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class CartModel extends BaseModel
{
    protected $name = 'cart';
	public function goods()
	{
		return $this->belongsTo('GoodsModel', 'goods_id');
	}
	
	public function myCart($userid,$siteid,$where='') {
		$data = [];
		$cartModel = $this->field('*, goods_price*goods_number as money')
										->where('siteid', $siteid)
										->where('user_id', $userid);
		if(!empty($where)){
			$cartModel = $cartModel->where($where);
		}										
		$data['items'] = $cartModel->select()->toArray();
		
		$data['amount'] = array_sum(array_column($data['items'], 'money'));
		$data['number'] = array_sum(array_column($data['items'], 'goods_number'));
		return $data;
	}
}