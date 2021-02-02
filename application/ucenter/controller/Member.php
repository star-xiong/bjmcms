<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\ucenter\controller;

use app\common\controller\Common;
use app\common\model\MemberModel;
use app\common\model\MemberFavoriteModel;
use think\Validate;
use app\traits\Auth;
use tp51\sms\Sms;
use PHPMailer\SendEmail;
use app\common\validates\AddressValidate;

class Member extends Common
{
	use Auth;
	protected $middleware = ['checkLogin'];
	protected $redirect = '/';
	public $userinfo;
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
		$this->userinfo = session(config('permissions.user'));
		$this->view->config('view_path', './template/member/');
		
	} 
	
	/**
	 * 会员中心
	 *
	 */
	public function index(MemberModel $user)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		$userinfo = $this->userinfo;
		$info = $user->get($userinfo['id']);
		//最近30天订单数量
		$time =  date("Y-m-d H:i:s",strtotime("-30 day"));
		$info->order_num = model('OrderInfoModel')->where('siteid',$this->current_site->id)
											->where('user_id',$userinfo['id'])
											->where('add_time','>',$time)
											->count('id');
		unset($info['password']);
		$this->assign(['user' => $info]);
		return $this->fetch('/user/index');
	}
	
	/**
	 * 修改密码
	 *
	 */
	public function resetpasswd(MemberModel $user)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		//$userinfo = session(config('permissions.user'));
		$info = $user->get($this->userinfo['id']);
		unset($info['password']);
		
		$this->assign(['user' => $info]);
		return $this->fetch('/resetpasswd');
	}
	
	/**
	 * 会员修改
	 *
	 */
	public function edit(MemberModel $user)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		$userinfo = $this->userinfo;
		
		if ($this->request->isPost()) {
			$param = $this->request->param();
			unset($param['name']);
			if(!empty($param['password'])) {
				//检测旧密码 old
				$rule = [
					'oldpassword|旧密码' => 'require|min:6|max:20|alphaDash',
					'password|新密码'  => 'require|min:6|max:20alphaDash',
					'repassword|确认密码'=>'require|confirm:password'
				];
				$validate = new Validate($rule);
				if (!$validate->check($param)){
					$data = ['code'=> 1, 'msg'=>$validate->getError()];
					return json($data);
				}
				$info = $user->get($userinfo['id']);
				
				if(!password_verify($param['oldpassword'], $info->password)) {
					$data = ['code'=> 1, 'msg'=> '旧密码错误'];
					return json($data);
				}
				if($param['password'] == $param['oldpassword']) {
					$data = ['code'=> 1, 'msg'=> '新旧密码不能相同'];
					return json($data);
				}
				$param['password'] = password_hash($param['password'], PASSWORD_DEFAULT);
				unset($param['op']);
				unset($param['oldpassword']);
				unset($param['repassword']);
			}
			else {
				$rule = [
					//'name|用户名' => 'require|min:3|max:15|unique:users',
					'email|邮箱' => 'email|unique:users'
				];
				$validate = new Validate($rule);
				if (!$validate->check($param)){
					$data = ['code'=> 1, 'msg'=>$validate->getError()];
					return json($data);
				}
			}
			
			$user->where('id', $userinfo['id'])->update(array_filter($param));
			
			$data = ['code'=> 0, 'msg'=>'更新成功'];
			
			return json($data);
		}
		
		$info = $user->get($userinfo['id']);
		unset($info['password']);
		$this->assign(['user' => $info]);
		return $this->fetch('/user/edit');
	}
	
	/**
	 * 上传用户图像
	 *
	 */
	public function upload(MemberModel $user)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		$userinfo = $this->userinfo;
		if(empty($userinfo)) {
			$data = ['code'=> 1, 'msg'=>'没有登录'];
			
			return json($data);
			exit();
		}
		
		if ($this->request->isPost()) {
			
			$param = $this->request->param();
			
			if(!empty($param['avatar'])){
				//匹配出图片的格式
				if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $param['avatar'], $result)){
					$type = $result[2];
					$allow_types = ['png','jpeg','jpg','PNG','JPEG','JPG'];
					if(!in_array($type, $allow_types)) {
						$data = ['code'=> 1, 'msg'=>'图片格式不支持'];
						return json($data);
					}
					$root_path = \Env::get('root_path');
					$path = "public/uploads/avatar/";
					$new_file = $root_path.$path;
					if(!file_exists($new_file)){
						//检查是否有该文件夹，如果没有就创建，并给予最高权限
						mkdir($new_file, 0700);
					}
					
					$file_name = time().".{$type}";
					
					$new_file = $new_file.$file_name;
					if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $param['avatar'])))){
						$file_url = '/'.$path.$file_name;
						$user->where('id', $userinfo['id'])->update(['avatar'=>$file_url]);
						$data = ['code'=>0,'msg'=>'上传成功', 'avatar_path'=>$file_url];
					}else{
						$data = ['code'=>1,'msg'=>'上传失败'];
					}
				}else{
					$data = ['code'=>2,'msg'=>'参数错误'];
				}

			}else{
				$data = ['code'=>2,'msg'=>'参数错误'];
			}
			
			return json($data);
		}
	}
	
	
	//地址
	public function address() {
		$province = model("RegionModel")->field("region_id, region_name")
											->where('region_type',1)
											->where('parent_id', 1)
											->select();
		$address = model('MemberAddressModel')->where('user_id',$this->userinfo['id'])->select();
		foreach($address as $key=>$value) {
			$address[$key]['city_list'] = model("RegionModel")->field("region_id, region_name")
												->where('region_type',2)
												->where('parent_id', $value['province'])
												->select();

			$address[$key]['district_list']= model("RegionModel")->field("region_id, region_name")
												->where('region_type',3)
												->where('parent_id', $value['city'])
												->select();
		}
		$this->assign(['address' => $address,'province' => $province,'user'=>$this->userinfo]);
		return $this->fetch('/user/address');
	}
	
	
	/**
	 * 收藏夹
	 *
	 */
	public function myfavorite(MemberModel $user, MemberFavoriteModel $favorite)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		$userinfo = $this->userinfo;


		$favorites = $favorite->where('member_id',$userinfo->id)->select();
		$info = $user->get($userinfo['id']);
		unset($info['password']);
		$this->assign(['favorites' => $favorites]);
		$this->assign(['user' => $info]);
		return $this->fetch('/myfavorite');
	}
	
	/**
	 * 收藏产品
	 *
	 */
	public function favorite($id, MemberFavoriteModel $favorite)
	{
		// 登录逻辑,是否登录,没有登录则跳转到登录页
		$userinfo = $this->userinfo;
		if(empty($userinfo)) {
			$data = ['code'=> 5, 'msg'=>'没有登录,请登录后再收藏.'];
			return json($data);
			exit();
		}
		
		if ($id) {
			$param = ['member_id'=>$userinfo->id, 'content_id' => intval($id)];
			$myfav = $favorite->where($param)->find();
			
			if(empty($myfav['content_id'])) {
				//收藏
				$param['created_at'] = date('Y-m-d h:i:s', time());
				$favorite->insert($param);
				$data = ['code'=>1,'msg'=>'收藏成功'];
			}
			else {
				//取消收藏
				$favorite->where($param)->delete();
				$data = ['code'=>2,'msg'=>'已取消收藏'];
			}
			
			return json($data);
		}
		
		$data = ['code'=>3,'msg'=>'参数错误','data'=>$id];
		
		return json($data);
	}
	
}