<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\AttributeRequest;

class Attribute extends Base
{

	public function index(){
		return $this->fetch();
	}
	/**
	 * Get Attribute list
	 *
	 * @time at 2018年10月06日
	 * @return json
	 */
	public function getList()
	{
		$attributes = model('AttributeModel')->field('a.*, t.title as type_title')
								->alias('a')
								->join('types'.' t', 'a.type_id = t.id')
								->where('a.siteid', session('site'))
								->order('a.type_id')
								->order('a.sort')
								->order('a.id')
								->All();
		return json($this->layuiPaginator($attributes));
	}
	
	
	/**
	 * Create attribute
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(AttributeRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['siteid'] = session('site');
			$param['is_linked'] = switch_on_to_1($param['is_linked']);
			$param['is_image'] = switch_on_to_1($param['is_image']);
			if($param['is_image'] == 1){
				model('AttributeModel')->where('type_id', $param['type_id'])->update(['is_image'=>0]);
			}
			if (model('AttributeModel')->store($param)) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
		
		$this->assign([
			'types' => model('TypeModel')->where('siteid', session('site'))->order('sort', 'asc')->All()
		]);
		return $this->fetch();
	}
	
	/**
	 * Edit Attribute
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(AttributeRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['is_linked'] = switch_on_to_1($param['is_linked']);
			$param['is_image'] = switch_on_to_1($param['is_image']);
			if($param['is_image'] == 1){
				model('AttributeModel')->where('type_id', $param['type_id'])->update(['is_image'=>0]);
			}
			if (model('AttributeModel')->updateBy($param['id'], $param) !== false) {
				$data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			}
			return json($data);
		}

		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
	
		$this->assign([
		    'attribute'  => model('AttributeModel')->get($this->request->param('id')),
			'types' => model('TypeModel')->where('siteid', session('site'))->order('sort', 'asc')->All()
		]);

		return $this->fetch();
	}
	
	public function delete()
	{
		$attributeId = $this->request->post('id');
		
		if (!$attributeId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		//$attribute = model('AttributeModel')->get($attributeId);
		
		if (model('AttributeModel')->deleteBy($attributeId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}