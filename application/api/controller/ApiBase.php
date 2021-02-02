<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\api\controller;

use think\Controller;
use think\Collection;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;
use app\service\TreeService;
use think\facade\Cache;
use app\traits\Auth;

class ApiBase extends Controller
{
	use Auth;
	public $current_site;
	public $site_list;
	
	 /*
	 * 初始化操作
	 */
	public function _initialize() 
	{
		$this->rememberLogin();
		
		//前当语言
		$lang = get_current_lang();
		$this->current_site = model('SiteModel')->where('mark', $lang)->find();		
	}
	
}
