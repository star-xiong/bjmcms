<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\validates;

use think\Validate;
 
class AddressValidate extends Validate {
	public function getErrors($data)
	{
		$this->check($data);
	
		return $this->getError();
	}
	
	
	public function __set($name, $value)
	{
	    // TODO: Implement __set() method.
	    $this->rule[$name] = $value;
	}
	protected $rule = [
		'user_id' => 'require|number',
		'consignee' => 'require',
		'country' => 'require|number',
		'province' => 'require|number',
		'city' => 'require|number',
		'district' => 'require|number',
		'address' => 'require|chsDash',
		'email' => 'email',
		'mobile' => 'require|mobile'
	];
	
	protected $message = [
		'user_id.require' => '未登录',
		'name.require' => '收件人不能为空',
		'country.require' => '国家不能为空',
		'province.require' => '省不能为空',
		'province.number' => '省代码必顺是数字',
		'city.require' => '市不能为空',
		'city.number' => '市代码必顺是数字',
		'district.require' => '区不能为空',
		'district.number' => '区代码必顺是数字',
		'address.require' => '地址不能为空',
		'address.chsDash' => '地址只能是汉字、字母、数字和下划线_及破折号-',
		'email.email' => 'email地址格式错误',
		'mobile.number' => '手机号不能为空',
		'mobile.mobile' => '不是有效的手机号'
	];
	
	
	/*示例
	*
	protected $rule = [
		'name' => 'require|max:25|checkName:用户名不能为123',
		'nickname' => 'require'
	];

	protected $message = [
		'name.require' => '名称不能为空',
		'name.max' => '名称最少为25个字符',
		'nickname.require' => 'nickname不能为空'
	];

	// 自定义验证规则
	protected function checkName($value,$rule,$data=[],$name,$description){
		if ($value == '123'){
			return $rule;
		}else{
			return true;
		}
    }*/
}