<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\traits;

use app\admin\service\TreeService;
use think\facade\Cache;
use think\Db;

trait GoodsTrait
{
	private function save($param, $mid) {
		Db::startTrans();
		try{

			//是否发布
			$param['status'] = switch_on_to_1($param['status']);
			
			//是否开新窗口
			$param['target'] = switch_on_to_1($param['target']);
			$param['istop'] = switch_on_to_1($param['istop']);
			$param['isnew'] = switch_on_to_1($param['isnew']);
			$param['ishot'] = switch_on_to_1($param['ishot']);
			$param['isbest'] = switch_on_to_1($param['isbest']);
			
			//货号
			if(empty($param['goods_sn'])){
				$param['goods_sn'] = generate_goods_sn(session('site'));
			}
			
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
				//$param['group'] = trim($param['group'],'，');
				$param['group'] = str_replace(' ','',$param['group']);
				$param['group'] = str_replace('；',',',$param['group']);
				$param['group'] = str_replace(';',',',$param['group']);
				$param['group'] = str_replace('，',',',$param['group']);
			}
			
			//属性
			$goods_attr = $param['goods_attr'];
			unset($param['goods_attr']);
			
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
			
			$param['goods_id'] = Db::name($table_name)->strict(false)->insertGetId($param);
			
			$result = false;
			if ($param['goods_id']) {
				$now_time = date("Y-m-d H:i:s");
				$param['created_at'] = $now_time;
				$param['update_at'] = $now_time;
				$user = session(config('permissions.user'));
				$param['uid'] = $user['id'];
				
				$param['mid'] = $mid;
				$result = model('GoodsModel')->strict(false)->insertGetId($param);
				
				if($result){
					//标签tag
					$tags = explode(',', $param['tag']);
					$goods_tags = [];
					foreach($tags as $key=>$tag){
						if(!empty($tag)) {
							$tg = [];
							$tg['goods_id'] = $result;
							$tg['tag'] = $tag;
							$goods_tags[] = $tg;
						}
						
					}
					if(!empty($goods_tags)){
						model('GoodsTagModel')->saveAll($goods_tags);
					}
					
					//群组group
					$groups = explode(',', $param['group']);
					$goods_groups = [];
					foreach($groups as $key=>$group){
						if(!empty($group)) {
							$gp = [];
							$gp['goods_id'] = $result;
							$gp['group_id'] = $group;
							$goods_groups[] = $gp;
						}
						
					}
					if(!empty($goods_groups)) {
						model('GoodsGroupModel')->saveAll($goods_groups);
					}
					
					//属性
					$this->saveAttributes($goods_attr, $result);
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
			
			$goods = model('GoodsModel')->get($id);
			
			$model = model('ModelModel')->get($goods['mid']);
			$table_name = "form_".$model['table_name'];

			//是否发布
			$param['status'] = switch_on_to_1($param['status']);			
			$param['target'] = switch_on_to_1($param['target']);
			$param['istop'] = switch_on_to_1($param['istop']);
			$param['isnew'] = switch_on_to_1($param['isnew']);
			$param['ishot'] = switch_on_to_1($param['ishot']);
			$param['isbest'] = switch_on_to_1($param['isbest']);
			
			//货号
			if(empty($param['goods_sn'])){
				$param['goods_sn'] = generate_goods_sn(session('site'));
			}			

			//标签tag
			model('GoodsTagModel')->where('goods_id', $id)->delete();
			if($param['tag']){

				$param['tag'] = rtrim($param['tag'],' ');
				$param['tag'] = trim($param['tag'],',');
				//$param['tag'] = trim($param['tag'],'，');
				$param['tag'] = str_replace(' ','',$param['tag']);
				$param['tag'] = str_replace('；',',',$param['tag']);
				$param['tag'] = str_replace(';',',',$param['tag']);
				$param['tag'] = str_replace('，',',',$param['tag']);

				$tags = explode(',', $param['tag']);

				$goods_tags = [];
				foreach($tags as $key=>$tag){
					if(!empty($tag)) {
						$tg = [];
						$tg['goods_id'] = $id;
						$tg['tag'] = $tag;
						$goods_tags[] = $tg;
					}
				}
				
				if(!empty($goods_tags)){
					model('GoodsTagModel')->saveAll($goods_tags);
				}
			}

			//群组group
			model('GoodsGroupModel')->where('goods_id', $id)->delete();
			if(!empty($param['group'])){
				$param['group'] = trim($param['group'],' ');
				$param['group'] = trim($param['group'],',');
				//$param['group'] = trim($param['group'],'，');
				$param['group'] = str_replace(' ','',$param['group']);
				$param['group'] = str_replace('；',',',$param['group']);
				$param['group'] = str_replace(';',',',$param['group']);
				$param['group'] = str_replace('，',',',$param['group']);
				
				$groups = explode(',', $param['group']);
				$goods_groups = [];
				foreach($groups as $key=>$group){
					if(!empty($group)) {
						$gp = [];
						$gp['goods_id'] = $id;
						$gp['group_id'] = $group;
						$goods_groups[] = $gp;
					}
				}

				if(!empty($goods_groups)){
					model('GoodsGroupModel')->saveAll($goods_groups);
				}
			}
			
			//保存商品属性
			$this->saveAttributes($param['goods_attr'], $id);
			unset($param['goods_attr']);
			
			//保存商品相册
			$this->saveGoodsGallery($param['goods-gallery'], $id);
			unset($param['goods-gallery']);
			
			//保存商品库存
			$number_total = $this->saveGoodsStock($param['goods_stocks'], $id);
			if($number_total != -1){
				$param['goods_number'] = $number_total;
			}
			unset($param['goods_stocks']);
			
			foreach($param as $key => $field) {
				//处理多图/图组/复选框
				if(is_array($field)){
					$param[$key] = implode(',', $field);
				}
				else{
					$param[$key] = trim($field);
				}

			}
			
			//自定义字段中checkbox字段无值提交时，设值为空
			$fields = $model->fields->where('class','checkbox')->column('field');
			foreach($fields as $field){
				if(empty($param[$field])){
					$param[$field] = '';
				}
			}
			
			//更新goods
			Db::name($table_name)->strict(false)
								->where('id', $goods['goods_id'])
								->update($param);
								
			if(empty($param['update_at'])) {
				$param['update_at'] = date("Y-m-d H:i:s");
			}

			//更新goods表
			model('GoodsModel')->strict(false)
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
			
			//是否开新窗口
			$param['target'] = switch_on_to_1($param['target']);
			$param['ishot'] = switch_on_to_1($param['ishot']);
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
			
			$list[] = $param;
			model('CategoryModel')->saveAll($list);
			
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
			$goods = model('GoodsModel')->get($id);
			
			$model = model('ModelModel')->get($goods['mid']);
			
			$table_name = "form_".$model['table_name'];
			
			$falg = $goods->delete($id);
			
			Db::name($table_name)->delete($goods['goods_id']);
			
			//删除推荐top
			// $contentTopModel = new ContentTopModel();
			// $contentTopModel->where('goods_id', $id)->delete();
			//删除标签tag
			model('GoodsTagModel')->where('goods_id', $id)->delete();
			//删除群组group
			model('GoodsGroupModel')->where('goods_id', $id)->delete();
			
			
			Db::commit();
			
			return ['code'=> 0, 'msg'=>'删除数据成功', 'data'=> $falg];
			 
		}catch( PDOException $e){
			Db::rollback();
			return ['code' => 2, 'data' => '', 'msg' => $e->getMessage()];
		}
	}
	
	/**
	 * get goods
	 *
	 * @time at 2019年03月25日
	 * @param $params
	 * @return \think\Paginator
	 */
	private function getGoodsFull($id)
	{
		$goods = model('GoodsModel')->get($id);
		$caList = model('GoodsAttributeModel')->where('goods_id', $id)->select();
		$goodsAttributes = [];
		foreach($caList as $key=>$value){
			$goodsAttributes['attr_'.$value['attr_id']] = $value['attr_value'];
		}
		$table_name = "form_".$goods->model->table_name;
		$more_info = Db::name($table_name)->get($goods['goods_id']);
		$goods['more'] = $more_info;
		$goods['attributes'] = $goodsAttributes;
		return $goods;
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
	
	/**
	 * save attributes
	 *
	 * @time at 2019年03月26日
	 * @goods_attr array 商品属性
	 * @goods_id 商品ID
	 */
	private function saveAttributes($goods_attr, $goods_id=0)
	{
		if(!empty($goods_attr)) {
			$attrData = [];
		
			foreach($goods_attr as $key=>$value) {
				foreach($value['value'] as $K=>$V){
					if(empty($V)){
						continue;
					}
					$attr = [];
					
					$attr['goods_id'] = $goods_id;
					$attr['attr_id'] = $key;
					$attr['attr_value'] = $V;
					$attr['attr_price'] = intval($value['price'][$K]);
					if($value['goods_attr_id'][$K]){
						$attr['goods_attr_id'] = $value['goods_attr_id'][$K];
					}
					$attrData[] =$attr;
				}
			}
			
			if($goods_id){
				model('GoodsAttributeModel')->where('goods_id', $goods_id)->delete();
			}
			
			if(!empty($attrData)) {
				model('GoodsAttributeModel')->saveAll($attrData);
			}
		}
	}
	
	/**
	 * save attribute images
	 *
	 * @time at 2019年03月26日
	 * @goods_attr_img array 商品属性图片
	 * @goods_id 商品ID
	 */
	private function saveGoodsGallery($goods_gallery, $goods_id=0)
	{
		if(!empty($goods_gallery)) {
			$galleryData = [];
		
			foreach($goods_gallery as $key=>$value) {
				foreach($value['url'] as $K=>$V){
					$img = [];
					$img['goods_id'] = $goods_id;
					$img['goods_attr_id'] = $key;
					$img['img_url'] = $V;
					$img['img_sort'] = intval($value['sort'][$K]);
					
					if($value['goods_gallery_id'][$K]){
						$img['goods_gallery_id'] = $value['goods_gallery_id'][$K];
					}
					
					$galleryData[] =$img;
				}
			}
			
			if($goods_id){
				model('GoodsGalleryModel')->where('goods_id', $goods_id)->delete();
			}
			
			if(!empty($galleryData)) {
				model('GoodsGalleryModel')->saveAll($galleryData);
			}
		}
	}
	
	/**
	 * save goods stock 
	 *
	 * @time at 2019年03月26日
	 * @goods_attr_img array 商品属性图片
	 * @goods_id 商品ID
	 */
	private function saveGoodsStock($goods_stocks, $goods_id=0)
	{
		$number_total = -1;
		if(!empty($goods_stocks)) {
			$stockData = [];
			
			foreach($goods_stocks['goods_number'] as $key=>$value) {
				$stockData[$key]['goods_id'] = $goods_id;
				$stockData[$key]['goods_number'] = $value;
				if($goods_stocks['goods_stock_id'][$key]){
					$stockData[$key]['goods_stock_id'] = $goods_stocks['goods_stock_id'][$key];
				}
				$stockData[$key]['goods_attr'] = '';
				foreach($goods_stocks['attr_id'] as $K=>$V) {
					if(empty($stockData[$key]['goods_attr'])) {
						$stockData[$key]['goods_attr'] = $V[$key];
					}
					else{
						$stockData[$key]['goods_attr'] .= '|'.$V[$key];
					}
					
				}
				
				if(isset($goods_stocks['attr_id']) && empty($stockData[$key]['goods_attr'])){
					unset($stockData[$key]);
				}
			}
			
			if($goods_id){
				model('GoodsStockModel')->where('goods_id', $goods_id)->delete();
			}
			
			if(!empty($stockData)) {
				model('GoodsStockModel')->saveAll($stockData);
				$number_total = array_sum(array_column($stockData, 'goods_number'));
			}
			
		}
		return $number_total;
	}
	//缓存栏目
	private function categorys_catch() {
		$treeService = new TreeService();
		
		//商品类目
		$goods_cats = model('CategoryModel')->where('siteid', session('site'))
								->where('status', 1)
								->where('model_type', 'goods')
								->order("sort","acs")
								->select();
		Cache::connect(config('cate_cache_options'))->set('goods_cats_'.session('site'), $treeService->tree($goods_cats));
	}
}
