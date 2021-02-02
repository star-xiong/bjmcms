<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\traits\Auth;
use think\Controller;

class Login extends Controller
{
	use Auth;

	protected $redirect = '/admin';

	/**
	 * Login Page
	 *
	 * @return mixed
	 */
	public function login()
	{
		
		// 登录逻辑
		$user = session(config('permissions.user'));
		if($user) {
			return redirect($this->redirect);
		}
		
		if ($this->request->isPost()) {
			return $this->authLogin($this->request);			
		} else {
			return $this->fetch('/index/login');
		}
	}

	/**
	 * 登出
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\think\response\Redirect
	 */
	public function logout()
	{
		$user = session(config('permissions.user'));
		if($user) {
			$this->authLogout();
		} 
		
		$data = ['code'=> 0, 'msg'=>'登出成功', 'data'=> null];
		return json($data);
		
	}

	/**
	 * 验证规则
	 *
	 * @time at 2018年11月13日
	 * @return array
	 */
	protected function rule()
	{
		return [
			'name|用户名'    => 'require',
			'password|密码'  => 'require',
			'captcha|验证码' => 'require|captcha'
		];
	}

}