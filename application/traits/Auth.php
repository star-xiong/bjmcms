<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\traits;

use think\Request;
use think\Validate;
use think\facade\Session;
use think\facade\Cookie;

trait Auth
{
	protected $loginUserKey = 'member';
	
	//登录
	public function authLogin(Request $request)
	{

		$err = $this->validateLogin($request);		
		if ($err) {
			$data = ['code'=> 1, 'msg'=>$err, 'url'=>url('login')];
			return json($data);
		}
		
		$params = $request->param();
		// 正常输入登录
		$userModel = model("MemberModel");

		$user = $userModel->where(array('name'=>$params['name']))
						->whereOr(array('email'=>$params['name']))
						->whereOr(array('phone'=>$params['name']))
						->find();
	
		if (!$user) {
			$data = ['code'=> 1, 'msg'=>'用户名或者密码错误', 'url'=>url('login')];
			return json($data);
		}
		
		if($user->status != 1){
			// 用户是否被封
			$data = ['code'=> 1, 'msg'=>'该帐户被锁定', 'url'=>url('login')];
			return json($data);
		}
		
		if (password_verify($params['password'], $user->password)) {
			
			Session::set(config('permissions.user'), $user);
			
			# 记住登录
			$rememberToken = $this->LoginRemember($user, $request);

			# 登录记录
			$flag = $userModel->update(['id'=>$user['id'], 'remember_token' => $rememberToken, 'login_at' => date("Y-m-d H:i:s", time())]);
			
			$data = ['code'=> 0, 'msg'=>'登入成功', 'data'=> ['access_token'=>$rememberToken, 'url'=>url('/')]];
			
			return json($data);
		}

		$data = ['code'=> 1, 'msg'=>'用户名或者密码错误', 'data'=> null];
		return json($data);

	}


	/**
	 * 会员注册
	 * @return bool
	 */
	public function authRegister(Request $request)
	{
		$err = $this->validateRegister($request);		
		if ($err) {
			$data = ['code'=> 1, 'msg'=>$err, 'url'=> url('register')];
			return json($data);
		}
		
		// 正常输入登录
		$userModel = model("MemberModel");
		$param = [];
		$param['name'] = $request->param('name');
		$param['password'] = password_hash($request->param('password'), PASSWORD_DEFAULT);
		$param['email'] = $request->param('email');
		$param['phone'] = $request->param('phone');
		$param['created_at'] = date("Y-m-d H:i:s", time());
		$param['login_at'] = date("Y-m-d H:i:s", time());
		
		$flag = $userModel->strict(false)->insertGetId($param);
		if($flag) {
			$user = $userModel::get($flag);
			
			Session::set(config('permissions.user'), $user);
		
			# 记住登录
			$rememberToken = $this->LoginRemember($user, $request);
			
			# 登录记录
			$flag = $userModel->update(['id'=>$user['id'], 'remember_token' => $rememberToken, 'login_at' => date("Y-m-d H:i:s", time())]);
			
			$data = ['code'=> 0, 'msg'=>'注册成功', 'data'=> ['access_token'=>$rememberToken, 'url'=>url('/')]];
			
			return json($data);

		}
		else {
			$data = ['code'=> 2, 'msg'=>'注册失败', 'url'=> url('register')];
			return json($data);
		}
	}

	/**
	 * 记住登录
	 * @return bool
	 */
	public function rememberLogin()
	{
		// 如果记住登录
		if (!Session::get(config('permissions.user')) && Cookie::get(config('permissions.token')) && $this->checkRememberToken()) 
		{
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
	 * 验证 登录信息
	 * @param Request $request
	 * @return array|bool
	 */
	protected function validateLogin(Request $request)
	{
		$validate = new Validate($this->ruleLogin());
		if (!$validate->check($request->except(['remember']))) {
			return $validate->getError();
		}

		return false;
	}
	
	/**
	 * 登录验证规则
	 * @return array
	 */
	protected function ruleLogin()
	{
		return [
			$this->name()    => 'require|alphaDash',
			'password|密码'  => 'require|alphaDash',
			'captcha|验证码' => 'require|captcha'
		];
	}
	
	/**
	 * 验证 注册
	 * @param Request $request
	 * @return array|bool
	 */
	protected function validateRegister(Request $request)
	{
		$validate = new Validate($this->ruleRegister());
		if (!$validate->check($request->except(['remember']))) {
			return $validate->getError();
		}
	
		return false;
	}

	/**
	 * 注册验证规则
	 * @return array
	 */
	protected function ruleRegister()
	{
		return [
			$this->name()    => 'require|alphaDash|unique:members',
			'password|密码'  => 'require|alphaDash',
			'repassword|确认密码'=>'require|confirm:password',
			'phone|手机号' => 'require|mobile|unique:members',
			//'captcha' => 'require|captcha'
		];
	}

	/**
	 * 设置登录字段
	 *
	 * @return string
	 */
	protected function name()
	{
		return 'name|用户名';
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
		$user = model("MemberModel")->get($userID);
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
		return ['member_auth', 'AES-128-CBC', config('permissions.key_secret')];
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
			Cookie::forever(config('permissions.token'), $rememberToken);
			return $rememberToken;
		}
	}
}