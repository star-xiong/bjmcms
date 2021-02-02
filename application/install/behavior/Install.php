<?php
namespace app\install\behavior;

class Install 
{
    public function installBegin()
    {
          //安装开始  钩子名称 install_begin
		
    }
	
	public function installEnd($params)
    {
		if(ismodule("Administrator")){
			
			$username = $params['user'];
			
			$password = $params['password'];
			
			$pass_prefix = exeFun('GetRandStr',[6],'administrator');
	
			$param['username'] = $username;

			$param['password'] = md5($password.$pass_prefix);

			$param['pass_prefix'] = $pass_prefix;

			$param['group_id'] = 1;

			$param['isdel'] = 1;

			$param['status'] = 1;

			$user = db('administrator_user')->strict(false)->insert($param);
		}
		//安装结束 钩子名称 install_begin
		
    }
}
?>