<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\permissions\facade\Permissions;
use think\permissions\facade\Roles;
use app\admin\service\TreeService;
use think\facade\Session;
use think\Db;
use think\facade\Cookie;

class Index extends Base
{

	protected $middleware = [ 'checkLogin'];

	/**
	 * 首页
	 *
	 * @time at 2019年02月26日
	 * @return mixed|string
	 */
    public function index(TreeService $menuService)
    {
		$site_id = $this->request->param('site') ?? 1;
		$site_info = model('SiteModel')->get($site_id);
		Session::set('site', $site_id);
		Cookie::forever('bjmsite_id', $site_id);
		Cookie::forever('bjmsite_domain', $site_info['domain']);
    	$loginUser = $this->getLoginUser();
    	$userHasRoles = $loginUser->getRoles();
    	$permissionIds = [];
		$userHasRoles->each(function ($role, $key) use (&$permissionIds) {
			$permissionIds = array_merge($permissionIds, Roles::getRoleBy($role->id)->getPermissions(false));
		});
		$permissions = Permissions::order('sort')->whereIn('id', $permissionIds)->where('is_show', 1)->select();

		$this->assign([
		    'permissions'  => $menuService->tree($permissions),
			'loginUser'  => $loginUser,
			'default_site'  => $site_info,
			'sites'  => model('SiteModel')->where('status',1)->order('sort', 'asc')->select()
		]);
        return $this->fetch();
    }


    /**
     * main
     *
     * @time at 2019/2/26
     * @return \think\Response
     */    
    public function main()
    {
		$sys_info = $this->get_sys_info();
		$this->assign(['sys_info' => $sys_info]);
    	return $this->fetch();
    }
	
	private function get_sys_info()
	{
	    $sys_info['os']             = PHP_OS;
	    $sys_info['zlib']           = function_exists('gzclose') ? 'YES' :
										'<font color="red">NO（请开启 php.ini 中的php-zlib扩展）</font>';		//zlib
	    $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';		//safe_mode = Off       
	    $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
	    $sys_info['curl']           = function_exists('curl_init') ? 'YES' : '<font color="red">NO（请开启 php.ini 中的php-curl扩展）</font>';  
	    $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
	    $sys_info['phpv']           = phpversion();
	    $sys_info['ip']             = gethostbyname($_SERVER["SERVER_NAME"]);
	    $sys_info['postsize']       = @ini_get('file_uploads') ? ini_get('post_max_size') :'unknown';
	    $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
	    $sys_info['max_ex_time']    = @ini_get("max_execution_time").'s'; 	//脚本最大执行时间
	    $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
	    $sys_info['domain']         = $_SERVER['HTTP_HOST'];
	    $sys_info['memory_limit']   = ini_get('memory_limit');
	    $sys_info['version']        = '1.0';
	    $mysqlinfo = Db::query("SELECT VERSION() as version");
	    $sys_info['mysql_version']  = $mysqlinfo[0]['version'];
	    if(function_exists("gd_info")){
	        $gd = gd_info();
	        $sys_info['gdinfo']     = $gd['GD Version'];
	    }else {
	        $sys_info['gdinfo']     = "未知";
	    }
	    if (extension_loaded('zip')) {
	        $sys_info['zip']     = "YES";
	    } else {
	        $sys_info['zip']     = '<font color="red">NO（请开启 php.ini 中的php-zip扩展）</font>';
	    }

	    $sys_info['curent_version'] = '1.0'; //当前程序版本
	    $sys_info['web_name'] = 'BJMCMS';
	
	    return $sys_info;
	}
}
