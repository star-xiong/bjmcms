<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// | 会员等级
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\MemberRankRequest;

class Rank extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Rank list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$ranks = model('MemberRankModel')->All();

		return json($this->layuiPaginator($ranks));
	}
	
	/**
	 * Create Rank
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(MemberRankRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['show_price'] = switch_on_to_1($param['show_price']);
			$param['special_rank'] = switch_on_to_1($param['special_rank']);
			$now_date = date("Y-m-d H:i:s");
			$param['created_at'] = $now_date;
			if (model('MemberRankModel')->store($param)) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
	
		return $this->fetch();
	}
	
	/**
	 * Edit Rank
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(MemberRankRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['show_price'] = switch_on_to_1($param['show_price']);
			$param['special_rank'] = switch_on_to_1($param['special_rank']);
			unset($param['file']);
			
			if (model('MemberRankModel')->updateBy($param['id'], $param) !== false) {
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
		    'rank'  => model('MemberRankModel')->get($this->request->param('id'))
		]);

		return $this->fetch();
	}
	
	public function delete()
	{
		$rankId = $this->request->post('id');
		
		if (!$rankId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		if (model('MemberRankModel')->deleteBy($rankId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}