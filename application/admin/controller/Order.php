<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// | 会员等级
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\OrderRequest;

class Order extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Rank list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$params = $this->request->param();
		$this->checkParams($params);
		$params['siteid'] = session('site');
		$orders = model('OrderInfoModel')->getList($params,30);

		return json($this->layuiPaginator($orders));
	}
	
	/**
	 * 订单详情
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function detail(){
		$id = $this->request->param('id/d');
		$order_info = model('OrderInfoModel')->getDetail($id, session('site'));
		
		$this->assign([
		    'order'  => $order_info
		]);
		return $this->fetch();
	}
	
	/**
	 * 订单商品
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function getGoods(){
		$id = $this->request->param('id/d');
		$goods = model('OrderGoodsModel')->where('order_id',$id)->All();
		return json($this->layuiPaginator($goods));
	}
	
	/**
	 * 订单操作记录
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function getLog(){
		$id = $this->request->param('id/d');
		$log = model('OrderActionModel')->getList($id);
		return json($this->layuiPaginator($log));
	}
	
	/**
	 * 订单操作
	 * @op op_confirm-确认; op_pay-付款; op_unpay-设为未付款; op_prepare-配货; op_split-生成发货单; op_unship-未发货; op_receive-已收货; op_cancel-取消; op_invalid-无效
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function action()
	{
		$id = $this->request->post('id/d');
		$order = model('OrderInfoModel')->get($id)->toArray();
		
		if(empty($order)){
			$data = ['code'=> 1, 'msg'=>'无效操作', 'data'=> $op];
			return json($data);
		}
		
		$action_note =  $this->request->post('action_note');
		$op = $this->request->param('op');
		$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> $op];
		
		
		$status = [
			'confirm'=>'order_status|1',				//确认
			'cancel'=>'order_status|3',					//取消
			'invalid'=>'order_status|4',				//无效
			
			'pay'=>'pay_status|2',						//付款
			'unpay'=>'pay_status|0',					//设为未付款
			
			'unship'=>'shipping_status|0',				//未发货
			'delivery'=>'shipping_status|1',			//已发货
			'receive'=>'shipping_status|2',				//已收货
			'prepare'=>'shipping_status|3',				//配货			
			];
		if(empty($op)){
			$data = ['code'=> 2, 'msg'=>'无效操作', 'data'=> $op];
			return json($data);
		}
		
		if(isset($status[$op])){
			$orderStatus = explode('|',$status[$op]);
			model('OrderInfoModel')->updateBy($id,[$orderStatus[0]=>$orderStatus[1]]);
			
			$loginUser = $this->getLoginUser();
			$data['action_user'] = $loginUser['name'];
			$data['order_id'] = $id;
			$data['action_note'] = $action_note;
			$data['order_status'] = $order['order_status'];
			$data['shipping_status'] = $order['shipping_status'];
			$data['pay_status'] = $order['pay_status'];
			$data['log_time'] = date('Y-m-d H:i:s');
			
			$data[$orderStatus[0]] = $orderStatus[1];
			
			model('OrderActionModel')->store($data);
			$data = ['code'=> 0, 'msg'=>'操作成功', 'data'=> $orderStatus];
		}

		return json($data);
	}
	
	public function delete()
	{
		$orderId = $this->request->post('id/d');
		
		if (!$orderId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		if (model('MemberRankModel')->deleteBy($orderId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}