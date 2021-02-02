<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\service;

use think\permissions\facade\Permissions;
use think\Request;
use app\admin\model\LogRecordModel;

class LogService
{

    public function record(Request $request)
    {
		
        $module = $request->module();
        $controller = $request->controller();
        $action = $request->action();
        $user = $request->session(config('permissions.user'));
        $permission = Permissions::getPermissionByModuleAnd($module, $controller, $action);
		if(empty($permission)) {
			$option = $module.'-'.$controller.'-'.$action;
		} else {
			$option = $permission->name;
		}
		
		(new LogRecordModel())->store([
		   'user_id'     => $user->id,
		   'user_name'   => $user->name,
			'module'     => $module,
			'controller' => $controller,
			'action'     => $action,
			'option'     => $option,
			'method'     => $request->method(),
			'created_at'     => date('Y-m-d h:i:s', time()),
		]);

        
    }
}
