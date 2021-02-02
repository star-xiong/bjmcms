<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

if (!function_exists('get_goods')) 
{
	/*获取推荐商品列表
	*$category    栏目
	*$type   推荐类型: 0:全部 1:推荐 2:新品 3:精品 4:热门
	*$limit  分页记录数量
	*$iscat  是否分栏目展示
	*/
    function get_goods ($category, $type, $limit=100, $iscat=0){
		$cid = $category['id'];
		$type_arr = [1=>'istop',2=>'isnew',3=>'isbest',4=>'ishot'];
		$list = [];
		
		if($category) {
			$table_name = 'form_'.$category->model->table_name;
			$ids = model('categoryModel')->getSubCategories($cid);
			$cid_arr = explode(",", $ids);
			
			$field_str = 'b.*, a.*';
			
			if($iscat == true) {
				foreach($cid_arr as $key=>$value) {
					if($value && $cid != $value) {
						$goodsModel = model('GoodsModel')->field($field_str) 
							    ->alias('a')
								->join($table_name.' b', 'a.goods_id = b.id');
						//推荐
						if($type) {
							$goodsModel = $goodsModel->where('a.'.$type_arr[$type], 1);
						}
						
						$sub_ids = model('categoryModel')->getSubCategories($value);
						$list[$value]['data'] = $goodsModel->where('a.cid', 'IN', $sub_ids)
													->order('a.sort', 'desc')
													->order('a.clicks', 'desc')
													->order('a.id', 'desc')
													->limit($limit)
													->select();

						$list[$value]['cate'] = model('categoryModel')->get($value);
						$list[$value]['count'] = count($list[$value]['data']);
					}
				}

			}
			else {
				$goodsModel = model('GoodsModel')->field($field_str)->alias('a')
												->join($table_name.' b', 'a.goods_id = b.id');
				
				$goodsModel = $goodsModel->where('a.cid', 'IN', $ids);
				
				//推荐
				if($type) {
					$goodsModel = $goodsModel->where('a.'.$type_arr[$type], 1);
				}
				
				//排序
				$goodsModel = $goodsModel->order('a.sort', 'desc');
				$goodsModel = $goodsModel->order('a.clicks', 'desc');
				$goodsModel = $goodsModel->order('a.id', 'desc');
				
				$list['data'] = $goodsModel->with('category')->limit($limit)->select();
				$list['cate'] = $category;

				$list['count'] = count($list[$cid]['data']);
			}
			
		}
    	return $list;
    }
}

