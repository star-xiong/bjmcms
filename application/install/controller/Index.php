<?php
namespace app\install\controller;
use think\Controller;
use think\facade\Request;
use think\facade\Env;
use think\facade\Hook;
class Index extends Controller
{
	public $config;
	public function index()
    {
		$root_path = env::get('root_path');
		$file = new \files\File();
		if($file->f_has($root_path."application/install/install.install")) die('程序已经安装，如重新安装请到install模块根目录下删除install.install');
		$step = Request::param('step');
		$step?$step:$step=1;
		if(!session("step") || (session("step")+1)<$step){
			session("step",$step);
			echo "<script>location.href='".url('install/index/index',['step'=>session("step")])."'</script>";
		}else{
			session("step",$step);
		}
		$config = config();
		switch($step)
		{
			case '1'://安装第一步，阅读安装协议
				$content = lang('Agreement');
				$this->assign('content', $content);
				$pagename = lang('step0');
				$this->assign('pagename', $pagename);
				return $this->fetch('install'); 
			break;
			case '2'://安装第二步，检测环境
				$pagename = lang('step1');
				$config = $config['config']['content'];
				$env_items = is_system($config['env_items']);
				$this->assign('pagename', $pagename);
				$this->assign('env_items', $env_items);
				$dir_items = is_write_array($config['dir_items']);
				$this->assign('dir_items', $dir_items);
				$func_items = get_php_system($config['func_items']);
				$this->assign('func_items', $func_items);
				return $this->fetch('step1');
			break;
			case '3'://安装第三步，数据库信息配置
				if(!session('environment')) echo "<script>location.href='".url('install/index/index',['step'=>2])."'</script>";
				$pagename = lang('step2');
				$this->assign('pagename', $pagename);
				return $this->fetch('step2');
			break;
			case '4'://安装第四步，安装数据库
				$params =[];
				$params['step'] = 5;
				if(ismodule("Admin")){
					$params["user"] = $this->request->param('user','','trim');
					$params["password"] = $this->request->param('password','','trim');
					$params["password2"] = $this->request->param('password2','','trim');
				}
				$this->assign('params', $params);
				Hook::listen("install_begin",$params);
				cache('sqlfile',null);
				$config = Request::except('step');
				if(!$config) echo "<script>location.href='".url('install/index/index',['step'=>3])."'</script>";
				$configs = $this->ReturnSqlConfig($config);
				$this->assign('configs', $configs);
				$Sql = new \database\Model($configs);
				if(!$Sql->mysql_isconnect()) exit('数据库了错误请返回上一步重新配置');
				if(!$Sql->has($configs['database'])){
					if($Sql->create($configs['database'])!=1){
						exit('创建新数据库失败，请检查您的用户权限');
					}
				}

				$pagename = lang('step3');
				$this->assign('pagename', $pagename);				
				return $this->fetch('step3');
			break;
			case '5'://安装第一步，阅读安装协议
				$pagename = lang('step4');
				$params = $this->request->param();
				if(!session('environment')) echo "<script>location.href='".url('install/index/index',['step'=>2])."'</script>";
				session("step",null);
				session('environment',null);
				cache('sqlfile',null);
				$file->write($root_path."application/install/install.install","");
				$this->assign('pagename', $pagename);
				Hook::listen("install_end",$params);
				return $this->fetch('update');	
			break;
		}
		
    }
	/*
	ajax检测数据库是否连接
	*/
    public function AjaxCheckDatabase()
    {
        $config = Request::param();
		$configs = $this->ReturnSqlConfig($config);
		$Sql = new \database\Model($configs);
		if($Sql->mysql_isconnect() == 1){
			if($configs['database']){
				if($Sql->has($configs['database'])){
					echo 2;
				}else{
					echo $Sql->mysql_isconnect();
				}
			}else{
				echo $Sql->mysql_isconnect();
			}
		}else{
			echo 0;
		}
    }
	/*
	执行sql数组
	*/
	public function ReadSqlFileArray()
	{
		$file = new \files\File();
		$sql_path = env('module_path')."database";
		$sql_array = $file->get_all_dir($sql_path);
		$config = Request::param();
		$num = count($sql_array);
		$Sql = new \database\Model($config);
		$Sql->mysql_exe_sql_file($sql_array,['mkcms_'=>$config["prefix"]],0);
	}	
	/*
	读取缓存
	*/	
	public function ReadCache(){
		echo json_encode(cache('sqlfile'));
	}
	
	/*
	安装状态检测
	*/
	public function WebStatus()
	{
		session('environment',true);
	}
	/*
	打开数据库配置文件写入配置
	*/
	public function WriteDatabase()
	{
		$config = Request::param();
		if($config){
			$data['type'] = isset($config['type'])?$config['type']:"mysql";
			$data['hostname'] = isset($config['hostname'])?$config['hostname']:"127.0.0.1";	
			$data['database'] = isset($config['database'])?$config['database']:"";		
			$data['username'] = isset($config['username'])?$config['username']:"";		
			$data['password'] = isset($config['password'])?$config['password']:"";		
			$data['hostport'] = isset($config['hostport'])?$config['hostport']:"";		
			$data['params'] = isset($config['params'])?$config['params']:"";		 
			$data['prefix'] = isset($config['prefix'])?$config['prefix']:"";	
			if(FF("database", $data, env("config_path"))){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		//FF($filename,$data="",$path='',$type = 1);
	}
	/*
	更新数据库配置内容，返回与TP要求格式相同的内容
	*/
	public function ReturnSqlConfig($config)
	{
		$configs['type'] = isset($config['databasetype'])?$config['databasetype']:"mysql";
		// 服务器地址
		$configs['hostname'] = isset($config['databaseurl'])?$config['databaseurl']:"";
		// 数据库名
		$configs['database'] = isset($config['databasename'])?$config['databasename']:"";
		//$configs['database'] = "";
		// 数据库用户名
		$configs['username'] = isset($config['databaseuser'])?$config['databaseuser']:"";
		// 数据库密码
		$configs['password'] = isset($config['databasepassword'])?$config['databasepassword']:"";
		// 数据库连接端口
		$configs['hostport'] = isset($config['databaseport'])?$config['databaseport']:"3306";
		
	    $configs['params'] = [];
		// 数据库编码默认采用utf8
		$configs['charset'] = 'utf8';
		// 数据库表前缀
		$configs['prefix'] = isset($config['databaseprefix'])?$config['databaseprefix']:"mkcms";
		return $configs;
	}
}

