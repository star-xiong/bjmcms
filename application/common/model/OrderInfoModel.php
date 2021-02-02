<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class OrderInfoModel extends BaseModel
{
    protected $name = 'order_info';
	public static $order_status = ['未确认', '已确认', '合并', '已取消', '无效', '退货'];
	public static $shipping_status = ['未发货', '已发货', '确认收货', '备货中', '已发货(部分商品)'];
	public static $pay_status = ['未付款', '付款中', '已付款'];
	
	public function goods()
	{
		return $this->hasMany('OrderGoodsModel', 'order_id');
	}
	
	/**
	 * order List
	 *
	 * @time at 2019年03月25日
	 * @param $params
	 * @return \think\Paginator
	 * order_status 订单的状态：0未确认  1已确认 2合并 3 已取消  4无效  5退货
	 * shipping_status 配送状态：0未发货  1已发货  2确认收货  3 备货中  4 已发货(部分商品)
	 * pay_status 款项的状态：0未付款  1付款中  2已付款
	 */
	public function getList($params, $limit = self::LIMIT)
	{
		
		if (!count($params)) {
			return $this->paginate($limit);
	    }
	
		$order = $this;
		
		if (isset($params['order_status'])) {
			$order = $order->where('order_status', $params['order_status']);
		}
		
		if (isset($params['shipping_status'])) {
			$order = $order->where('shipping_status', $params['shipping_status']);
		}
		
		if (isset($params['pay_status'])) {
			$order = $order->where('pay_status', $params['pay_status']);
		}
		
		if (isset($params['siteid'])) {
			$order = $order->where('siteid', $params['siteid']);
		}
		
		if (isset($params['order_sn'])) {
			$order = $order->where('order_sn', 'like', '%'.$params['order_sn'].'%');
		}
		
		$list = $order->order("id", "desc")
					->paginate($limit, false, ['query' => request()->param()]);
		
		if(!$list->isEmpty()){
			foreach($list as $key=>$value){
				$list[$key]['order_status_name'] = self::$order_status[$value['order_status']];
				$list[$key]['shipping_status_name'] = self::$shipping_status[$value['shipping_status']];
				$list[$key]['pay_status_name'] = self::$pay_status[$value['pay_status']];
			}
		}
		return $list;
	}
	
	/**
	 * 订单详情
	 *
	 * @time at 2019年03月25日
	 * @param $params
	 * @return \think\Paginator
	 * order_status 订单的状态：0未确认  1已确认 2合并 3 已取消  4无效  5退货
	 * shipping_status 配送状态：0未发货  1已发货  2确认收货  3 备货中  4 已发货(部分商品)
	 * pay_status 款项的状态：0未付款  1付款中  2已付款
	 */
	public function getDetail($id, $siteid, $uid=0){
		$order = $this->where('id',$id)
					->where('siteid',$siteid);
		if($uid) {
			$order = $order->where('user_id',$uid);
		}
		$order = $order->find();
		if($order->isEmpty()){
			return null;
		}
		
		//订单的状态
		$order->order_status_name = self::$order_status[$order['order_status']];
		//配送状态
		$order->shipping_status_name = self::$shipping_status[$order['shipping_status']];
		//款项的状态
		$order->pay_status_name = self::$pay_status[$order['pay_status']];

		$regions = model('RegionModel')->where('region_id','IN',[$order[province],$order[city],$order[district]])
									->column('region_name','region_id');
		$order->addressFull = '[中国 '.$regions[$order['province']].' '.$regions[$order['city']].' '.$regions[$order['district']].'] '.$order['address'];
		return $order;
	}
	
	public function payment()
	{
		return $this->belongsTo('PaymentModel', 'pay_id');
	}
}