if (!function_exists('get_goods_list')) 
{

	/*获取栏目内容列表
	*@siteid 站点ID
	*@cid    栏目ID
	*@limit  分页记录数量
	*@desc   是否拉取详情
	*@params Query
	*return array
	*/
	function get_goods_list ($siteid, $cid, $limit, $params=[], $desc = false){
		
		$info = [];
		$data = [];
		$where = [];
		
		$cateInfo = model("categoryModel")->where('siteid',$siteid)
											->where('id',$cid)
											->where('model_type','goods')
											->find();
		if(empty($cateInfo)) {return ;}
		if(empty($limit)) $limit = $cateInfo['limit'];
		
		$where['status'] = 1;
		$where['siteid'] = $siteid;
		$where['cid'] = model('categoryModel')->getSubCategories($cid);		
		

		if($params['keywords']) {
			$keywords = $params['keywords'];
			unset($params['keywords']);
		}
		//品牌
		if($params['bid']) {
			$bid = intval($params['bid']);			
		}
		unset($params['bid']);
		
		//类型type
		if($params['t']) {
			$types = $params['t'];
			
		}
		unset($params['t']);
		
		//标签
		if(!empty($params['tag'])){
			$tag = $params['tag'];
		}
		unset($params['tag']);
		
		//排序sort
		if($params['sort']) {
			$sort = explode('-',$params['sort']);
			unset($params['sort']);
		}
		
		
		if($desc) {
			$goodsModel =	model('GoodsModel')->alias('a')->field('a.*');
				//->join($table_name.' b','a.goods_id = b.id');
		}
		else{
			$goodsModel =	model('GoodsModel')->alias('a')
											->field('a.*');
		}
		
		if (isset($where['status'])) {
			$goodsModel = $goodsModel->where('a.status', $where['status']);
		}
		
		if (isset($where['siteid'])) {
			$goodsModel = $goodsModel->where('a.siteid', $where['siteid']);
		}
		
		if (isset($where['cid'])) {
			$goodsModel = $goodsModel->where('a.cid', 'IN', $where['cid']);
		}
		
		if (isset($where['mid'])) {
			$goodsModel = $goodsModel->where('a.mid', $where['mid']);
		}
		
		if (!empty($bid)) {
			$goodsModel = $goodsModel->where('a.brand_id', $bid);
		}
		
		if (!empty($keywords)) {
			$goodsModel = $goodsModel->where('a.title', 'like', "%".$keywords."%");
		}
		
		if (!empty($types)) {
			$goodsModel = $goodsModel->where('a.type_id', 'IN', $types);
		}
		
		
		if(!empty($sort)) {
			$goodsModel = $goodsModel->order($sort[0], $sort[1]);
		}
		else{
			$goodsModel = $goodsModel->order("a.sort", "desc")
						->order("a.id", "desc");
		}
		$data = $goodsModel->paginate($limit, false, ['query' => request()->param()]);
		$nowdate = date('Y-m-d');
		foreach($data as $key=>$list){
			if(isset($list['update_at']) && !empty($list['update_at'])) {
				$update_at = strtotime($list['update_at']);
				$data[$key]['date_Y'] = date("Y", $update_at);
				$data[$key]['date_M'] = date("M", $update_at);
				$data[$key]['date_D'] = date("d", $update_at);
			}
			
			if(isset($list['images']) && !empty($list['images'])) {
				$data[$key]['images'] = array_filter(explode(',', $list['images']));
			}
			
			if(isset($list['pictures']) && !empty($list['pictures'])) {
				$data[$key]['pictures'] = array_filter(explode(',', $list['pictures']));
			}
			
		
			//相关商品
			if(isset($list['related']) && !empty($list['related'])) {
				$data[$key]['related_list'] = model('GoodsModel')->where('id', 'IN', $list['related'])->select();
			}

			// foreach($list->attributes as $field=>$attr){
			// 	$data[$key][$attr['attribute']['field']] = $attr['attr_value'];
			// }
		}
		
		
		$info['cate'] = $cateInfo;						//当前栏目
		$info['data'] = $data;							//商品列表
		
		return $info;

	}

}

if (!function_exists('get_goods_detail')) 
{
    //获取栏目内容
    function get_goods_detail ($id, $siteid){
		
    	$info = model('GoodsModel')->where('status', 1)
									->where('siteid', $siteid)
									->where('id', $id)
									->find();
		//DIY表内容
		$table_name = "form_".$info->model->table_name;
		$more = think\Db::name($table_name)->get($info->goods_id);
		$info['more'] = $more;
		
		//商品相册
		$info['gallery_list'] = model('GoodsGalleryModel')->getListByGoodsid($info['id']);

		//商品属性
		$info['attributes'] = model('AttributeModel')->where('type_id', $info['type_id'])->order('sort')->All();
		$info['attr_value_list'] = model('GoodsAttributeModel')->getListByGoodsid($info['id']);
		
		//商品库存
		$info['stock_list'] = model('GoodsStockModel')->getListByGoodsid($info['id']);
		
		//相关内容
		$info['related_list'] = [];
		if(isset($info['related']) && !empty($info['related'])) {
			$info['related_list'] = model('GoodsModel')->where('id', 'IN', $info['related'])->select();
		}
		
		//提取标签tag
		$tag = $info['tag'];
		if($tag){
			$tag = str_replace(' ','',$tag);
			$tag = str_replace('；',',',$tag);
			$tag = str_replace(';',',',$tag);
			$tag = str_replace('，',',',$tag);
			$info['tag_list'] = explode(',',$tag);
		}

		if(empty($info)) return;
		return $info;
    }
}

if (!function_exists('get_order_sn')) 
{
	/**
	 * 得到新订单号
	 * @return  string
	 */
	function get_order_sn()
	{
		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);

		return date('Ymd') . str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
	}
}