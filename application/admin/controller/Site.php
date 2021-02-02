<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\SiteRequest;
use think\Db;

class Site extends Base
{
    public function index(){
		
		return $this->fetch();
    }
	
	/**
	 * Get Site list
	 *
	 * @time at 2019年03月12日
	 * @return json
	 */
	public function getList()
	{
		$sites = model('SiteModel')->All();
		return json($this->layuiPaginator($sites));
	}
	
	/**
	 * Create Site
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function create(SiteRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$list = [];
			$param['status'] = switch_on_to_1($param['status']);
			$param['watermark'] = switch_on_to_1($param['watermark']);
			
			//是否开启默认站
			$param['isdefault'] = switch_on_to_1($param['isdefault']);
			if ($param['isdefault'] == 1) {
				$sites = model('SiteModel')->select();				
				foreach($sites as $key => $value){
					$list[] = ['id'=>$value->id, 'isdefault'=>'0'];
				}
			}
			
			$list[] = $param;
			
			if (model('SiteModel')->saveAll($list) !== false) {
				$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
			}

			return json($data);
		}
	
		return $this->fetch();
	}
	
	/**
	 * Edit Site
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(SiteRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$list = [];
			$param['status'] = switch_on_to_1($param['status']);
			$param['watermark'] = switch_on_to_1($param['watermark']);
			//是否开启默认站
			$param['isdefault'] = switch_on_to_1($param['isdefault']);
			if ($param['isdefault'] == 1) {
				$sites = model('SiteModel')->select();				
				foreach($sites as $key => $value){
					$list[] = ['id'=>$value->id, 'isdefault'=>'0'];
				}
			}
			
			unset($param['file']);
			
			$list[] = $param;
			
			if (model('SiteModel')->saveAll($list) !== false) {
				$data = ['code'=> 0, 'msg'=>'编辑成功', 'data'=> $list];
			} else {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> null];
			}
			return json($data);
		}

		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
		$this->assign([
		    'site'  => model('SiteModel')->get($this->request->param('id'))
		]);

		return $this->fetch();
	}
	
	/**
	 * copy Site
	 *
	 * @time at 2019年10月17日
	 * @return mixed|string
	 */
	public function copy(SiteRequest $request)
	{
		$id = intval($this->request->param('id'));
		if(empty($id)) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];				
			return json($data);
		}
		
		if ($request->isPost()) {
			$data = $request->post();
			$site_obj = model('SiteModel')->get($id);
			
			if(!count($site_obj)) {
				$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
				return json($data);
			}
			
			//站点模型
			$model_obj = $site_obj->models;
			$category_arr = $site_obj->categories->toArray();
			$site_info = $site_obj->toArray();
			unset($site_info['id']);
			unset($site_info['models']);
			unset($site_info['categories']);
			$site_info['name'] .= $site_info['name'].$data['mark'];
			$site_info['mark'] = $data['mark'];

			dump($category_arr);exit();


			Db::startTrans();
			try{

				$site_id = model('SiteModel')->insertGetId($site_info);

				$field_arr = [];
				foreach($model_obj as $key=>$models){
					$model_data = [];
					$model_data['siteid'] = $site_id;
					$model_data['title'] = $models['title'];
					$model_data['table_name'] = $data['mark'].$models['table_name'];
					$model_data['description'] = $models['description'];
					$model_data['type'] = $models['type'];
					$model_data['sort'] = $models['sort'];
					$model_data['issystem'] = $models['issystem'];
					$fields = $models->fields->toArray();
					$mid = model('ModelModel')->insertGetId($model_data);
					
					foreach($fields as $K=>$Val) {
						$fields[$K]['mid'] = $mid;
						$fields[$K]['siteid'] = $site_id;
						unset($fields[$K]['id']);
					}
					$field_arr = array_merge($field_arr, $fields);					
					
				}
				$result = model('FieldModel')->saveAll($field_arr);
				
				$category_arr = [];
				foreach($category_obj as $key=>$category){
					
					
					
				}
				
				//print_r($result);
				//print_r($site_id);
				//print_r($model_data);
				//exit("KKK");
				
				
				Db::commit();
				$data = ['code'=> 1, 'msg'=>'复制站点成功', 'data'=> $model_data];
					
				return json($data);
				
				
			}catch( PDOException $e){
				Db::rollback();
				return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
			}

		}
		$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			
		return json($data);
		
	}
	
	public function delete()
	{
		$siteId = $this->request->post('id');
		
		if (!$siteId) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		
		$site = model('SiteModel')->get($siteId);
		
		if($site->status) {
			$data = ['code'=> 1, 'msg'=>'请先关闭站点，再删除！', 'data'=> null];
			return json($data);
		}
		
		if (model('CategoryModel')->where('siteid', $siteId)->count() > 0) {
			$data = ['code'=> 2, 'msg'=>'该站点有栏目，不能删除！', 'data'=> null];
			return json($data);
		}
		 
		if (model('ModelModel')->where('siteid', $siteId)->count() > 0) {
		 	$data = ['code'=> 3, 'msg'=>'该站点有内容模型，不能删除！', 'data'=> null];
		 	return json($data);
		}

		if (model('SiteModel')->deleteBy($siteId)) {
			 $data = ['code'=> 0, 'msg'=>'删除成功', 'data'=> null];
			 return json($data);
		}
		
		$data = ['code'=> 3, 'msg'=>'删除失败', 'data'=> null];
		return json($data);
	  
	}
}