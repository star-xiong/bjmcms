<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\permissions\facade\Roles;
use app\admin\request\RoleRequest;
use think\permissions\facade\Permissions;
use app\admin\service\TreeService;

class Role extends Base
{
    public function index()
    {
        return $this->fetch();
    }

	/**
	 * Role List
	 *
	 * @time at 2019年03月07日
	 * @return json
	 */
	public function getList()
	{
		return json($this->layuiPaginator(Roles::paginate(15)));
	}

	/**
	 * create Data
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
    public function create(RoleRequest $request)
    {

		if ($request->isPost()) {
			if (Roles::store($request->post())) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
				return json($data);
			}
			$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			return json($data);

		} else {
			return $this->fetch();
		}
		
    }

	/**
	 * Edit Data
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
    public function edit(RoleRequest $request)
    {
    	if ($this->request->isPost()) {
    		Roles::updateBy($request->post('id'), $request->post()) !== false ? $data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'编辑失败', 'data'=> null];
			return json($data);
	    }

    	$this->role = Roles::getRoleBy($this->request->param('id'));
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
    	$roleId = $this->request->post('id');
    	if (!$roleId) {
			$data = ['code'=> 1, 'msg'=>'角色信息不存在', 'data'=> null];
			return json($data);
	    }
	    // 删除角色相关的用户
	    Roles::detachUsers($roleId);
	    // 删除角色相关的权限
	    Roles::detachPermissions($roleId);
	    if (Roles::deleteBy($roleId)) {
		    $this->success('删除成功', url('role/index'));
	    }

	   $data = ['code'=> 2, 'msg'=>'删除失败', 'data'=> null];
	   return json($data);
    }

	/**
	 * 获取角色权限
	 *
	 * @time at 2019年03月07日
	 * @return void
	 */
	public function getPermissionsOfRole(TreeService $menuService)
	{
		$field = ['name', 'id', 'pid'];
		$roleId = $this->request->post('role_id');
		$permissions = Permissions::order('sort')->field($field)->all();
		$roleHasPermissions = Roles::getRoleBy($roleId)->getPermissions(false);
		$permissions = $permissions->each(function ($item, $key) use ($roleHasPermissions){
				if (!$item->pid) {
					$item->open = true;
				}
				$item->checked = in_array($item->id, $roleHasPermissions) ? true : false;
				return $item;
		});
        
		header('content-Type: application/json');
		exit(json_encode($menuService->sort($permissions)));
	}

	/**
	 * 获取角色栏目
	 *
	 * @time at 2019年03月07日
	 * @return void
	 */
	public function getCategoriesOfRole(TreeService $menuService)
	{
		$field = ['title as name', 'id', 'pid'];
		$roleId = $this->request->post('role_id');
		$categories = model('CategoryModel')->field($field)->where('siteid', session('site'))->all();
		
		$roleHasCategories = db('role_has_categories')->where('role_id', $roleId)
													->where('site_id', session('site'))
													->column('category_id');
		$categories = $categories->each(function ($item, $key) use ($roleHasCategories){
				if (!$item->pid) {
					$item->open = true;
				}
				$item->checked = in_array($item->id, $roleHasCategories) ? true : false;
				return $item;
		});
        
		header('content-Type: application/json');
		exit(json_encode($menuService->sort($categories)));
	}
	
	/**
	 * 分配权限
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function givePermissions()
	{
		if ($this->request->isPost()) {
		    $postData = $this->request->post();
		    $roleId      = $postData['role_id'];
		    if (!isset($postData['permissions'])) {
			    Roles::detachPermissions($roleId);
				$data = ['code'=> 0, 'msg'=>'分配成功', 'data'=> null];
				return $data;
		    }

			$permissions = explode(",", $postData['permissions']);
		    Roles::detachPermissions($roleId);
		    Roles::attachPermissions($roleId, $permissions) ? $data = ['code'=> 0, 'msg'=>'分配成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'分配失败', 'data'=> null];
			return $data;
	    } else {
			$this->role_id = $this->request->param('id');
			return $this->fetch('role/givePermissions');
		}
		
	}

	/**
	 * 分配栏目权限
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function giveCategories()
	{
		if ($this->request->isPost()) {
		    $postData = $this->request->post();
		    $roleId      = $postData['role_id'];
		    if (!isset($postData['categories'])) {
			    model('CategoryModel')->detachCategories($roleId);
			    $this->success('分配成功', url('role/index'));
		    }
		    $categories = explode(",", $postData['categories']);
		    model('CategoryModel')->detachCategories($roleId);
		    model('CategoryModel')->attachCategories($roleId, $categories) ? $data = ['code'=> 0, 'msg'=>'分配成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'分配失败', 'data'=> null];
			return $data;
	    } else {
			$this->assign([
			    'role_id'  => $this->request->param('id')
			]);
			return $this->fetch('role/giveCategories');
		}
		
	}
}