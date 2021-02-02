<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

//use think\Request;
class Member extends Base
{
	/**
	 * User Admin 管理首页
	 *
	 * @time at 2019年02月27日
	 * @return mixed|string
	 */
	public function index()
	{
		return $this->fetch();
	}

	/**
	 * User List
	 *
	 * @time at 2019年02月28日
	 * @return json
	 */
	public function getList()
	{
		$params = $this->request->param();
		$this->checkParams($params);
		$list = model("MemberModel")->getList($params, 50);
		return json($this->layuiPaginator($list));
	}
	


	/**
	 * Delete Data
	 *
	 * @time at 2019年02月27日
	 * @return void
	 */
	public function delete()
	{
		$id = $this->request->post('id');

		if (!$id) {			
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		$member = model("MemberModel")->get($id);
		if ($member->delete($id)) {
			$data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			return json($data);
		}
		//$this->error('删除失败');
		$data = ['code'=> 2, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	}

}