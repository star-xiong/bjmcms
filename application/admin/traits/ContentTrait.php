<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\traits;

use app\admin\service\TreeService;
use think\Collection;
use think\facade\Cache;
use think\Db;

trait ContentTrait
{
	private function save($param, $mid) {
		Db::startTrans();
		try{

			//是否发布
			$param['status'] = switch_on_to_1($param['status']);
			$param['istop'] = switch_on_to_1($param['istop']);
			
			//标签tag
			if($param['tag']){
				$param['tag'] = trim($param['tag'],' ');
				$param['tag'] = trim($param['tag'],',');
				$param['tag'] = str_replace(' ','',$param['tag']);
				$param['tag'] = str_replace('；',',',$param['tag']);
				$param['tag'] = str_replace(';',',',$param['tag']);
				$param['tag'] = str_replace('，',',',$param['tag']);				
			}
			
			//群组group
			if($param['group']){
				$param['group'] = trim($param['group'],' ');
				$param['group'] = trim($param['group'],',');
				$param['group'] = str_replace(' ','',$param['group']);
				$param['group'] = str_replace('；',',',$param['group']);
				$param['group'] = str_replace(';',',',$param['group']);
				$param['group'] = str_replace('，',',',$param['group']);
			}

			//处理多图/图组/复选框
			foreach($param as $key => $field) {
				if (is_array($field)) {
					$param[$key] = implode(',', $field);
				}else{
					$param[$key] = trim($field);
				}
			}
			
			$param['siteid'] = session('site');
			
			$tableName = model('ModelModel')->get($mid);
			$table_name = "form_".$tableName['table_name'];
			
			$param['content_id'] = Db::name($table_name)->strict(false)->insertGetId($param);
			
			$result = false;
			if ($param['content_id']) {
				$now_time = date("Y-m-d H:i:s");
				$param['created_at'] = $now_time;
				$param['update_at'] = $now_time;
				$user = session(config('permissions.user'));
				$param['uid'] = $user['id'];
				
				$param['mid'] = $mid;
				$result = model('ContentModel')->strict(false)->insertGetId($param);
				
				if($result){
					//推荐
					$tops = explode(',', $param['tops']);
					if(is_array($tops)) {
						$content_tops = [];
						foreach($tops as $key=>$top){
							if(!empty($top)) {
								$ct = [];
								$ct['content_id'] = $result;
								$ct['top_id'] = $top;
								$content_tops[] = $ct;
							}
							
						}
						if(!empty($content_tops)){
							model('ContentTopModel')->saveAll($content_tops);
						}
					}

					//标签tag
					$tags = explode(',', $param['tag']);
					$content_tags = [];
					foreach($tags as $key=>$tag){
						if(!empty($tag)) {
							$tg = [];
							$tg['content_id'] = $result;
							$tg['tag'] = $tag;
							$content_tags[] = $tg;
						}
						
					}
					if(!empty($content_tags)){
						model('ContentTagModel')->saveAll($content_tags);
					}
					
					//群组group
					$groups = explode(',', $param['group']);
					$content_groups = [];
					foreach($groups as $key=>$group){
						if(!empty($group)) {
							$gp = [];
							$gp['content_id'] = $result;
							$gp['group_id'] = $group;
							$content_groups[] = $gp;
						}
						
					}
					if(!empty($content_groups)) {
						model('ContentGroupModel')->saveAll($content_groups);
					}
					
					//属性
					$list = model('AttributeModel')->All();
					$attributes = [];
					foreach($list as $K=>$V){
						$attributes['attr_'.$V['id']] = $V['id'];
					}
					$attrDate = [];
					foreach($param as $key => $field) {
						if(substr($key, 0, 5) === 'attr_') {
							$attr = [];
							$attr['content_id'] = $result;
							$attr['attr_id'] = $attributes[$key];
							$attr['attr_value'] = trim($field);
							$attrDate[] = $attr;
						}
					}
					if(!empty($attrDate)) {
						$flag = model('ContentAttributeModel')->saveAll($attrDate);
					}
				}
			}
			
			Db::commit();
			
			if(false === $result){
				 Db::rollback();
				return ['code' => 1, 'data' => '', 'msg' => '添加内容失败'];
			}else{
				return ['code' => 0, 'data' => $result, 'msg' => '添加内容成功'];
			}
			
		}catch( PDOException $e){
			Db::rollback();
			return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
		}
	}
	
