<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\validates;

use think\Validate;

class GuestBookValidate extends Validate
{

	/**
	 * Get Validate Errors
	 *
	 * @time at 2018年11月12日
	 * @param $data
	 * @return array
	 */
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
		'id|栏目ID'  =>  'require|number',
        'name|称呼'  =>  'require|max:25',
        'phone|电话' =>  'mobile',
		//'content|内容' =>  'require|max:500',
		//'captcha|验证码' => 'require|captcha'
    ];
}