<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\shop\controller;

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
	} 
		
	
	/*
	* 添加新订单	
	*/
	public function add() {
		$params = $this->request->post();
		$rule = [
			'shipping|送货方式' => 'require|number',
			'payment|支付方式'  =>  'require|number',
			//'postscript|订单附言'  =>  'print',
			'how_oos|缺货处理'	=> 'number',
			'sel_cartgoods' => 'require|array',
		];
		$msg = [
			'shipping.require' => lang('送货方式不存在'),
			'shipping.number' => lang('送货方式只能为数字'),
			'payment.require' => lang('支付方式不存在'),
			'payment.number' => lang('支付方式只能为数字'),
			//'postscript.print'  => lang('订单附言有非法字符'),
			'how_oos.number'  => lang('缺货处理只能为数字'),
			'sel_cartgoods.require'  => lang('您还没有选择商品哦！'),
			'sel_cartgoods.array'  => lang('您还没有选择商品哦！'),
		];
   		
		$validate = new Validate($rule,$msg);
		if (!$validate->check($params)) {
			return json(['code' => 1, 'msg' => $validate->getError()]);
		}
		
		$order = [];
		$order_goods = [];
		
		//生成新订单号
		$order['order_sn'] =get_order_sn();
		
		$order['shipping_id'] = $params['shipping'];
		$order['shipping_name'] = $this->getShipping($order['shipping_id']);
		
		
		$order['pay_id'] = $params['payment'];
		$order['pay_name'] = $this->getPayment($order['pay_id']);
		
		$order['postscript'] = checkStrHtml($params['postscript']);
		
		$order['how_oos'] = $this->how_oos($params['how_oos']);
		$order['siteid'] = $this->current_site->id;
		$order['user_id'] = $this->userinfo['id'];
		$order['add_time'] = date("Y-m-d H:i:s");
		//购物车
		$where = 'id IN ('.implode(',', $params['sel_cartgoods']).')';
		$goods_list = model('CartModel')->myCart($this->userinfo['id'], $this->current_site->id,$where);
		//print_r($goods_list);
		$order['goods_amount'] = $goods_list['amount'];		//商品总金额
		
		//收货信息
		$userinfo = model("MemberModel")->get($this->userinfo['id']);
		//$address = $userinfo->defaultAddress;
		$add_key = ['consignee','country','province','city','district','address','zipcode','tel','mobile','email','best_time','sign_building'];
		foreach($add_key as $value){
			$order[$value] = $userinfo->defaultAddress->$value;
		}
				
		$order['money_paid'] = 0;			//已付款金额		
		$order['shipping_fee'] = 0;			//配送费用		
		$order['pay_fee'] = 0;				//支付手续费		
		$order['pack_fee'] = 0;				//包装费用		
		$order['card_fee'] = 0;				//卡片费用		
		$order['surplus'] = 0;				//使用余额
		$order['bonus'] = 0;				//使用红包		
		$order['discount'] = 0;				//折扣		
		$order['tax'] = 0;					//发票税额
		
		//订单总金额 = 商品总金额 - 折扣 + 发票税额 + 配送费用 + 支付费用
		$order['order_amount'] = $order['goods_amount'] - $order['discount'] + $order['tax'] + $order['shipping_fee'] + $order['pay_fee'];
		
		//应付款金额 = 订单总金额 - 已付款金额：{$order.money_paid} - 使用余额： {$order.surplus} - 使用红包： {$order.bonus}
		
		$order_id = model("OrderInfoModel")->store($order);
		
		foreach($goods_list['items'] as $key => $items){
			$order_goods[$key]['order_id'] = $order_id;
			$order_goods[$key]['goods_id'] = $items['goods_id'];
			$order_goods[$key]['goods_sn'] = $items['goods_sn'];
			$order_goods[$key]['stock_id'] = $items['stock_id'];
			$order_goods[$key]['goods_name'] = $items['goods_name'];
			$order_goods[$key]['goods_img'] = $items['goods_img'];
			$order_goods[$key]['market_price'] = $items['market_price'];
			$order_goods[$key]['goods_price'] = $items['goods_price'];
			$order_goods[$key]['goods_number'] = $items['goods_number'];
			$order_goods[$key]['goods_attr'] = $items['goods_attr'];
			$order_goods[$key]['goods_attr_id'] = $items['goods_attr_id'];
		}
		
		$flag = model("OrderGoodsModel")->saveAll($order_goods);
		
		if($flag){
			//清除购物车
			model("CartModel")->where('user_id',$this->userinfo['id'])->where($where)->delete();
			
			return json(['code' => 0, 'msg' => '订单添加成功', 'data'=>$order_id]);
		}
		return json(['code' => 1, 'msg' => '订单添加失败']);
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
