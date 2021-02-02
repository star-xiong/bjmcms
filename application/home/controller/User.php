<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\common\controller\Common;
use app\common\model\MemberModel;
use app\common\model\MemberFavoriteModel;
use think\Validate;
use app\traits\Auth;
use tp51\sms\Sms;
use PHPMailer\SendEmail;
use app\common\validates\AddressValidate;

class User extends Common
{
	use Auth;

	protected $redirect = '/';
	
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
		$this->view->config('view_path', './template/member/');
		
	} 

	/**
	 * Login Page
	 *
	 * @return json
	 */
	public function login()
	{
		
		// 登录逻辑,是否登录
		$user = session(config('permissions.user'));
		if($user) {
			return redirect($this->redirect);
		}
		
		if ($this->request->isPost()) {
			
			return $this->authLogin($this->request);
			
		} else {
			return $this->fetch('/login');
		}
	}
	
/* 	public function checkLogin(){
		$user=session(config('permissions.user'));
		if($user){
			$data = ['code'=> 0, 'msg'=>'已经登录', 'data'=> $user];
			return json($arr);
		}
		else{
			if(cookie('username') && cookie('password')){
				$data['username']=encryption(cookie('username'),1);
				$data['password']=encryption(cookie('password'),1);
				$loginRes=model('MemberModel')->login($data,1);
				if($loginRes['error'] == 0){
					$arr['error']=0;
					$arr['uid']=$uid;
					$arr['username']=session('username');
					return json($arr);
				}
			}
			$arr=array();
			$arr['error']=1;
			return json($arr);
		}
	} */
	
	/**
	 * Forgot password Page
	 *
	 * @return json
	 */
	public function password(MemberModel $user)
	{
		if ($this->request->isPost()) {
			
			$param = $this->request->param();
			$type = 'register'; 	//短息模板类型标识 如 register | find_password
			$sms = new Sms();
			$res = $sms->checkVerifyCode($type, $param['phone'], $param['phoneCode']);
			
			if($res['code']){
				return json($res);
			}
			else{
				
				$rule = [
					'password|新密码'  => 'require|min:6|max:20alphaDash',
					'repassword|确认密码'=>'require|confirm:password'
				];
				$validate = new Validate($rule);
				if (!$validate->check($param)){
					$data = ['code'=> 1, 'msg'=>$validate->getError()];
					return json($data);
				}
				
				$resetpwd['password'] = password_hash($param['password'], PASSWORD_DEFAULT);
				$user->where('phone', $param['phone'])->update($resetpwd);
				$data = ['code'=> 0, 'msg'=>'密码重置成功', 'url'=>url('login')];
				
				return json($data);
			}
			
		} else {
			return $this->fetch('/password');
		}
	}
	
	/**
	 * pop_login Page 弹出登录
	 *
	 * @return mixed
	 */
	public function pop_login()
	{
		// 登录逻辑,是否登录
		$user = session(config('permissions.user'));
		if($user) {
			return redirect($this->redirect);
		}
		$this->view->engine->layout(false);
		return $this->fetch('/pop_login');
	}
	
	/**
	 * register Page
	 *
	 * @return mixed
	 */
	public function register()
	{
		
		// 登录逻辑,是否登录
		$user = session(config('permissions.user'));
		if($user) {
			return redirect($this->redirect);
		}

		if ($this->request->isPost()) {
			$param = $this->request->param();
			$type = 'register'; 	//短息模板类型标识 如 register | find_password
			$sms = new Sms();
			$res = $sms->checkVerifyCode($type, $param['phone'], $param['phoneCode']);
			
			if($res['code']){
				return json($res);
			}
			else{
				return $this->authRegister($this->request);
			}
		} else {
			return $this->fetch('/register');
		}
	}
	
	/**
	 * 登出
	 *
	 * @return json(array)
	 */
	public function logout()
	{
		$user = session(config('permissions.user'));
		if($user) {
			$this->authLogout();
		} 
		return redirect($this->redirect);
		
	}
	
	/**
	 * 发送手机短信
	 *
	 * @return json(array)
	 */
	public function send()
	{
		
		$param = $this->request->param();
		
		$validate = new Validate(['phone|手机号码'=>'require|mobile|unique:members', 'captcha|验证码' => 'require|captcha']);
		if (!$validate->check($param)){
			$data = ['code'=> 1, 'msg'=>$validate->getError(), 'url'=> url('register')];
			return json($data);
		}
		
		$sms = new Sms();
		$type = 'register'; //短息模板类型标识 如 register | find_password
		
		//系统内置了获取验证码方法，可以配置 验证码的长度，有效期，有效时间内获取的同一个手机号的同一类型的验证码是否一样
		$code = $sms->getVerifyCode($type, $param['phone']);
		
		//发送短信
		$out_id = $sms->send($type, $param['phone'], ['code'=>$code]);

		if($code){
		    //验证成功了
		    //这里可以写具体的业务逻辑
			$data = ['code'=> 0, 'msg'=>"验证码发送成功"];
			return json($data);
		} else {
		    //验证失败了
		    echo 'error';
		}
	}
	
	/**
	 * 发送手机短信 忘记密码
	 *
	 * @return json(array)
	 */
	public function sendpwd()
	{
		
		$param = $this->request->param();
		
		$validate = new Validate(['phone|手机号码'=>'require|mobile', 'captcha|验证码' => 'require|captcha']);
		if (!$validate->check($param)){
			$data = ['code'=> 1, 'msg'=>$validate->getError(), 'url'=> url('register')];
			return json($data);
		}
		
		$sms = new Sms();
		$type = 'register'; //短息模板类型标识 如 register | find_password
		
		//系统内置了获取验证码方法，可以配置 验证码的长度，有效期，有效时间内获取的同一个手机号的同一类型的验证码是否一样
		$code = $sms->getVerifyCode($type, $param['phone']);
		
		//发送短信
		$out_id = $sms->send($type, $param['phone'], ['code'=>$code]);
	
		if($code){
		    //验证成功了
		    //这里可以写具体的业务逻辑
			$data = ['code'=> 0, 'msg'=>"验证码发送成功"];
			return json($data);
		} else {
		    //验证失败了
		    echo 'error';
		}
	}
	
	/**
	 * 发送邮件
	 *
	 * @return json(array)
	 */
	public function sendemail()
	{
		$data = $this->request->post();
		$subject = "用户留言";
		$message = '';
		foreach($data as $key=>$value) {
			$message .= $value.'<br/>';
		}
		$mail = new SendEmail();
		$msg = $mail->send($subject, $message);
		
	}
	
}