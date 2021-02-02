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

class Home extends ApiBase
{
	use Auth;
	
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
	} 
	
    public function index()
    {
		//模板
		$data = get_home_templete($this->current_site->id, 2);
		$goods_list = get_goods_list ($this->current_site->id, 2, 8);
		$data['goodslist'] = $goods_list;
		return json(['status'=> 200, 'msg'=>'请求成功','data'=>$data]);
    }
	
		

	//栏目封面页--栏目首页	
	public function category($id) {		
		$category = model('categoryModel')->get($id);
	
		//上级栏目
		$cType = get_parent_type ($this->menus, $category->pid, $id);
		
		//所有栏目
		if(empty($cType['topid']) || ($cType['topid']->id == $category->pid)) {
			$categorys = $cType;
		}else{
			$categorys = get_parent_type ($this->menus, $cType['topid']->id, $id);
		} 
		
		//所有推荐内容
		if(!empty($cType['topid'])) {
			$type = 1;			//推荐内容
			$data = get_data ($cType['topid']->id, $type);
			$cate_products = [];
			foreach($data as $key=>$value) {
				$cate_products[$value['pid']][] = $value;
			}
		
		}
		
		$this->assign(['positions' => model('CategoryModel')->get_parents($info['category']->id)]);
		$this->assign(['cType' => $cType]);
		$this->assign(['cate_products' => $cate_products]);
		$this->assign(['cateInfo' => $category]);
		$this->assign(['categorys' => $categorys]);
	}
	
	/*
	*内容列表
	*@param $id 栏目ID;
	*@function get_content_list ("站点ID", "栏目ID", "LIMIT", "筛选参数", "是否拉取内容详情");
	*/
	public function list($id) {		
		//参数
		$params = $this->request->get();
		$params = array_filter($params);
		unset($params['id']);
		unset($params['page']);
		unset($params['jump_page']);
		
		//优先指定价筛选
		if($params['min_price'] || $params['max_price']) {
			unset($params['attr_price_1']);
			unset($params['attr_price_2']);
			unset($params['attr_price_3']);
		}
		$type_arr = explode(',', $params['t']);
		
		$limit = 0;
		$info = get_content_list($this->current_site->id, $id, $limit, $params, true);
		$positions = model('CategoryModel')->get_parents($id);
		$catetree = get_subtree ($this->treeService->tree($this->categoryAll), $positions[0]['id']);
		
		$data['data'] = $info['data'];
		$data['totalPages'] = ceil($info['data']->total()/$info['cate']['limit']);
		$data['page_html'] = $info['data']->render();
		return json($data);
	}
	
	public function show($id) {
		$info = get_content_detail ($id, $this->current_site->id);
		$positions = model('CategoryModel')->get_parents($info['cid']);
		$catetree = get_subtree ($this->treeService->tree($this->categoryAll), $positions[0]['id']);

		model('contentModel')->where('id',$id)->setInc('clicks');


		$last_next = get_lastAndnext ($id, $info['cid']);
		

		//热门
		//$templetes = model('TempleteModel')->get(34);
		$hots = get_content_hot ($info->category->id, 8);
		
		$seo_title = $info['seo_title'] ? $info['seo_title'] : $info['title'].'_'.$this->current_site->name;
		$this->assign(['seo_title' => $seo_title]);		
		$this->assign(['seo_keywords' => $info['seo_keywords']]);
		$this->assign(['seo_content' => $info['seo_description']]);
		
		$this->assign(['positions' => $positions]);
		$this->assign(['catetree' => $catetree]);
		$this->assign(['lastNext' => $last_next]);
		$this->assign(['cateInfo' => $info->category]);
		$this->assign(['info' => $info]);

		return $this->fetch($info['view']);
	}
	
	public function search() {
		
		$params = [];
		$params['status'] = 1;
		//$params['mid'] = 3;
		$params['keywords'] = trim($this->request->param('keywords'));
		//$params['cid'] = intval($this->request->param('id'));
		$params['siteid'] = $this->current_site->id;
		$list = model('contentModel')->getList($params, 12);
		//所有栏目
		//$categorys = get_parent_type ($this->menus, 4, 0);
		//$this->assign(['categorys' => $categorys]);
		$this->assign(['data' => $list]);
		$this->assign(['params' => $params]);
		return $this->fetch('/search');
	}
	

}
