<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\UserModel;
use app\admin\request\UserRequest;
use think\permissions\facade\Roles;
//use think\Request;
class User extends Base
{
	/**
	 * User Admin 管理首页
	 *
	 * @time at 2019年02月27日
	 * @return mixed|string
	 */
	public function index()
	{
		$this->roles = Roles::all();
		return $this->fetch();
	}

	/**
	 * User List
	 *
	 * @time at 2019年02月28日
	 * @return json
	 */
	public function getList(UserModel $userModel)
	{
		$params = $this->request->param();
		$this->checkParams($params);
		return json($this->layuiPaginator($userModel->getList($params, 50)));
	}
	
	/**
	 * create Data
	 *
	 * @time at 2019年02月27日
	 * @return mixed|string
	 */
	public function create(UserModel $userModel, UserRequest $request)
	{
		if ($request->isPost()) {

			$data = $request->post();
			$data['password'] = generatePassword($data['password']);

			if ($userId = $userModel->store($data)) {
				// 分配角色
				$this->giveRoles($userModel, $userId, $data);
				//$this->success('添加成功', url('user/index'));
				$data = ['code'=> 0, 'msg'=>'添加成功', 'data'=> null];
				return json($data);
			}
			$data = ['code'=> 1, 'msg'=>'添加失败', 'data'=> null];
			return json($data);
			//$this->error('添加失败');
		} else {
			$this->roles = Roles::all();
			return $this->fetch();
		}
	}

	/**
	 * Edit Data
	 *
	 * @time at 2019年02月27日
	 * @return mixed|string
	 */
	public function edit(UserModel $userModel, UserRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			
			$this->giveRoles($userModel, $param['id'], $param);
			
			if ($param['password']) {
				$param['password'] = generatePassword($param['password']);
			} else {
				unset($param['password']);
			}
			
			$userModel->updateBy($param['id'], $param) ? $data = ['code'=> 0, 'msg'=>'修改成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'修改失败', 'data'=> null];
			return json($data);
		}

		$id = $this->request->param('id');
		if (!$id) {
			$this->error('数据不存在');
		}
		$user = $userModel->findBy($id);
		$userHasRoles = $user->getRoles(false);
		$roles = Roles::all()->each(function($item, $key) use ($userHasRoles){
				$item->checked = in_array($item->id, $userHasRoles) ? true : false;
				return $item;
		});

		$this->user   =  $user;
		$this->roles  = $roles;
		return $this->fetch();
	}

	/**
	 * Edit Data
	 *
	 * @time at 2019年02月27日
	 * @return mixed|string
	 */
	public function info(UserModel $userModel, UserRequest $request)
	{
		$loginUser = $this->getLoginUser();
		if (!$loginUser) {
			$data = ['code'=> 1, 'msg'=>'数据不存在', 'data'=> null];
			return json($data);
		}
		
		if ($request->isPost()) {
			$param = $request->post();
			
			if ($loginUser['id'] != $param['id']) {
				$data = ['code'=> 2, 'msg'=>'数据错误，不能更新！', 'data'=> null];
				return json($data);
			}
			
			$this->giveRoles($userModel, $param['id'], $param);
			
			if ($param['password']) {
				$param['password'] = generatePassword($param['password']);
			} else {
				unset($param['password']);
			}
			
			$userModel->updateBy($param['id'], $param) ? $data = ['code'=> 0, 'msg'=>'修改成功', 'data'=> null] : $data = ['code'=> 1, 'msg'=>'修改失败', 'data'=> null];
			return json($data);
		}

		$user = $userModel->findBy($loginUser['id']);
		$userHasRoles = $user->getRoles(false);
		$roles = Roles::all()->each(function($item, $key) use ($userHasRoles){
				$item->checked = in_array($item->id, $userHasRoles) ? true : false;
				return $item;
		});

		$this->assign([
		    'user'  => $user,
			'roles' => $roles
		]);

		return $this->fetch();
	}
	
	/**
	 * Delete Data
	 *
	 * @time at 2019年02月27日
	 * @return void
	 */
	public function delete(UserModel $userModel)
	{
		$id = $this->request->post('id');

		if (!$id) {			
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		// 删除用户相关的角色
		$userModel->detachRoles($id);
		if ($userModel->deleteBy($id)) {
			//$this->success('删除成功', url('user/index'));
			$data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			return json($data);
		}
		//$this->error('删除失败');
		$data = ['code'=> 2, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	}

	/**
	 * 分配角色
	 *
	 * @time at 2019年02月27日
	 * @param \app\model\UserModel $userModel
	 * @param int $userId
	 * @param $data
	 * @return bool
	 */
	protected function giveRoles(UserModel $userModel, int $userId, $data)
	{
		if (isset($data['roles'])) {			
			$rolesIds = array_values($data['roles']);
			if (!is_array($rolesIds)) {
				$rolesIds = [$rolesIds];
			} 
			$userModel->detachRoles($userId);
			$userModel->attachRoles($userId, $rolesIds);
			unset($data['roles']);
			return true;
		}
		
		$userModel->detachRoles($userId);
		return true;
	}
}