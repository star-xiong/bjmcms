<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\traits;

use think\facade\Session;

trait ControllerTrait
{
	protected $vars = [];

	/**
	 * 绑定实现
	 *
	 * @time at 2018年11月16日
	 * @return void
	 */
	// public function initialize()
	// {
	// 	//bind(UploadInterface::class, LocalUpload::class);
	// }

	/**
	 * 是否登录
	 *
	 * @return bool
	 */
	protected function isLogin()
	{
		return $this->getLoginUser() ? true : false;
	}

	/**
	 * 获取登录用户
	 *
	 * @return mixed
	 */
	protected function getLoginUser()
	{
		return Session::get(config('permissions.user'));
	}

	/**
	 * fetch 重写
	 *
	 * @param string $template
	 * @param array $vars
	 * @param array $config
	 * @return mixed
	 */
	protected function fetch($template = '', $vars = [], $config = [])
	{
		$vars = array_merge($this->vars, $vars);

		return $this->view->fetch($template, $vars, $config);
	}

	/**
	 * Set Template Vars
	 *
	 * @param $name
	 * @param $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		// TODO: Implement __set() method.
		$this->vars[$name] = $value;
	}
}