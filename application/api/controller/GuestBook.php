<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\common\controller\Common;
use think\facade\Cookie;
use think\facade\Config;
//use app\common\validates\GuestBookValidate;
use PHPMailer\SendEmail;
use think\Validate;

class GuestBook extends Common
{
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
	} 
	
	public function save()
	{
		$param = $this->request->post();
		$validate = new Validate($this->rule(),$this->msg());
		if (!$validate->check($param)) {
            return json(['code' => 0, 'msg' => $validate->getError()]);
        }
				
		$cateInfo = model("categoryModel")->get($param['id']);
		$table_name = "form_".$cateInfo->model->table_name;
		
		$data = [];
		foreach($param as $key=>$value){
			if(!in_array($key,['id','captcha'])){
				$data[$key] = checkStrHtml($value);
			}
		}
		
		
		$flag = \think\Db::name($table_name)->data($data)->insert();
		
// 		$subject = "用户留言";
// 		$message = '';
// 		foreach($data as $key=>$value) {
// 			$message .= $value.'<br/>';
// 		}
// 		$mail = new SendEmail();
// 		$msg = $mail->send($subject, $message);
// 
		return json(['code' => $flag, 'msg' => lang('信息保存成功！')]);

	}
	
	public function upload(){
		$data = $this->request->post();
		$validate = new Validate($this->rule(),$this->msg());
		if (!$validate->check($data)) {
			return json(['code' => 0, 'msg' => $validate->getError()]);
		}
	
		if(request()->file('')){
			$file = request()->file('file');
			// 移动到框架应用根目录/uploads/ 目录下
			$info = $file->validate(['size'=>10240000,'ext'=>'pdf,xlsx,xls,doc,docx,zip,rar,7z'])
									->move( 'public/uploads/files');
			$data['files'] = "/public/uploads/files/".$info->getSaveName();
			$data['files'] = str_replace('\\', '/', $data['files']);
		}
		
		$cateInfo = model("categoryModel")->get($data['id']);
		$table_name = "form_".$cateInfo->model->table_name;
	
		unset($data['id']);
		unset($data['captcha']);
		
		
		$flag = \think\Db::name($table_name)->data($data)->insert();
		//return redirect(url("/"));
		return json(['code' => $flag, 'msg' => lang('信息保存成功！'), 'data'=>$data]);
	}
	
	protected function rule()
	{
		return [
				'id|name'  =>  'require|number',
				'name|name'  =>  'require|max:25',
				'captcha' => 'require|captcha'
			];
	}
	
	protected function msg()
	{
		return [
				'name.require'  => lang('name require'),
				'captcha.require'  => lang('captcha require'),
				'captcha.captcha'  => lang('captcha captcha'),
			];
	}
	
	protected function rulesave()
	{
		return [
				'id|name'  =>  'require|number',
				'name|name'  =>  'require|max:25',
				//'captcha' => 'require|captcha'
			];
	}
	
	protected function msgsave()
	{
		return [
				'name.require'  => lang('name require'),
				//'captcha.require'  => lang('captcha require'),
			];
	}
/* 
	//我要买商标
	
	public function purchase($id, GuestBookValidate $validate)
	{
		$this->view->engine->layout(false);
		
		switch($id) {
			case 1 : return $this->fetch('/purchase');
			break;
			case 2 : return $this->fetch('/purchase_2');
			break;
			case 3: return $this->fetch('/purchase_3');
		}
	}
	
	
	//我要卖商标
	public function sell($id, GuestBookValidate $validate)
	{
		$id = intval($id);
		$this->view->engine->layout(false);
		$cateInfo = model("categoryModel")->get($id);
		$this->assign(['typelist' => $cateInfo->typelist]);
		//print_r($cateInfo->typelist);
		switch($id) {
			case 1 : return $this->fetch('/sell');
			break;
			case 2 : return $this->fetch('/sell_2');
			break;
			case 3: return $this->fetch('/sell_3');
		}
		return $this->fetch('/sell');
	}
 */
}