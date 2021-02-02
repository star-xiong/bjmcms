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

class Cart extends Common
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
	* 添加商品到购物车	
	*/
	public function add() {
				
		$params = $this->request->post();
		$rule = [
			'number|商品数量' => 'require|number',
			'goods_id|商品id'  =>  'require|number',
			'goods_price|商品售价'	=> 'float',
			'spec_attr_type|商品规格id'  =>  'array',
		];
		$msg = [
				'number.require' => 'number require',
				'number.number' => 'number isnumber',
				'goods_id.require'  => lang('goods_id require'),
				'spec_attr_type.require'  => lang('goods_attr require'),
			];
			
		$validate = new Validate($rule,$msg);
		if (!$validate->check($params)) {
			return json(['code' => 1, 'msg' => $validate->getError()]);
		}
		
		//获取商品属性(价格及库存)
		$goods = $this->getSelectedAttributes($params);
		
		//商品属性是否存在
		if(empty($params['spec_attr_type'])){
			//商品相册
			$goods['gallery_list'] = model('GoodsGalleryModel')->getListByGoodsid($goods['goods_id']);
			
			//商品属性
			$goods['attributes'] = model('AttributeModel')->where('type_id', $goods['type_id'])->order('sort')->All();
			$goods['attr_value_list'] = model('GoodsAttributeModel')->getListByGoodsid($goods['goods_id']);
			
			//商品库存
			$goods['stock_list'] = model('GoodsStockModel')->getListByGoodsid($goods['goods_id']);
			
			if($goods['attr_value_list']){
				return json(['code' =>3, 'msg' => "弹出商品属性框", 'data'=>$goods]);
			}
		}
		
		
		
		
		//检查库存
		if($params['number'] > $goods["goods_number"]){
			return json(['code' =>2, 'msg' => "库存不足，请调整购物数量！"]);
		}
		
		//获取商品信息
		// $goods_info = model("GoodsModel")->field('title as goods_name, pic as goods_img, market_price')
		// 								->get($goods["goods_id"])
		// 								->toArray();
		
		// $goods = array_merge($goods, $goods_info);
		
		$goods["goods_number"] = $params['number'];
		$goods["siteid"] = $this->current_site->id;
		$goods["user_id"] = $this->userinfo['id'];
		
		//检查SKU在购物车中是否存在
		$sku = model("CartModel")->where('siteid',$goods["siteid"])
								->where('user_id',$goods["user_id"])
								->where('goods_id',$goods["goods_id"])
								->where('stock_id',$goods["stock_id"])
								->find();
		if($sku){
			$flag = $sku->updateBy($sku['id'],['goods_attr'=>$goods['goods_attr'],'goods_attr_id'=>$goods['goods_attr_id'],'goods_number'=>$goods["goods_number"]+$sku['goods_number']]);
		}
		else{
			$flag = model("CartModel")->store($goods);
		}
		
		if($flag){
			$data = model('CartModel')->myCart($this->userinfo['id'], $this->current_site->id);
			$data['goods_id'] = $goods["goods_id"];
			return json(['code' =>0, 'msg' => "成功加入购物车！", 'data'=>$data]);
		}
		else{
			return json(['code' => 3, 'msg' => "出错了，再试试吧！"]);
		}
	}
	
	public function flow() {
		$this->view->engine->layout(false);
		$data = model('CartModel')->myCart($this->userinfo['id'], $this->current_site->id);
		
		if(Request::isAjax()){
			return json(['code' => 0, 'data' => $data]);
		}
		else{
			$this->assign(['data' => $data]);
			return $this->fetch('/flow');
		}
		
	}
	
	public function checkout() {
		$params = $this->request->post();
		if(empty($params['sel_cartgoods'])){
			return json(['code' =>1, 'msg'=>'您还没有选择商品哦！', 'data'=>['number'=>0,'amount'=>0]]);
		}
		$where = 'id IN ('.implode(',', $params['sel_cartgoods']).')';
		$data = model('CartModel')->myCart($this->userinfo['id'], $this->current_site->id, $where);
		if($params['do'] == 'sel'){
			if ($data['number']){
				return json(['code' =>0, 'msg'=>'', 'data'=>$data]);
			}
			else{
				return json(['code' =>1, 'msg'=>'您还没有选择商品哦！']);
			}
		}
		else{
			//收件地址
			$data['userinfo'] = model("MemberModel")->get($this->userinfo['id']);
			$data['userinfo']['password'] = '';
			
			$this->assign(['data' => $data]);
			$this->view->engine->layout(false);
			return $this->fetch('/flow_checkout');
		}
		
	}
	
	public function payinfo($id) {		
		$this->view->engine->layout(false);
		$order = model('OrderInfoModel')->getDetail($id, $this->current_site->id);
		$this->assign(['order' => $order]);
		return $this->fetch('/flow_payinfo');
	}
	
	public function update() {
				
		$params = $this->request->post();
		$id = intval($params['id']);
		
		if($id){
			$item = model("CartModel")->get($id);
			if(count($item) == 0){
				$code = 0;
				$msg = "购物车中没有此商品！";
				return json(['code'=>$code,'msg'=>$msg,'data'=>$data]);
			}
			
			//是否存SKU
			if($item['stock_id']){
				$stock = model("GoodsStockModel")->where('goods_stock_id',$item['stock_id'])->find();
			}
			else{
				$stock = model("GoodsModel")->get($item['goods_id']);
			}
			
			if($params['num'] == -1 || $params['num'] == +1){
				$number = $item['goods_number'] + $params['num'];
			}
			else{
				$number = intval($params['num']);
			}
			
			
			$data = [];
			$data['id']	= $id;
			
			if($number <= 0 ){
				$item->delete($id);
				$code = 1;
				$msg = "商品已从购物车中移除！";
			}
			else{
				
				if($number > $stock['goods_number']){
					$item['goods_number'] = $stock['goods_number'];
					$item->save();
					$msg = "购买数量超过库存，最多能购买".$stock['goods_number'];
					$data['goods_number'] = $stock['goods_number'];
				}
				else{
					$item['goods_number'] = $number;
					$item->save();
					$msg = "更新购物车成功！";
					$data['goods_number'] = $number;
				}
				$code = 2;
				$data['money'] = $item['goods_price']*$data['goods_number'];				
			}
			
			$mycart = model('CartModel')->myCart($this->userinfo['id'], $this->current_site->id);
			$data['amount'] = $mycart['amount'];
			$data['total'] = $mycart['total'];
			return json(['code'=>$code,'msg'=>$msg,'data'=>$data]);
		}
	}
	
	public function delete($id) {
		$goods = model("CartModel")->get($id);
		
		if($goods['user_id'] == $this->userinfo['id']){
			$goods->delete();
			return json(['code'=>0,'msg'=>'已删除','data'=>$id]);
		}
		else{
			return json(['code'=>1,'msg'=>'不存在']);
		}
		
	}
	
	public function clear() {
		
		model("CartModel")->where('user_id',$this->userinfo['id'])->delete();
		
		return json(['code'=>0,'msg'=>'购物车已清空']);
		
	}
}
