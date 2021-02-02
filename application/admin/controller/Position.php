<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\model\TempletePositionModel;
use app\admin\request\PositionRequest;

class Position extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Position list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$positions = model('TempletePositionModel')->where('siteid', session('site'))->order('sort', 'asc')->All();
		return json($this->layuiPaginator($positions));
	}
	
	/**
	 * Create Position
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(PositionRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['status'] = switch_on_to_1($param['status']);
			$param['siteid'] = session('site');
			$param['param_name'] = trim($param['param_name']);
			if (model('TempletePositionModel')->store($param)) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
	
		return $this->fetch();
	}
	
	/**
	 * Edit Position
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(PositionRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['status'] = switch_on_to_1($param['status']);
			$param['param_name'] = trim($param['param_name']);
			if (model('TempletePositionModel')->updateBy($param['id'], $param) !== false) {
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
		    'position'  => model('TempletePositionModel')->get($this->request->param('id'))
		]);

		return $this->fetch();
	}
	
	public function delete()
	{
		$positionId = $this->request->post('id');
		
		if (!$positionId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		//$position = model('TempletePositionModel')->get($positionId);
		
		if (model('TempletePositionModel')->deleteBy($positionId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}