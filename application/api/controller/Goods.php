<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\api\controller;

use think\Collection;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;
use app\service\TreeService;
use think\facade\Cache;
use app\traits\Auth;
use app\traits\GoodsTrait;
use think\Validate;

class Goods extends ApiBase
{
	use Auth, GoodsTrait;
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
	} 
	
	
    // public function catalog()
    // {
    //     return $this->fetch('/goods_catalog');
    // }
	
	/*
	* 商品分类列表
	*@param $id 栏目ID;	
	*/
	public function getCategories($id) {
		
		$categoryAll = Cache::connect(Config::get('cate_cache_options'))->get('categoryAll_'.$this->current_site->id);
		if($categoryAll->isEmpty()){
			$categoryAll = model('CategoryModel')->where('siteid', $this->current_site->id)
										->with(['model','typelist','attrlist'])
										->order("sort","desc")
										->order("id","desc")
										->select();
			Cache::connect(config('cate_cache_options'))->set('categoryAll_'.$this->current_site->id, $categoryAll);
		}
	
		//路由
		$route = Cache::connect(Config::get('cate_cache_options'))->get('route_'.$this->current_site->id);
		
		// //商品类目
		$goods_cats = Cache::connect(Config::get('cate_cache_options'))->get('goods_cats_'.$this->current_site->id);
		
		if($goods_cats->isEmpty() || empty($route)) {
			$goods_cats = new Collection();
			$treeService = new TreeService();
			$route = [];		//路由
			foreach($categoryAll as $category){
				if($category['model_type'] == 'goods'){
					$route[$category['id']] = 'category';
					$goods_cats->unshift($category);
				}
				else{
					$route[$category['id']] = $category['model_type'];
				}
			}
			Cache::connect(config('cate_cache_options'))->set('route_'.$this->current_site->id, $route);
			$goods_cats = $treeService->tree_array($goods_cats);
			Cache::connect(config('cate_cache_options'))->set('goods_cats_'.$this->current_site->id, $goods_cats);
		}
		Session::set('route', $route);
		if($id){
			$cateList = [];
			foreach($goods_cats as $key=>$value) {
				if( !$value[$value['id']]->isEmpty()){
					foreach($value[$value['id']] as $ky=>$val) {
						if(!$val[$val['id']]->isEmpty()){
							foreach($val[$val['id']] as $k=>$v) {
								if($v['pid'] == $id){
									$tempArray = [];
									$tempArray['id'] = $v['id'];
									$tempArray['pid'] = $v['pid'];
									$tempArray['title'] = $v['title'];
									$tempArray['icon'] = $v['icon'];
									
									if(!$v[$v['id']]->isEmpty()){
										foreach($v[$v['id']] as $kk=>$vv) {
											$tempArr = [];
											$tempArr['id'] = $vv['id'];
											$tempArr['pid'] = $vv['pid'];
											$tempArr['title'] = $vv['title'];
											$tempArr['icon'] = $vv['icon'];									
											$tempArray['child'][] = $tempArr;
										}
									}
									
									$cateList[] = $tempArray;
								}
							}
						}
					}
				}
			}
			return json($cateList);
		}
		else{
			$goodsCates['flist'] = [];
			$goodsCates['slist'] = [];
			$goodsCates['tlist'] = [];
			foreach($goods_cats as $key=>$value) {
				if(!$value[$value['id']]->isEmpty()){
					foreach($value[$value['id']] as $ky=>$val) {					
						$tempArray = [];
						$tempArray['id'] = $val['id'];
						$tempArray['pid'] = $val['pid'];
						$tempArray['title'] = $val['title'];
						$tempArray['icon'] = $val['icon'];
						$goodsCates['flist'][] = $tempArray;
						if(!$val[$val['id']]->isEmpty()){
							foreach($val[$val['id']] as $k=>$v) {
								$tempArray = [];
								$tempArray['id'] = $v['id'];
								$tempArray['pid'] = $v['pid'];
								$tempArray['title'] = $v['title'];
								$tempArray['icon'] = $v['icon'];
								$goodsCates['slist'][] = $tempArray;
								if(!$v[$v['id']]->isEmpty()){
									foreach($v[$v['id']] as $kk=>$vv) {
										$tempArray = [];
										$tempArray['id'] = $vv['id'];
										$tempArray['pid'] = $vv['pid'];
										$tempArray['title'] = $vv['title'];
										$tempArray['icon'] = $vv['icon'];									
										$goodsCates['tlist'][] = $tempArray;
									}
								}
							}
						}
					}
				}
			}
			return json($goodsCates);
		}
		
	}
	
	
	/*
	* 商品列表页
	*@param $id 栏目ID;
	*@function get_goods_list ("站点ID", "栏目ID", "LIMIT", "筛选参数", "是否拉取内容详情");
	*/
	public function goodsList($id) {
				
		$limit = 0;
		
		//参数
		$params = $this->request->get();
		$params = array_filter($params);
		unset($params['page']);
		unset($params['jump_page']);

		$type_arr = explode(',', $params['t']);
		
		$info = get_goods_list($this->current_site->id, $id, $limit, $params, true);

		// $data['data'] = $info['data'];
		// $data['totalPages'] = ceil($info['data']->total()/$info['cate']['limit']);
		// $data['page_html'] = $info['data']->render();
		return json($info);
	}
	
	public function detail($id) {
		$info = get_goods_detail ($id, $this->current_site->id);
		//print_r($info);
		$goodsInfo = [];
		$goodsInfo['id'] = $info['id'];
		$goodsInfo['title'] = $info['title'];
		$goodsInfo['goods_number'] = $info['goods_number'];
		$goodsInfo['market_price'] = $info['market_price'];
		$goodsInfo['goods_price'] = $info['goods_price'];
		$goodsInfo['goods_sn'] = $info['goods_sn'];
		$goodsInfo['goods_weight'] = $info['goods_weight'];
		$goodsInfo['image'] = $info['image'];
		$goodsInfo['pic'] = $info['pic'];
		$goodsInfo['clicks'] = $info['clicks'];
		$goodsInfo['sales'] = $info['sales'];
		$goodsInfo['desc'] = $info['more']['content'];
		
		if(!empty($info['gallery_list'])){
			foreach($info['gallery_list'] as $key=>$list){
				foreach($list as $k=>$img){
					$goodsInfo['imgList'][]['url'] = $img['img_url'];
				}
			}
		}
		
		
		if(!empty($info['attributes'])){
			foreach($info['attributes'] as $key=>$spec_attr){
				if(!empty($info['attr_value_list'][$spec_attr['id']])){
					$specList = [];
					$specList['id'] = $spec_attr['id'];
					$specList['name'] = $spec_attr['title'];
					$specList['class'] = $spec_attr['class'];
					$specList['is_image'] = $spec_attr['is_image'];
					$goodsInfo['specList'][] = $specList;
				}
			}
		}
		
		if(!empty($info['attr_value_list'])){
			foreach($info['attr_value_list'] as $key=>$item){
				foreach($item as $K=>$attr){
					$specChildList = [];
					$specChildList['id'] = $attr['goods_attr_id'];
					$specChildList['pid'] = $attr['attr_id'];
					$specChildList['name'] = $attr['attr_value'];
					if(!empty($info['gallery_list'][$attr['goods_attr_id']][0]['img_url'])){
						$specChildList['images'] = $info['gallery_list'][$attr['goods_attr_id']][0]['img_url'];
					}
					
					$goodsInfo['specChildList'][] = $specChildList;
				}
			
			}
			
		}
//print_r($goodsInfo);
		
	//return json($goodsInfo);	
		
		//更新点击量
		model('GoodsModel')->where('id',$id)->setInc('clicks');
		
		return json($goodsInfo);
		
		//$last_next = get_lastAndnext ($id, $info['cid']);

		// $seo_title = $info['seo_title'] ? $info['seo_title'] : $info['title'].'_'.$this->current_site->name;
		// $this->assign(['seo_title' => $seo_title]);
		
		// $this->assign(['seo_keywords' => $info['seo_keywords']]);
		// $this->assign(['seo_content' => $info['seo_description']]);
		// //当前位置
		// $this->assign(['positions' => model('CategoryModel')->get_parents($info['category']->id)]);
		// $this->assign(['lastNext' => $last_next]);
		// $this->assign(['category' => $info->category]);
		// $this->assign(['info' => $info]);

		// return $this->fetch('/goods_detail');
	}
	
	public function getstocks() {
		$params = [];
		$params['goods_id'] = $this->request->post('gid');
		$params['goods_attr'] = $this->request->post('goods_attr');
		
		$validate = new Validate($this->rule(),$this->msg());
		if (!$validate->check($params)) {
			return json(['code' => 0, 'msg' => $validate->getError()]);
		}

		$params['goods_attr'] = implode('|', $params['goods_attr']);
		$data = model('GoodsStockModel')->where('goods_id', $params['goods_id'])
								->where('goods_attr', $params['goods_attr'])
								->find();
		return json(['code' => 1, 'data' => $data]);
	}
	
	public function getgoodsattr() {
		$params = $this->request->post();
		$rule = [
			'number|商品数量' => 'require|number',
			'goods_id|商品id'  =>  'require|number',
			'goods_price|商品售价'	=> 'require|float',
			'spec_attr_type|商品规格id'  =>  'array',
		];
		$msg = [
				'number.require' => 'number require',
				'number.number' => 'number isnumber',
				'goods_id.require'  => lang('goods_id require'),
				'spec_attr_type.require'  => lang('goods_attr require'),
			];
			
		$validate = new Validate($rule,$msg);
		if (!$validate->check($params)) {
			return json(['code' => 1, 'msg' => $validate->getError()]);
		}

		$goods = $this->getSelectedAttributes($params);
		return json(['code' => 0, 'data' => $goods]);
	}

}
