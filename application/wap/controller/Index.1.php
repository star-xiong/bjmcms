<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\wap\controller;

use app\common\controller\Common;
use think\facade\Cookie;
use think\facade\Config;

class Index extends Common
{
	
		function __construct() 
		{
		    parent::__construct();
			parent::_initialize();
		} 
		
	    public function index()
	    {
	
			//isMobile();
			
			//模板
			$data = get_home_templete($this->current_site->id, 2);

			foreach($data as $key=>$value) {
				$this->assign([$key => $value]);
			}
		
	        return $this->fetch('/index');
	    }
		
		public function list($id) {

			$info = get_content_list ($this->current_site->id, $id, 16, true);

			//上级栏目
			$cType = get_parent_type ($this->menus, $info['cate']->pid, $id);
			
			//所有栏目
			if(empty($cType['topid']) || ($cType['topid']->id == $info['cate']->pid)) {
				$categorys = $cType;
			}else{
				$categorys = get_parent_type ($this->menus, $cType['topid']->id, $id);
			} 
			
			
			//热门
			$templetes = model('TempleteModel')->where('position_id', 11)
											->where('siteid', $this->current_site->id)
											->find();
			$mid = model('categoryModel')->where('id',$templetes['cid'])->value('mid');
			$hots = get_content_hot ($mid);
			$this->assign(['hots' => $hots]);
			
			$this->assign(['seo_title' => $info['cate']['seo_title']]);
			$this->assign(['seo_keywords' => $info['cate']['seo_keywords']]);
			$this->assign(['seo_content' => $info['cate']['seo_description']]);
			
			$this->assign(['cType' => $cType]);
			$this->assign(['cateInfo' => $info['cate']]);
			$this->assign(['data' => $info['data']]);

			return $this->fetch($info['view']);
			
		}
		
		public function show($id) {
			$info = get_content ($id, $this->current_site->id);
			
			model('contentModel')->where('id',$id)->setInc('clicks');
			
			//上级栏目
			$cType = get_parent_type ($this->menus, $info->category);
		
			$last_next = get_lastAndnext ($id, $info['cid']);
			
			//热门
			$hots = get_content_hot ($info['mid']);
			
			$this->assign(['seo_title' => $info['seo_title']]);
			$this->assign(['seo_keywords' => $info['seo_keywords']]);
			$this->assign(['seo_content' => $info['seo_description']]);
			
			$this->assign(['hots' => $hots]);
			$this->assign(['lastNext' => $last_next]);
			$this->assign(['cType' => $cType]);
			$this->assign(['cateInfo' => $info->category]);
			$this->assign(['info' => $info]);
	
			return $this->fetch($info['view']);
		}
		
	public function category($id) {
		$category = model('categoryModel')->get($id);
		
		//上级栏目
		$cType = get_parent_type ($this->menus, $category);
		
		if($category->model->type == 5) {
			//所有栏目
			$categorys = get_parent_type ($this->menus, ['pid'=>4]);
			//所有推荐内容
			$data = get_data(2, 1);
			$cate_products = [];
			foreach($data as $key=>$value) {
				$cate_products[$value['pid']][] = $value;
			}
	
		}
		
		//子栏目
		//$categorys = model('categoryModel')->where('pid', $id)->select();
	
		$this->assign(['cType' => $cType]);
		$this->assign(['cate_products' => $cate_products]);
		$this->assign(['cateInfo' => $category]);
		$this->assign(['categorys' => $categorys]);
		return $this->fetch('/category');
	}
}
