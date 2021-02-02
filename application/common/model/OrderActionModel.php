<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class OrderActionModel extends BaseModel
{
    protected $name = 'order_action';
	public static $order_status = ['未确认', '已确认', '合并', '取消', '无效', '退货'];
	public static $shipping_status = ['未发货', '已发货', '确认收货', '备货中', '已发货(部分商品)'];
	public static $pay_status = ['未付款', '付款中', '已付款'];
	
	public function getList($order_id) {
		$list = $this->where('order_id',$order_id)->order('id desc')->All()->toArray();

		foreach($list as $key=>$value){
			$list[$key]['order_status'] = self::$order_status[$value['order_status']];
			$list[$key]['shipping_status'] = self::$shipping_status[$value['shipping_status']];
			$list[$key]['pay_status'] = self::$pay_status[$value['pay_status']];
		}
		return $list;
	}
	
}