<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\shop\controller;

use app\common\controller\Common;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Request;
use app\traits\Auth;
use app\traits\GoodsTrait;
use think\Validate;

class Goods extends Common
{
	use Auth, GoodsTrait;
	function __construct() 
	{
	    parent::__construct();
		parent::_initialize();
	} 
	
	
    public function catalog()
    {
        return $this->fetch('/goods_catalog');
    }

	
	/*
	* 商品列表页
	*@param $id 栏目ID;
	*@function get_goods_list ("站点ID", "栏目ID", "LIMIT", "筛选参数", "是否拉取内容详情");
	*/
	public function category($id) {
				
		$limit = 0;
		
		//参数
		$params = $this->request->get();
		$params = array_filter($params);
		unset($params['page']);
		unset($params['jump_page']);

		$type_arr = explode(',', $params['t']);
		
		$info = get_goods_list($this->current_site->id, $id, $limit, $params, true);
		
		if(Request::isAjax()) {		
			// $data['data'] = $info['data'];
			// $data['totalPages'] = ceil($info['data']->total()/$info['cate']['limit']);
			// $data['page_html'] = $info['data']->render();
			return json($data);
		}
		else{
			// $this->assign(['seo_title' => $info['cate']['seo_title']]);
			// $this->assign(['seo_keywords' => $info['cate']['seo_keywords']]);
			// $this->assign(['seo_content' => $info['cate']['seo_description']]);
			
			$this->assign(['type_arr' => $type_arr]);
			$this->assign(['params' => $params]);
			
			//当前位置
			$this->assign(['positions' => model('CategoryModel')->get_parents($info['cate']->id)]);
			$this->assign(['category' => $info['cate']]);
			$this->assign(['brands' => model('BrandModel')->where('siteid',$this->current_site->id)->All()]);
			$this->assign(['data' => $info['data']]);
			
			return $this->fetch('/goods_category');
		}
	}
	
	public function detail($id) {
		$info = get_goods_detail ($id, $this->current_site->id);
		//更新点击量
		model('GoodsModel')->where('id',$id)->setInc('clicks');

		$last_next = get_lastAndnext ($id, $info['cid']);

		$seo_title = $info['seo_title'] ? $info['seo_title'] : $info['title'].'_'.$this->current_site->name;
		$this->assign(['seo_title' => $seo_title]);
		
		$this->assign(['seo_keywords' => $info['seo_keywords']]);
		$this->assign(['seo_content' => $info['seo_description']]);
		//当前位置
		$this->assign(['positions' => model('CategoryModel')->get_parents($info['category']->id)]);
		$this->assign(['lastNext' => $last_next]);
		$this->assign(['category' => $info->category]);
		$this->assign(['info' => $info]);

		return $this->fetch('/goods_detail');
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
