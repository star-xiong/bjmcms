<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\behavior;

class LoginRecord
{
	public function run($params)
	{
		$user = $params['user'];
		## 登录记录
		$user->login_at	= date('Y-m-d h:i:s', time());
		$user->login_ip	= request()->ip();
		$user->save();
	}
}