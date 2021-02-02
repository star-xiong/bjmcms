<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\ModelRequest;
use think\Config;
use think\Db;

class Model extends Base
{
	public function index(){
		return $this->fetch();
	}
	
	/**
	 * Get model list
	 *
	 * @time at 2018年02月21日
	 * @return json
	 */
	public function getList()
	{
		$list = model('ModelModel')->field('m.*, t.name') 
					->alias('m')
					->join('model_types'.' t', 'm.type = t.id')
					->where('siteid',session('site'))
					->All();
		return json($this->layuiPaginator($list));
	}
	
	/**
	 * Create model
	 *
	 * @time at 2018年02月21日
	 * @return mixed|string
	 */
	public function create(ModelRequest $request)
	{
		if ($request->isPost()) {
			$data = $request->post();

			$data['siteid'] = session('site');
			$data['issystem'] = 2;		//用户模型为2
			if (model('ModelModel')->store($data)) {
				$sql = "CREATE TABLE `".config('database.prefix')."form_".$data['table_name']."` (`id` INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT)";

				if ($res = Db::execute($sql) == 'true') {
					$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> $res];
				} else {
					$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> $res];
				}
			} else {
				$data = ['code'=> 2, 'msg'=>'创建失败', 'data'=> null];
			}
		
			return json($data);
	    }

		$model_info  = $this->request->param('id') ? model('ModelModel')->get($this->request->param('id')) : 0;
		$this->assign(['_model' => $model_info]);
		$this->assign(['types' => model('ModelTypeModel')->All()]);
		return $this->fetch();
	}
	
	/**
	 * Edit model
	 *
	 * @time at 2018年02月21日
	 * @return mixed|string
	 */
	public function edit(ModelRequest $request)
	{
		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}		
		$model_info = model('ModelModel')->get($this->request->param('id'));
		
		if ($request->isPost()) {
			$param = $request->post();

			$flag = model('ModelModel')->where('id', $param['id'])
				->data($param)
				->update();

            if (false === $flag) {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> $flag];
				return json($data);
			}
	
			$table_name = config('database.prefix')."form_".$param['table_name'];
			$sql = "SHOW TABLES LIKE '".$table_name."'";
			if(!Db::query($sql)) {
				//表不存在,创建表
				$sql = "CREATE TABLE `".$table_name."` (`id` INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT)";				
				if ($res = Db::execute($sql) == 'true') {
					$data = ['code'=> 0, 'msg'=>'创建数据表成功', 'data'=> $res];
				} else {
					$data = ['code'=> 1, 'msg'=>'创建数据表失败', 'data'=> $res];
				}
			}
			elseif ($model_info['table_name'] != $param['table_name']) {
				//修改数据表名
				$sql = "ALTER TABLE `".config('database.prefix')."form_".$model_info['table_name']."` RENAME TO `".$table_name."`";
				if ($res = Db::execute($sql) == 'true') {
					$data = ['code'=> 0, 'msg'=>'修改数据表名成功', 'data'=> $res];
				} else {
					$data = ['code'=> 1, 'msg'=>'修改数据表名失败', 'data'=> $res];
				}
			} 
			else {
				$data = ['code'=> 0, 'msg'=>'保存成功', 'data'=> null];
			}
			

			return json($data);
		}

		$this->assign(['_model' => $model_info]);
		$this->assign(['types' => model('ModelTypeModel')->All()]);
		return $this->fetch();
	}
	
	public function delete()
	{
		$id = $this->request->post('id');
		if (!$id) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}

		$model = model('ModelModel')->get($id);
		
	    if ($model && model('ModelModel')->delete($id)) {
	        //删除数据表名
	        $sql = "DROP TABLE `".config('database.prefix')."form_".$model['table_name']."`";
			if ($res = Db::execute($sql) == 'true') {
				$data = ['code'=> 0, 'msg'=>'删除数据表成功', 'data'=> $res];
			} else {
				$data = ['code'=> 2, 'msg'=>'删除数据表失败', 'data'=> $res];
			}
	    } else {
			$data = ['code'=> 1, 'msg'=>'删除数据失败', 'data'=> $res];
		}
	    return json($data);
	}
}