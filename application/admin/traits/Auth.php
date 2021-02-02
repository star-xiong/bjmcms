<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\traits;

use think\Request;
use think\Validate;
use think\facade\Session;
use think\facade\Cookie;
use app\admin\model\UserModel as User;
use app\admin\behavior\LoginRecord;

trait Auth
{
	public function authLogin(Request $request)
	{		
		$err = $this->validateLogin($request);		
		if ($err) {
			$data = ['code'=> 1, 'msg'=>$err, 'data'=> null];
			return json($data);
		}

		// 正常输入登录
		$userModel = new User();
		$user = $userModel::where('name', $request->param('name'))->find();

		if (!$user) {
			$data = ['code'=> 1, 'msg'=>'密码错误', 'data'=> null];
			return json($data);
		}
		if ($user->status != 1) {
			$data = ['code'=> 2, 'msg'=>'帐户被锁定，请联系管理员解锁。', 'data'=> null];
			return json($data);
		}
		if (password_verify($request->param('password'), $user->password)) {
			
			Session::set(config('permissions.user'), $user);
			
			# 记住登录
			$rememberToken = $this->LoginRemember($user, $request);
			
			# 登录记录
			hook(LoginRecord::class, ['user' => $user]);
			
			$data = ['code'=> 0, 'msg'=>'登入成功', 'data'=> ['access_token'=>$rememberToken]];
			
			return json($data);
			//$this->success('登录成功', url($this->redirect));
		}

		//$this->error('登录失败');
		$data = ['code'=> 1, 'msg'=>'登录失败', 'data'=> null];
		return json($data);

	}

	/**
	 * 记住登录
	 * @return bool
	 */
	public function rememberLogin()
	{
		// 如果记住登录
		if (!Session::get(config('permissions.user')) && Cookie::get(config('permissions.token')) && $this->checkRememberToken()) {
			return true;
		}

		return false;
	}

	/**
	 * 退出
	 * @return void
	 */
	public function authLogout()
	{
		$user = Session::get(config('permissions.user'));
		$this->deleteToken($user);
		Session::delete(config('permissions.user'));
	}

	protected function deleteToken($user)
	{
		if ($user->remember_token) {
			$user->remember_token = 'null';
			$user->save();
			Cookie::delete(config('permissions.token'));
		}
	}
	/**
	 * 验证
	 * @param Request $request
	 * @return array|bool
	 */
	protected function validateLogin(Request $request)
	{
		$validate = new Validate($this->rule());
		if (!$validate->check($request->except(['remember']))) {
			return $validate->getError();
		}

		return false;
	}

	/**
	 * 登录验证规则
	 * @return array
	 */
	protected function rule()
	{
		return [
			$this->name()    => 'require|token|alphaDash',
			'password|密码'  => 'require|alphaDash',
			'captcha|验证码' => 'require|captcha'
		];
	}

	/**
	 * Remember Token
	 *
	 * @return string
	 */
	public function generateRememberToken()
	{
		return uniqid(md5(time()+rand(10000, 99999)));
	}

	/**
	 * 加密 TOKEN
	 *
	 * @param $user_id
	 * @param $remember_token
	 * @return string
	 */
	protected function secretRememberToken($user_id, $remember_token)
	{
		list($key, $method, $iv) = $this->getSecret();
		return base64_encode(openssl_encrypt($user_id . ':' . $remember_token, $method, $key, OPENSSL_RAW_DATA, $iv));
	}

	/**
	 * 检查remember token 是否正确
	 *
	 * @return bool
	 */
	protected function checkRememberToken()
	{
		if (!Cookie::has(config('permissions.token'))) {
			return false;
		}
		$rememberToken = Cookie::get(config('permissions.token'));
		// 解密
		list($key, $method, $iv) = $this->getSecret();
		list($userID) = explode(':', (openssl_decrypt(base64_decode($rememberToken), $method, $key, OPENSSL_RAW_DATA, $iv)));
	
		// 校验
		$user = (new User())->findBy($userID);
		Session::set(config('permissions.user'), $user);
		return $user->remember_token == $rememberToken;
	}

	/**
	 * 加密
	 *
	 * @return array
	 */
	protected function getSecret()
	{
		return ['admin_auth', 'AES-128-CBC', config('permissions.key_secret')];
	}

	/**
	 * 记住
	 *
	 * @param $user
	 * @return void
	 */
	protected function LoginRemember($user, Request $request)
	{
		if ($request->has('remember')) {
			$rememberToken = $this->secretRememberToken($user->id, $this->generateRememberToken());
			$user->remember_token = $rememberToken;
			Cookie::forever(config('permissions.token'), $rememberToken);
			return $rememberToken;
		}
	}

}