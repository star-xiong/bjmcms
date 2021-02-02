<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\ucenter\controller;

use app\common\controller\Common;
use app\traits\Auth;

use app\common\validates\AddressValidate;

class Address extends Common
{
	use Auth;
	private $user;
	protected $middleware = ['checkLogin'];
	
	function __construct()
	{
	    parent::__construct();
		parent::_initialize();
		$this->user = session(config('permissions.user'));
	} 
	
	/**
	 * 添加地址
	 *
	 * @return json(array)
	 */
	public function save()
	{
		if ($this->request->isPost()) {
			$param = $this->request->post();
			
			$field = ['consignee','country','province','city','district','address','email','mobile','tel','zipcode','sign_building','best_time'];
			$data = [];
			foreach($field as $value){
				if(isset($param[$value])){
					$data[$value] = $param[$value];
				}				
			}
			$data['user_id'] = $this->user['id'];
			$validate = new AddressValidate();
			if (!$validate->check($data)) {
				return json(['code' => 1, 'msg' => $validate->getError()]);
			}
			if($param['address_id']){
				$id = $param['address_id'];
				model("MemberAddressModel")->updateBy($id, $data);
			}
			else{
				$id = model("MemberAddressModel")->insertGetId($data);
			}
						
			if($id){
				model('MemberModel')->updateBy($this->user['id'],['address_id'=>$id]);
				return json(['code'=> 0, 'msg'=>"地址保存成功"]);
			}
			else{
				return json(['code'=> 2, 'msg'=>"地址保存失败"]);
			}
		}
		
	}
	
	/**
	 * 添加地址
	 *
	 * @return json(array)
	 */
	public function delete($id)
	{		
		$address = model("MemberAddressModel")->where("user_id", $this->user['id'])
											->where("id", $id)
											->find()
											->delete();
		
		return json(['code'=> 0, 'msg'=>"删除成功"]);		
	}
	
	/**
	 * 设置默认收货地址
	 *
	 * @return json(array)
	 */
	public function setAddress($id)
	{		
		model('MemberModel')->updateBy($this->user['id'],['address_id'=>$id]);
		
		return json(['code'=> 0, 'msg'=>"收货地址设置成功"]);		
	}
	
	/**
	 * 获取地址
	 *
	 * @return json(array)
	 */
	public function getAddress($id)
	{		
		$province = model("RegionModel")->field("region_id, region_name")
											->where('region_type',1)
											->where('parent_id', 1)
											->select();
		$address = [];
		$city = [];
		$district = [];
		
		if($id){
			$address = model("MemberAddressModel")->where('user_id', $this->user['id'])
												->where('id', $id)
												->find();
			$city = model("RegionModel")->field("region_id, region_name")
												->where('region_type',2)
												->where('parent_id', $address['province'])
												->select();
			$district = model("RegionModel")->field("region_id, region_name")
												->where('region_type',3)
												->where('parent_id', $address['city'])
												->select();
		}
		
		$this->assign(['address'=> $address,'province'=>$province,'city'=>$city,'district'=>$district]);
		$this->view->engine->layout(false);
		return json(['code'=> 0, 'content'=> $this->fetch('/address')]);
	}
}