<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\TempleteRequest;
use app\admin\service\TreeService;

class Templete extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Templete list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$templetes = model('TempleteModel')->where('siteid', session('site'))
							->order('sort', 'asc')
							->with('position')
							->select();
		
		foreach($templetes as $key => $value) {
			$templetes[$key]['position_title'] = $value->position->title;
			$templetes[$key]['param_name'] = $value->position->param_name;
		}
		return json($this->layuiPaginator($templetes));
	}
	
	/**
	 * Create templete
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(TempleteRequest $request, TreeService $menuService)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['iscat'] = switch_on_to_1($param['iscat']);
			$param['status'] = switch_on_to_1($param['status']);
			$param['target'] = switch_on_to_1($param['target']);
			
			$now_time = date("Y-m-d H:i:s");
			$param['created_at'] = $now_time;
			$param['update_at'] = $now_time;
			$param['siteid'] = session('site');
			if (model('TempleteModel')->store($param)) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
	
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(''));
		}
		else{
			$categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')),session('site')));
		}
		
		$this->assign([
		    'positions'  => model('TempletePositionModel')->where('siteid', session('site'))->all(),
			'categories' => $categories
		]);
	
		return $this->fetch();
	}
	
	/**
	 * Edit Templete
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(TempleteRequest $request, TreeService $menuService)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['iscat'] = switch_on_to_1($param['iscat']);
			$param['status'] = switch_on_to_1($param['status']);
			$param['target'] = switch_on_to_1($param['target']);
			
			unset($param['file']);
			$param['update_at'] = date("Y-m-d H:i:s");
			
			if (model('TempleteModel')->updateBy($param['id'], $param) !== false) {
				$data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			}
			return json($data);
		}

		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(''));
		}
		else{
			$categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')),session('site')));
		}
		
		$this->assign([
			'positions'  => model('TempletePositionModel')->where('siteid', session('site'))->all(),
			'categories' => $categories,
		    'templete'  => model('TempleteModel')->get($this->request->param('id'))
		]);

		return $this->fetch();
	}
	
	public function delete()
	{
		$templeteId = $this->request->post('id');
		
		if (!$templeteId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		$templete = model('TempleteModel')->get($templeteId);
		
		if (model('TempleteModel')->deleteBy($templeteId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}