	private function update($param) {
		
		Db::startTrans();
		try{
			$id = intval($param['id']);
			unset($param['id']);
			
			$content = model('ContentModel')->get($id);
			$model = model('ModelModel')->get($content['mid']);
			$table_name = "form_".$model['table_name'];

			//是否发布
			$param['status'] = switch_on_to_1($param['status']);
			$param['istop'] = switch_on_to_1($param['istop']);
			
			//推荐
			model('ContentTopModel')->where('content_id', $id)->delete();
			if(is_array($param['tops'])){
				$content_tops = [];
				foreach($param['tops'] as $key=>$top){
					if(!empty($top)) {
						$ct = [];
						$ct['content_id'] = $id;
						$ct['top_id'] = $top;
						$content_tops[] = $ct;
					}
					
				}
				if(!empty($content_tops)){
					model('ContentTopModel')->saveAll($content_tops);
				}
			}
			
			//标签tag
			model('ContentTagModel')->where('content_id', $id)->delete();
			if($param['tag']){

				$param['tag'] = rtrim($param['tag'],' ');
				$param['tag'] = trim($param['tag'],',');
				//$param['tag'] = trim($param['tag'],'，');
				$param['tag'] = str_replace(' ','',$param['tag']);
				$param['tag'] = str_replace('；',',',$param['tag']);
				$param['tag'] = str_replace(';',',',$param['tag']);
				$param['tag'] = str_replace('，',',',$param['tag']);

				$tags = explode(',', $param['tag']);

				$content_tags = [];
				foreach($tags as $key=>$tag){
					if(!empty($tag)) {
						$tg = [];
						$tg['content_id'] = $id;
						$tg['tag'] = $tag;
						$content_tags[] = $tg;
					}
				}
				
				if(!empty($content_tags)){
					model('ContentTagModel')->saveAll($content_tags);
				}
			}

			//群组group
			model('ContentGroupModel')->where('content_id', $id)->delete();
			if(!empty($param['group'])){
				$param['group'] = trim($param['group'],' ');
				$param['group'] = trim($param['group'],',');
				//$param['group'] = trim($param['group'],'，');
				$param['group'] = str_replace(' ','',$param['group']);
				$param['group'] = str_replace('；',',',$param['group']);
				$param['group'] = str_replace(';',',',$param['group']);
				$param['group'] = str_replace('，',',',$param['group']);
				
				$groups = explode(',', $param['group']);
				$content_groups = [];
				foreach($groups as $key=>$group){
					if(!empty($group)) {
						$gp = [];
						$gp['content_id'] = $id;
						$gp['group_id'] = $group;
						$content_groups[] = $gp;
					}
				}

				if(!empty($content_groups)){
					model('ContentGroupModel')->saveAll($content_groups);
				}
			}

			//属性
			$list = model('AttributeModel')->All();
			$attributes = [];
			foreach($list as $K=>$V){
				$attributes['attr_'.$V['id']] = $V['id'];
			}
			
			$attrDate = [];
			foreach($param as $key => $field) {
				//处理多图/图组/复选框
				if(is_array($field)){
					$param[$key] = implode(',', $field);
				}
				else{
					$param[$key] = trim($field);
				}
				
				if(substr($key, 0, 5) === 'attr_') {					
					$attr = [];
					$attr['content_id'] = $id;
					$attr['attr_id'] = $attributes[$key];
					$attr['attr_value'] = $param[$key];
					$attrDate[] =$attr;
					unset($param[$key]);
				}
			}
						
			model('ContentAttributeModel')->where('content_id', $id)->delete();
			if(!empty($attrDate)) {
				model('ContentAttributeModel')->saveAll($attrDate);
			}
			
			//自定义字段中checkbox字段无值提交时，设值为空
			$fields = $model->fields->where('class','checkbox')->column('field');
			foreach($fields as $field){
				if(empty($param[$field])){
					$param[$field] = '';
				}
			} 
			
			//更新content			
			Db::name($table_name)->strict(false)
								->where('id', $content['content_id'])
								->update($param);
								
			if(empty($param['update_at'])) {
				$param['update_at'] = date("Y-m-d H:i:s");
			}

			//更新content表
			model('ContentModel')->strict(false)
						->where('id', $id)
						->update($param);
			Db::commit();
			return ['code' => 0, 'data' => '', 'msg' => '内容更新成功'];
			
		}catch( PDOException $e){
			Db::rollback();
			return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
		}
	}
	
