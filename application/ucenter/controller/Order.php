<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\ucenter\controller;

use app\common\controller\Common;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Request;
use app\traits\Auth;
use app\traits\GoodsTrait;
use think\Validate;

class Order extends Common
{
	use Auth, GoodsTrait;
	protected $middleware = ['checkLogin'];
	public $userinfo;
	
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
		$this->userinfo = session(config('permissions.user'));
		$this->view->config('view_path', './template/member/');
	} 
		
	public function index(){
		//$params = $this->request->param();
		$params['siteid'] = $this->current_site->id;
		$params['user_id'] = $this->userinfo['id'];
		$orders = model('OrderInfoModel')->getList($params,6);
		$this->assign(['orders' => $orders]);
		return $this->fetch('/user/order');
	}
	
	/**
	 * 订单详情
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function detail($id){
		$order_info = model('OrderInfoModel')->getDetail($id, $this->current_site->id,$this->userinfo['id']);
		
		$this->assign(['order'  => $order_info]);
		return $this->fetch('/user/order_detail');
	}
	
	/**
	 * 取消订单
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function cancel($id){
		$order = model('OrderInfoModel')->getDetail($id, $this->current_site->id, $this->userinfo['id']);
		if($order.pay_staus == 0 && $order.shipping_status == 0){
			//未付款,且未发货 取消订单
			model('OrderInfoModel')->updateBy($id,['order_status'=>3]);
			$data = [];
			$data['action_user'] = $this->userinfo['name'];
			$data['order_id'] = $id;
			$data['action_note'] = '用户取消订单';
			$data['order_status'] = 3;
			$data['shipping_status'] = $order['shipping_status'];
			$data['pay_status'] = $order['pay_status'];
			$data['log_time'] = date('Y-m-d H:i:s');
			
			model('OrderActionModel')->store($data);
			$data = ['code'=> 0, 'msg'=>'订单成功取消'];
			return json($data);
		}
		
		if($order.pay_staus == 2){
			//已付款,取消订单,并退款
			$back_order = $order->toArray();
			$back_order['status'] = 0;
			$back_order['update_time'] = time();
			//如果有发货，则需退货
			if($order.shipping.status == 1) {
				$goods = $order->goods;
			}
			
			$data = ['code'=> 0, 'msg'=>'订单成功取消'];
			return json($data);
		}
		
		$data = ['code'=> 1, 'msg'=>'不符合取消订单规则'];
		return json($data);
	}
	
	private function getPayment($pay_id) {
		$payment = [
				1=>'余额支付',
				2=>'货到付款',
				3=>'支付宝',
				4=>'微信支付'
			];
		return $payment[$pay_id];
	}
	
	private function how_oos($oos) {
		$how_oos = [
			'等待所有商品备齐后再发',
			'取消订单',
			'与店主协商'
			];
		return $how_oos[$oos];
	}
	
	private function getShipping($shipping_id) {
		$getshipping = [
			1=>'上门取货',
			2=>'运费到付',
			3=>'顺丰速运'
			];
		return $getshipping[$shipping_id];
	}
}
