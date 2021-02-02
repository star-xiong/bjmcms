<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\TreeService;
use app\admin\request\CategoryRequest;
use app\admin\traits\ContentTrait;

class Category extends Base
{
	use ContentTrait;
	
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get category list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList(TreeService $menuService)
	{
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$catelist = model('CategoryModel')->getCatgoryByRole('');
		}
		else{
			$catelist = model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')),session('site'));
		}

		foreach($catelist as $key => $cate) {
			$catelist[$key]['model_name'] = $cate->model->title;
		}

		return json($catelist);
	}
	
	/**
	 * Create Category
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(CategoryRequest $request, TreeService $menuService)
	{
		if ($request->isPost()) {
			$param = $request->post();
			//是否显示
			$param['status'] = switch_on_to_1($param['status']);
			$param['ishot'] = switch_on_to_1($param['ishot']);
			$param['istop'] = switch_on_to_1($param['istop']);
			$param['siteid'] = session('site');
			$param['model_type'] = model('ModelModel')->get($param['mid'])->typeinfo->title;
			//栏目类型
			$types = $param['types'];
			unset($param['types']);
			
			//栏目属性
			$attributes = $param['attributes'];
			unset($param['attributes']);
			
			$list[] = $param;
			if (($re = model('CategoryModel')->saveAll($list)) !== false) {
				if($param['pid'] != 0) {
					model('CategoryModel')->updateSubIds($param['pid']);
				}

				//栏目类型
				model('CategoryTypeModel')->saveTypes($types,$re[0]['id']);
				
				//栏目属性
				model('CategoryAttributeModel')->saveAttributes($attributes, $re[0]['id']);
				
				//缓存栏目
				$this->categorys_catch();
				
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
		
		
		
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$this->categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(''));
		}
		else{
			$this->categories =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')),session('site')));
		}
		
		$_category  = $this->request->param('id') ? model('CategoryModel')->get($this->request->param('id')) : 0;
		
		$this->types = model('TypeModel')->order('sort', 'asc')->All();
		
		if($_category == 0) {
			$this->models = model('ModelModel')->where('siteid', session('site'))->select();
		}
		else{
			if($_category->model->type == 5){
				$this->models = [$_category->model];
			}
			else{
				$this->models = model('ModelModel')->where('type != 5')->where('siteid', session('site'))->select();
			}
		}
		
		$this->assign('_category', $_category);
		$this->attributes = model('AttributeModel')->order('sort', 'asc')->All();
		return $this->fetch();
	}
	
	/**
	 * Edit category
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(CategoryRequest $request, TreeService $menuService)
	{
		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
		if ($request->isPost()) {
			$param = $request->post();
			//是否显示
			$param['status'] = switch_on_to_1($param['status']);
			$param['ishot'] = switch_on_to_1($param['ishot']);
			$param['istop'] = switch_on_to_1($param['istop']);
			$param['nav_pctop'] = switch_on_to_1($param['nav_pctop']);
			$param['nav_pcfooter'] = switch_on_to_1($param['nav_pcfooter']);
			$param['nav_mtop'] = switch_on_to_1($param['nav_mtop']);
			$param['nav_mfooter'] = switch_on_to_1($param['nav_mfooter']);
			$param['model_type'] = model('ModelModel')->get($param['mid'])->typeinfo->title;
			
			unset($param['file']);
			
			//栏目类型
			$types = $param['types'];
			unset($param['types']);
			
			//栏目属性
			$attributes = $param['attributes'];
			unset($param['attributes']);

			$list[] = $param;
			if (model('CategoryModel')->saveAll($list) !== false) {
				if($param['pid'] != 0) {
					model('CategoryModel')->updateSubIds($param['pid']);
				}
				
				model('CategoryModel')->updateSubIds($param['id']);

				//栏目类型
				model('CategoryTypeModel')->saveTypes($types,$param['id'],true);
				
				//栏目属性
				model('CategoryAttributeModel')->saveAttributes($attributes, $param['id'], true);
				
				//缓存栏目
				$this->categorys_catch();
				
				$data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			}
			
			
			return json($data);
		}

		
		
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$categorie_list =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(''));
		}
		else{
			$categorie_list =  $menuService->sort(model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')),session('site')));
		}
		
		$data = model('CategoryModel')->getTypeAndAttrs ($this->request->param('id'));
		
		if($data['category']['pid'] == 0){
			$this->models = model('ModelModel')->where('siteid', session('site'))->select();
		}
		else{
			if($data['category']->model->type == 5){
				$this->models = [$data['category']->model];
			}
			else{
				$this->models = model('ModelModel')->where('type != 5')->where('siteid', session('site'))->select();
			}
		}
		
		$this->assign([
			'types' => model('TypeModel')->order('sort', 'asc')->All(),
			'attributes' => model('AttributeModel')->order('sort', 'asc')->All(),
			'type_id_arr' => $data['types'],
			'attr_id_arr' => $data['attrs'],
			'_category'  => $data['category'],
			'categories' => $categorie_list,
		]);
		
		return $this->fetch();
	}
	
	public function delete()
	{
		$categoryId = intval($this->request->post('id'));
		
		$cate = model('CategoryModel')->get($categoryId);
		
		if (!$cate) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		 
		if (model('CategoryModel')->where('pid', $categoryId)->find()) {
			$data = ['code'=> 2, 'msg'=>'请先删除子栏目', 'data'=> null];
			return json($data);
		}
		 
		// 删除权限关联的栏目信息
		model('CategoryModel')->detachRole(session('site'), $categoryId);
		
		
		if (model('CategoryModel')->deleteBy($categoryId)) {
			
			if($cate['pid']) {
				model('CategoryModel')->updateSubIds($cate['pid']);
			}
			
			//删除栏目类型
			model('CategoryTypeModel')->where('category_id', $categoryId)->delete();
			//删除栏目属性
			model('CategoryAttributeModel')->where('category_id', $categoryId)->delete();
			
			//缓存栏目
			$this->categorys_catch();
			
			$data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}

}