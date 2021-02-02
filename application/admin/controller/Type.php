<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\TypeRequest;

class Type extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Type list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$types = model('TypeModel')->where('siteid', session('site'))->order('sort', 'asc')->All();

		return json($this->layuiPaginator($types));
	}
	
	/**
	 * Create Type
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(TypeRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['siteid'] = session('site');
			$param['istop'] = switch_on_to_1($param['istop']);
			$list[] = $param;
			
			if (model('TypeModel')->saveAll($list) !== false) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
		
		// $attributes = $attrModel->order('sort', 'asc')->select();
		
		// $this->assign([
		// 	'attrList'  => $attributes
		// ]);
		return $this->fetch();
	}
	
	/**
	 * Edit Type
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(TypeRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['istop'] = switch_on_to_1($param['istop']);
			if(empty($param['attributes']))
				$param['attributes'] = [];
			$list[] = $param;
			if (model('TypeModel')->saveAll($list) !== false) {
				$data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> $list];
			} else {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			}
			
			return json($data);
		}

		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
		//$attributes = $attrModel->order('sort', 'asc')->select();
		$type = model('TypeModel')->get($this->request->param('id'));
		
		$this->assign(['type' => $type]);

		return $this->fetch();
	}
	
	public function delete()
	{
		$typeId = $this->request->post('id');
		
		if (!$typeId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		//$type = model('TypeModel')->get($typeId);

		if (model('TypeModel')->deleteBy($typeId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}