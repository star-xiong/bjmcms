<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\validates;

use think\Validate;

abstract class AbstractValidate extends Validate
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
}