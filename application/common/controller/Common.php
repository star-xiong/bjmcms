<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;
use think\Collection;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;
use app\service\TreeService;
use think\facade\Cache;
use app\traits\Auth;

class Common extends Controller
{
	use Auth;
	public $current_site;
	public $site_list;
	
	 /*
	 * 初始化操作
	 */
	public function _initialize() 
	{
		$this->rememberLogin();
		
		//前当语言
		$lang = get_current_lang();
		$this->current_site = model('SiteModel')->where('mark', $lang)->find();
		//if (!Session::get('current_site')){
			Session::set('current_site', $this->current_site);
		//}
		//Ajax或Post请求不加载
		if(Request::isGet() || (!Request::isAjax() && Request::isPost())){
			$this->assign(['current_site' => $this->current_site]);
			$cur_member = session(config('permissions.user'));
			unset($cur_member['password']);
			$this->assign(['member' => $cur_member]);
			
			//收藏夹
			if(!empty($cur_member)) {
				$myfavorites = model('MemberFavoriteModel')->where(['member_id'=>$cur_member->id])->column('content_id');
				$this->assign(['myfavorites' => $myfavorites]);
			}

			if($this->current_site->isdefault != 1){
				$this->assign(['lang' => '?lang='.$this->current_site->mark]);
			}
			
			$this->treeService = new TreeService();
			
			//导航
			if(isMobile()) {
				$navlist = ['nav_mtop', 'nav_mfooter'];
			}
			else {
				$navlist = ['nav_pctop', 'nav_pcfooter'];
			}
			
			$categoryAll = Cache::connect(Config::get('cate_cache_options'))->get('categoryAll_'.$this->current_site->id);
			if($categoryAll->isEmpty()){
				$categoryAll = model('CategoryModel')->where('siteid', $this->current_site->id)
											->with(['model','typelist','attrlist'])
											->order("sort","desc")
											->order("id","desc")
											->select();
				Cache::connect(config('cate_cache_options'))->set('categoryAll_'.$this->current_site->id, $categoryAll);
			}
			$this->categoryAll = $categoryAll;
			foreach($navlist as $nav) {
				$menus = Cache::connect(Config::get('cate_cache_options'))->get($nav.'_'.$this->current_site->id);
				if($menus->isEmpty()) {
					$menus = new Collection();
					foreach($categoryAll as $category){
						if($category[$nav] == 1){
							$menus->unshift($category);
						}
					}
					$menus = $this->treeService->tree($menus);
					Cache::connect(config('cate_cache_options'))->set($nav.'_'.$this->current_site->id, $menus);
				}
				$this->$nav = $menus;
				$this->assign([$nav => $menus]);				
			}
			//路由
			$route = Cache::connect(Config::get('cate_cache_options'))->get('route_'.$this->current_site->id);			
			
			//商品类目
			$goods_cats = Cache::connect(Config::get('cate_cache_options'))->get('goods_cats_'.$this->current_site->id);
			if($goods_cats->isEmpty() || empty($route)) {
				$goods_cats = new Collection();
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
				$goods_cats = $this->treeService->tree($goods_cats);
				Cache::connect(config('cate_cache_options'))->set('goods_cats_'.$this->current_site->id, $goods_cats);
			}
			//if (!Session::get('route')){
				Session::set('route', $route);
			//}

			$this->goods_cats = $goods_cats;
			$this->assign(['goods_cats' => $goods_cats]);

			//语言列表
			if(Config::get('lang_switch_on')) {
				$this->site_list = model('SiteModel')->where('status', 1)->select();
				$this->assign(['sites' => $this->site_list]);
			}

			//模板目录
			if(request()->module() == 'wap') {
				$this->view->config('view_path', './template/'.$this->current_site->default_style.'/wap/');
			}
			else{
				$this->view->config('view_path', './template/'.$this->current_site->default_style.'/');			
			}
			
			//友情链接
			$links = model('LinkModel')->where('siteid', $this->current_site->id)->where('status', 1)->select();
			$this->assign(['links' => $links]);
			
			$this->assign(['seo_title' => $this->current_site->seo_title]);
			$this->assign(['seo_keywords' => $this->current_site->seo_keywords]);
			$this->assign(['seo_content' => $this->current_site->seo_description]);
			
			//前端静态资源
			$this->assign(['web_index' => '/']);
			$this->assign(['web_css' => '/template/'.$this->current_site->default_style.'/skin/css']);
			$this->assign(['web_font' => '/template/'.$this->current_site->default_style.'/skin/font']);
			$this->assign(['web_uploads' => '/public/uploads/images/']);
			$this->assign(['web_images' => '/template/'.$this->current_site->default_style.'/skin/images']);
			$this->assign(['web_js' => '/template/'.$this->current_site->default_style.'/skin/js']);
			$this->assign(['web_wap' => '/template/'.$this->current_site->default_style.'/wap']);
		}
	}
	
}
