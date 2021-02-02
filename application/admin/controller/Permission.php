<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\Collection;
use think\permissions\facade\Permissions;
use app\admin\request\PermissionRequest;
use app\admin\service\TreeService;

class Permission extends Base
{
    public function index(TreeService $menuService)
    {
        return $this->fetch();
    }

	/**
	 * Get permission list
	 *
	 * @time at 2019年03月07日
	 * @return json
	 */
	public function getList(TreeService $menuService)
	{
		return json(Permissions::order('sort')->All());
	}
	/**
	 * Create Permission
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
    public function create(PermissionRequest $request, TreeService $menuService)
    {
    	if ($request->isPost()) {
    		$param = $request->post();
			if (isset($param['is_show']) && $param['is_show'] == 'on') {
				$param['is_show'] = 1;
			} else {
				 $param['is_show'] = 2;
			}
			$nowTime = date('Y-m-d h:i:s', time());
			
			$param['module'] = 'admin';
			$param['created_at'] = $nowTime;
			$param['updated_at'] = $nowTime;
    		Permissions::store($param) ? $data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			return json($data);
	    }

	    $this->permissions = $menuService->sort(Permissions::order('sort')->select());
    	$this->permissionId  = $this->request->param('id') ?? 0;
        return $this->fetch();
    }

	/**
	 * Edit Permission
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
    public function edit(PermissionRequest $request, TreeService $menuService)
    {
    	if ($request->isPost()) {
    		$param = $request->post();
			if (isset($param['is_show']) && $param['is_show'] == 'on') {
				$param['is_show'] = 1;
			} else {
				 $param['is_show'] = 2;
			}
			$param['updated_at'] = date('Y-m-d h:i:s', time());
    		Permissions::updateBy($param['id'], $param) !== false ? $data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			return json($data);
	    }
    	$permissionId = $this->request->param('id');
    	if (!$permissionId) {
    		$this->error('不存在的数据');
	    }
	    $this->permissions = $menuService->sort(Permissions::order('sort')->select());
    	$this->_permission = Permissions::getPermissionBy($permissionId);
        return $this->fetch();
    }

	/**
	 * Delete Data
	 *
	 * @time at 2019年03月07日
	 * @return void
	 */
    public function delete()
    {
    	$permissionId = $this->request->post('id');
    	if (!$permissionId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
	    }
	    if (Permissions::where('pid', $permissionId)->find()) {
			$data = ['code'=> 2, 'msg'=>'请先删除子菜单', 'data'=> null];
			return json($data);
	    }
	    // 删除权限关联的角色信息
	    Permissions::detachRole($permissionId);
	    if (Permissions::deleteBy($permissionId)) {
		    $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
		    return json($data);
	    }
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
    }
}