<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\common\controller\Common;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Request;
use app\traits\Auth;

class Index extends Common
{
	use Auth;
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
	} 
	
    public function index()
    {

		if(isMobile()) {
			if($this->current_site->isdefault != 1){
				$lang = '?lang='.$this->current_site->mark;
			}
			return redirect('/m.html'.$lang);
		}
		
		//模板
		$data = get_home_templete($this->current_site->id);

		foreach($data as $key=>$value) {
			$this->assign([$key => $value]);
		}
		
        return $this->fetch('/index');
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
		return $this->fetch('/category');
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
		//$positions = model('CategoryModel')->get_parents($id);
		//$catetree = get_subtree ($this->treeService->tree($this->categoryAll), $positions[0]['id']);
		
		//交易动态
		//$tradingdynamics = get_content_hot (56, 10);
		if(Request::isAjax()) {		
			$data['data'] = $info['data'];
			$data['totalPages'] = ceil($info['data']->total()/$info['cate']['limit']);
			$data['page_html'] = $info['data']->render();
			return json($data);
		}
		else{
			$this->assign(['seo_title' => $info['cate']['seo_title']]);
			$this->assign(['seo_keywords' => $info['cate']['seo_keywords']]);
			$this->assign(['seo_content' => $info['cate']['seo_description']]);
			
			$this->assign(['positions' => $positions]);
			// $this->assign(['hots' => $hots]);
			// $this->assign(['tradingdynamics' => $tradingdynamics]);
			$this->assign(['type_arr' => $type_arr]);
			$this->assign(['params' => $params]);
			$this->assign(['catetree' => $catetree]);
			$this->assign(['cateInfo' => $info['cate']]);
			$this->assign(['data' => $info['data']]);
			
			return $this->fetch($info['view']);
		}
		
		
	}
	
	public function show($id) {
		$info = get_content_detail ($id, $this->current_site->id);
		//$positions = model('CategoryModel')->get_parents($info['cid']);
		//$catetree = get_subtree ($this->treeService->tree($this->categoryAll), $positions[0]['id']);
		//历史价格
		// $prices = model("PriceModel")->where("content_id", $info['id'])
		// 							->order("year")
		// 							->order("month")
		// 							->order('day')
		// 							->select();
		// $this->assign(['prices' => $prices]);
		//更新点击量
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
	
	public function error() {
		return $this->fetch('/error');
	}
}