	/**
	 * update category and page
	 *
	 * @time at 2019年03月26日
	 * @param $params
	 * @return \think\Paginator
	 */
	private function updateCatAndPage($param) {
		
		Db::startTrans();
		try{
			$model = model('ModelModel')->get($param['mid']);			
			$table_name = "form_".$model['table_name'];
			
			//是否显示
			$param['status'] = switch_on_to_1($param['status']);
			
			$param['istop'] = switch_on_to_1($param['istop']);
			
			//是否开新窗口
			$param['target'] = switch_on_to_1($param['target']);
			
			//导航显示
			$param['nav_pctop'] = switch_on_to_1($param['nav_pctop']);
			$param['nav_pcfooter'] = switch_on_to_1($param['nav_pcfooter']);
			$param['nav_mtop'] = switch_on_to_1($param['nav_mtop']);
			$param['nav_mfooter'] = switch_on_to_1($param['nav_mfooter']);
			
			foreach($param as $key => $field) {
				if (is_array($field)) {
					$param[$key] = implode(',', $field);
				}
				
			}
			
			//单页面
			if($model['type'] == 2) {
				$id = $param['id'];
				
				unset($param['id']);
				
				if($param['page_id']) {
					Db::name($table_name)->strict(false)
										->where('id', $param['page_id'])
										->update($param);
				} else {				
					$param['page_id'] = Db::name($table_name)->strict(false)->insertGetId($param);
					
				}
				$param['id'] = $id;
			}
			
			$param['types'] = explode(',', $param['types']);
			$param['attributes'] = explode(',', $param['attributes']);
	
			//栏目类型
			$types = $param['types'];
			unset($param['types']);
			
			//栏目属性
			$attributes = $param['attributes'];
			unset($param['attributes']);

			$list[] = $param;
			model('CategoryModel')->saveAll($list);
			
			//栏目类型
			model('CategoryTypeModel')->saveTypes($types,$param['id'],true);
			
			//栏目属性
			model('CategoryAttributeModel')->saveAttributes($attributes, $param['id'], true);
			
			Db::commit();
			
			//缓存栏目
			$this->categorys_catch();
			
			return ['code' => 0, 'data' => '', 'msg' => '内容更新成功'];
			
		}catch( PDOException $e){
			Db::rollback();
			return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
		}
	}
	
	private function del($id)
	{
		Db::startTrans();
		try{
			$content = model('ContentModel')->get($id);
			$model = model('ModelModel')->get($content['mid']);
			
			$table_name = "form_".$model['table_name'];
			
			$falg = $content->delete($id);
			
			Db::name($table_name)->delete($content['content_id']);
			
			//删除推荐top
			model('ContentTopModel')->where('content_id', $id)->delete();
			//删除标签tag
			model('ContentTagModel')->where('content_id', $id)->delete();
			//删除群组group
			model('ContentGroupModel')->where('content_id', $id)->delete();
			
			
			Db::commit();
			
			return ['code'=> 0, 'msg'=>'删除数据成功', 'data'=> $falg];
			 
		}catch( PDOException $e){
			Db::rollback();
			return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
		}
	}
	
	/**
	 * get content
	 *
	 * @time at 2019年03月25日
	 * @param $params
	 * @return \think\Paginator
	 */
	private function getContentFull($id)
	{
		$content = model('ContentModel')->get($id);
		$caList = model('ContentAttributeModel')->where('content_id', $id)->select();
		$contentAttributes = [];
		foreach($caList as $key=>$value){
			$contentAttributes['attr_'.$value['attr_id']] = $value['attr_value'];
		}
		$table_name = "form_".$content->model->table_name;
		$more_info = Db::name($table_name)->get($content['content_id']);
		$content['more'] = $more_info;
		$content['attributes'] = $contentAttributes;
		return $content;
	}
	
	/**
	 * get page
	 *
	 * @time at 2019年03月26日
	 * @param $params
	 * @return \think\Paginator
	 */
	private function getPage($mid, $page_id)
	{		
		$model = model('ModelModel')->get($mid);
		
		$table_name = "form_".$model['table_name'];
		
		$page = Db::name($table_name)->get($page_id);

		return $page;
	}
	
	//缓存栏目
	private function categorys_catch() {
		$treeService = new TreeService();
		$navlist = ['nav_pctop', 'nav_pcfooter', 'nav_mtop', 'nav_mfooter'];
		$categoryAll = model('CategoryModel')->where('siteid', session('site'))
									->with(['model','typelist','attrlist'])
									->order("sort","desc")
									->order("id","desc")
									->select();
		Cache::connect(config('cate_cache_options'))->set('categoryAll_'.session('site'), $categoryAll);
		foreach($navlist as $nav) {
			$collection = new Collection();
			foreach($categoryAll as $category){
				if($category[$nav] == 1){
					$collection->unshift($category);
				}
			}
			Cache::connect(config('cate_cache_options'))->set($nav.'_'.session('site'), $treeService->tree($collection));
		}
		//商品类目
		$collection = new Collection();
		$route = [];		//路由
		foreach($categoryAll as $key=>$category){
			if($category['model_type'] == 'goods'){
				$route[$category['id']] = 'category';
				$collection->unshift($category);
			}
			else{
				$route[$category['id']] = $category['model_type'];
			}
		}
		Cache::connect(config('cate_cache_options'))->set('route_'.session('site'), $route);
		Cache::connect(config('cate_cache_options'))->set('goods_cats_'.session('site'), $treeService->tree($collection));
	}
}
