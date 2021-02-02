<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

// 关闭所有PHP错误报告
error_reporting(0);

include_once "function.php";
include_once "shop.php";
// 应用公共文件

if (!function_exists('buildurl')) {
    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function buildurl($url = '', $vars = '', $suffix = true, $domain = true)
    {
		$route = Session::get('route');
		$site = Session::get('current_site');
		// 解析参数
		if (is_string($vars)) {
		    // aaa=1&bbb=2 转换成数组
		    parse_str($vars, $vars);
			foreach($vars as $key=>$value){
				$$key = $value;
			}
		}
		
		if($id && (substr($id, 0 , 4) === 'http')){
			return $id;
		} 

		if($url == 'list'){
			$url = $route[$id];
		}
		
		return Url::build($url, $vars, $suffix, $domain);

    }
}

if (!function_exists('get_default_lang')) 
{
    /**
     * 获取默认语言
     */
    function get_default_lang()
    {
		$default_lang = model('SiteModel')->where('isdefault', 1)->value('mark');
        return $default_lang;
    }
}

if (!function_exists('get_current_lang')) 
{
    /**
     * 获取当前语言
     */
    function get_current_lang()
    {
        $keys = Config::get('home_lang_key');
    	//$home_lang = Cookie::get($keys);
        //if (empty($home_lang)) {
            $home_lang = Request::param('lang');
            if (empty($home_lang)) {
                $home_lang = get_default_lang();
            }
    		Cookie::set($keys,$home_lang);
        //}
        return $home_lang;
    }
}

if (!function_exists('get_home_templete')) 
{
    /**
     * 获取模板数据
	 * @class    模板类型，1电脑模板，2手机模板,默认为电脑模板
     */
    function get_home_templete($siteid, $class=1)
    {
		$class = '3,'.$class;
		$positions = model('TempletePositionModel')
						->where('siteid', $siteid)
						->where('class', 'IN', $class)
						->where('status', 1)
						->order('sort')
						->select();

		$home_data = [];
		foreach($positions as $key => $position) {

			if(count($position->templetes) > 0) {
				$data = [];
		
				foreach($position->templetes as $K => $V) {
					$templete = $V;
					break;
				}
				
				$data['temp'] = $templete;
				$data['position'] = $position;
				switch ($position->type) {
					case 1:			//调用模板
						$data['list'] = $position->templetes()->where('status',1)->order('sort')->select();
					break;
					
					case 2:			//调用栏目列表
						$data['cid'] = $templete->cid;
						
						$data['list'] = model('categoryModel')
										->where('pid', $templete->cid)
										->order('sort', 'asc')
										->select();
					break;
					
					case 3:			//调用单页
						
						$data['list'] = model('categoryModel')
										->where('pid', $templete->cid)
										->order('sort', 'asc')
										->select();
						
						$data['data'] = get_page($templete->cid);
					break;
					
					case 4:			//调用内容列表:文章/商品/案例/下载/图组
						$data['cid'] = $templete->cid;
						$data['limit'] = $templete->limit;
						$list = get_data($templete->cid, $templete->type, $templete->limit, $templete->iscat);
				
						$data['list'] = $list;
					break;
				}

				$home_data[$position->param_name] = $data;
			}
			
		}
		
        return $home_data;
    }
}

if (!function_exists('get_page')) 
{
    //获取单页内容
    function get_page ($cid){
    
    	$cat = model('categoryModel')->get($cid);
    	if($cat) {
			$pageTable = "form_".$cat->model->table_name;
			$cat['page'] = think\Db::name($pageTable)->get($cat->page_id);
		}
    	
    	return $cat;
    }
}

if (!function_exists('get_page_list_bypid')) 
{
    //获取单页栏目下的单面内容列表
    function get_page_list_bypid ($pid){
    
    	$cat = model('categoryModel')->get($pid);
		$page_list = [];
    	if($cat) {
			$pageTable = "form_".$cat->model->table_name;
		
			$page_list = model('categoryModel')->field('a.id as cid, a.title, a.subtitle, a.pid, a.thumpic, a.adpic, a.status, b.*') 
					->alias('a')
					->join($pageTable.' b', 'a.page_id = b.id')
					->where('a.pid', $pid)
					->select();
		}
    	return $page_list;
    }
}

if (!function_exists('get_data')) 
{
	/*获取栏目列表及内容列表
	*$cid    栏目ID
	*$type   推荐类型: 0:全部 1:推荐 2:新品 3:精品 4:热门
	*$limit  分页记录数量
	*$iscat  是否分栏目展示
	*/
    function get_data ($cid, $type, $limit=100, $iscat=0){
		
		$category = model('categoryModel')->get($cid);
		
		if($category->model->type == 5){
			return get_goods ($category, $type, $limit, $iscat);
		}
		else{
			return get_contents($category, $type, $limit=100, $iscat=0);
		}
	}
		
}

if (!function_exists('get_contents')) 
{
	/*获取栏目列表及内容列表
	*$category    栏目
	*$type   推荐类型: 0:全部 1:推荐 2:新品 3:精品 4:热门
	*$limit  分页记录数量
	*$iscat  是否分栏目展示
	*/
    function get_contents ($category, $type, $limit=100, $iscat=0){
		$cid = $category['id'];
		//推荐
		/* if(!empty($type)) {
			
			if(is_array($type)) {
				$types = implode(",", $type);
			}
			else {
				$types = $type;
			}
			
			$content_ids = model('ContentTopModel')->where('top_id','IN', $types)->column('content_id');
		} */
	
		$list = [];
		
		if($category) {
			$table_name = 'form_'.$category->model->table_name;
			$ids = model('categoryModel')->getSubCategories($cid);
			$cid_arr = explode(",", $ids);
			$field_str = 'b.*, a.*';
			
			if($iscat == true) {
				foreach($cid_arr as $key=>$value) {
					if($value && $cid != $value) {
						$contentModel = model('contentModel')->field($field_str) 
								->alias('a')
								->join($table_name.' b', 'a.content_id = b.id');
						//推荐
						if($type){
							$contentModel = $contentModel->where('istop', 1);
						}
						// if($content_ids) {
						// 	$contentModel = $contentModel->where('a.id', 'IN', $content_ids);
						// }
	
						$sub_ids = model('categoryModel')->getSubCategories($value);
						$list[$value]['data'] = $contentModel->where('a.cid', 'IN', $sub_ids)
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
				$contentModel = model('contentModel')->field($field_str) 
						->alias('a')
						->join($table_name.' b', 'a.content_id = b.id');
				
				$contentModel = $contentModel->where('a.cid', 'IN', $ids);
				
				//推荐
				if($type){
					$contentModel = $contentModel->where('istop', 1);
				}
				//推荐
				// if($content_ids) {
				// 	$contentModel = $contentModel->where('a.id', 'IN', $content_ids);
				// }
	
				$contentModel = $contentModel->order('a.sort', 'desc');
				$contentModel = $contentModel->order('a.clicks', 'desc');
				$contentModel = $contentModel->order('a.id', 'desc');
	
				$list['data'] = $contentModel->with('category')->limit($limit)->select();
				$list['cate'] = $category;
				
				//推荐栏目类型
				if($category->typelist){
					foreach($category->typelist as $key => $typelist){
						if($typelist->istop) {
							$list['type'][$typelist['id']] = $typelist;
						}
					}
				}
			
				$list['count'] = count($list[$cid]['data']);
			}
			
		}
	
		return $list;
	}
		
}



if (!function_exists('get_top_list')) 
{
	/*全局推荐内容列表
	*$cid    栏目ID
	*$type   array 推荐类型: 推荐 新品 精品 热门
	*$limit  分页记录数量
	*/
    function get_top_list ($type, $limit=100){
		
		$list = [];

		//推荐
		if(!empty($type)) {
			
			if(is_array($type)) {
				$types = implode(",", $type);
			}
			else {
				$types = $type;
			}
		
			$content_ids = model('ContentTopModel')->where('top_id','IN', $types)->column('content_id');
			
			$contentModel = model('contentModel');
			
			
			//推荐
			if($content_ids) {
				$contentModel = $contentModel->where('id', 'IN', $content_ids);
				$contentModel = $contentModel->order('sort', 'desc');				
				$list = $contentModel->with('category')->limit($limit)->select();
			}
		}
    	return $list;
    }
}

if (!function_exists('get_content_list')) 
{

	/*获取栏目内容列表
	*@siteid 站点ID
	*@cid    栏目ID
	*@limit  分页记录数量
	*@desc   是否拉取详情
	*@params Query
	*return array
	*/
	function get_content_list ($siteid, $cid, $limit, $params, $desc = false){
		
		$info = [];
		$data = [];
		$where = [];
		$viewPage = '/error';
		
		$cateInfo = model("categoryModel")->where('siteid', $siteid)
											->where('id',$cid)
											->where('model_type', '<>', 'goods')
											->find();
		if(empty($limit)) $limit = $cateInfo['limit'];
		if(empty($cateInfo)) {
			return ;
		}
		$where['status'] = 1;
		$where['siteid'] = $siteid;
		$where['cid'] = model('categoryModel')->getSubCategories($cid);		
		
		/*格式化栏目
		*1、文章;2、单页面;３留言; 4、图片; 5、产品; 6、案例; 7、下载; 8、报名; 9、预约; 10、招聘
		*/
		switch ($cateInfo->model->type)
		{
			case 1:				//文章
				$viewPage = "/list_article";
				if($cateInfo->tpl_list) {
					$viewPage = '/'.$cateInfo->tpl_list;
				}
				//$data = model('contentModel')->getList($where, $limit);
				$table_name = "form_".$cateInfo->model->table_name;
				$data = get_content_list_info ($table_name,$where,$limit,$params,$desc);
				break;
			case 4:				//图片
				$viewPage = "/list_image";
				if($cateInfo->tpl_list) {
					$viewPage = '/'.$cateInfo->tpl_list;
				}
				$table_name = "form_".$cateInfo->model->table_name;
				$data = get_content_list_info ($table_name,$where,$limit,$params,$desc);
				break;
			case 5:				//产品
				$viewPage = "/list_product";
				if($cateInfo->tpl_list) {
					$viewPage = '/'.$cateInfo->tpl_list;
				}
				$table_name = "form_".$cateInfo->model->table_name;
				$data = get_content_list_info ($table_name,$where,$limit,$params,$desc);
				break;
			case 6:				//案例
				$viewPage = "/list_cast";
				if($cateInfo->tpl_list) {
					$viewPage = '/'.$cateInfo->tpl_list;
				}
				$table_name = "form_".$cateInfo->model->table_name;
				$data = get_content_list_info ($table_name,$where,$limit,$params,$desc);
				break;
			case 7:				//下载
				$viewPage = "/list_download";
				if($cateInfo->tpl_list) {
					$viewPage = '/'.$cateInfo->tpl_list;
				}
				$table_name = "form_".$cateInfo->model->table_name;
				$contentModel =	model('contentModel')->alias('a')
					->join($table_name.' b','a.content_id = b.id');
					
				if (isset($where['status'])) {
					$contentModel = $contentModel->where('status', $where['status']);
				}
				
				if (isset($where['siteid'])) {
					$contentModel = $contentModel->where('siteid', $where['siteid']);
				}
				
				if (isset($where['cid'])) {
					$contentModel = $contentModel->where('cid', 'IN', $where['cid']);
				}	
				
				$data = $contentModel->paginate($limit, false, ['query' => request()->param()]);
				break;  
			case 2:				//单页面
				$table_name = "form_".$cateInfo->model->table_name;
				$data = think\Db::name($table_name)->get($cateInfo->page_id);
				if($data['images']) {
					$data['images'] = array_filter(explode(',', $data['images']));
				}
				$viewPage = "/singlepage";
				if($cateInfo->tpl_show) {
					$viewPage = '/'.$cateInfo->tpl_show;
				}
				break;
			case 3:				//自定义表单:留言				
				$table_name = "form_".$cateInfo->model->table_name;
				$data = think\Db::name($table_name)->paginate($limit);
				$viewPage = "/guestbook";
				if($cateInfo->tpl_show) {
					$viewPage = '/'.$cateInfo->tpl_show;
				}
			break;
			case 8:				//报名
			case 9:				//预约
			case 10:			//招聘
			  //自定义表单:留言/报名
			  $table_name = "form_".$cateInfo->model->table_name;
			  $data = think\Db::name($table_name)->paginate($limit);
			  $viewPage = "/guestbook";
			  if($cateInfo->tpl_show) {
			  	$viewPage = '/'.$cateInfo->tpl_show;
			  }
			  break;
			default:	$viewPage = "/error";
		}
		
		$info['cate'] = $cateInfo;						//当前栏目
		$info['view'] = $viewPage;						//前端模板
		$info['data'] = $data;							//栏目内容列表
		
		return $info;

	}

}


if (!function_exists('get_content_id_arr')) 
{
    /*获取内容ID
    *@params   array query
    *@data return array
    */
    function get_content_id_arr ($params){

		if(empty($params)) {
			return [];
		}

		//筛选属性
		foreach($params as $field => $value) {
			if(!(substr($field, 0, 5) === 'attr_')) {
				unset($params[$field]);
			}
		}

		$fields = implode(',',array_keys($params));
		$attr_list = model('AttributeModel')->where('field','IN',$fields)->column('id','field');;
		
		$attr_where = [];
		$ids = [];
		$n = 1;
		foreach($params as $key => $value){
			if($value) {
				$where = [['attr_id','=',$attr_list[$key]],['attr_value','like','%'.$value.'%']];
				$id = model('ContentAttributeModel')->Distinct(true)->where($where)->column('content_id');
				
				if(empty($id)) {
					$ids = [];
					break;
				}
				else{
					if($n == 1) {
						$ids = $id;
					}else{
						$ids = array_intersect($ids, $id);
					}
				}
				$n++;
			}
			
		}

		return $ids;
	}
}

if (!function_exists('get_content_list_info')) 
{
    /*获取内容列表
    *@table_name   string 查寻的数据表
    *@where    
    *@limit  分页记录数量
    *@desc   是否拉取详情
	*@params Query
    */
    function get_content_list_info ($table_name,$where,$limit,$params,$desc = false){
		if($params['keywords']) {
			$keywords = $params['keywords'];
			unset($params['keywords']);
		}
		
		if(isset($params['min_price'])) {
			$min_price = $params['min_price'];
			unset($params['min_price']);
		}
		
		if(isset($params['max_price'])) {
			$max_price = $params['max_price'];
			unset($params['max_price']);
		}
		
		//@ht详情显示类型 html type p:为图片列表,t:为文字列表
		if($params['ht']) {
			unset($params['ht']);
		}
		
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
		
		//排序order
		if($params['order']) {
			$order_arr = [
				1 => ['field'=>'a.id','order'=>'ASC'],
				2 => ['field'=>'a.id','order'=>'DESC'],
				3 => ['field'=>'a.created_at','order'=>'ASC'],
				4 => ['field'=>'a.created_at','order'=>'DESC'],
				5 => ['field'=>'a.goods_price','order'=>'ASC'],
				6 => ['field'=>'a.goods_price','order'=>'DESC']
			];
			$order = $order_arr[$params['order']];
			unset($params['order']);
		}
		
		//取属性关联的content_id
		$id_arr = get_content_id_arr($params);
		
		if($desc) {
			$contentModel =	model('contentModel')->alias('a')
				->field('b.*, a.id, a.cid, a.sort, a.title, a.etitle, a.type_id, a.tag, a.related, a.created_at, a.update_at, a.goods_price, a.pic, a.status, a.description ')
				->join($table_name.' b','a.content_id = b.id');
		}
		else{
			$contentModel =	model('contentModel')->alias('a')
				->field('a.id, a.cid, a.sort, a.title, a.etitle, a.type_id, a.tag, a.related, a.created_at, a.update_at, a.pic, a.status, a.description ');
		}
		
		if (isset($where['status'])) {
			$contentModel = $contentModel->where('a.status', $where['status']);
		}
		
		if (isset($where['siteid'])) {
			$contentModel = $contentModel->where('a.siteid', $where['siteid']);
		}
		
		if (isset($where['cid'])) {
			$contentModel = $contentModel->where('a.cid', 'IN', $where['cid']);
		}
		
		if (isset($where['mid'])) {
			$contentModel = $contentModel->where('a.mid', $where['mid']);
		}
		//->where('name', ['like', '%thinkphp%'], ['like', '%kancloud%'], 'or')
		if (!empty($keywords)) {
			$contentModel = $contentModel->where('a.title', 'like', "%".$keywords."%");
		}
		
		//属性筛选
		if (!empty($params)) {
			if(!empty($tag)) {
				$tag_id_arr = model('ContentTagModel')->Distinct(true)->where('tag', $tag)->column('content_id');
				$id_arr = array_intersect($id_arr, $tag_id_arr);
			}
			
			$ids = implode(',',$id_arr);
			$contentModel = $contentModel->where('a.id', 'IN', $ids);
		}
		else if (!empty($tag)){
			
			$ids = model('ContentTagModel')->Distinct(true)->where('tag', $tag)->column('content_id');
		
			$ids = implode(',',$ids);
		
			$contentModel = $contentModel->where('a.id', 'IN', $ids);
		}
		
		

		if (!empty($types)) {
			$contentModel = $contentModel->where('a.type_id', 'IN', $types);
		}
		
		if (!empty($min_price)) {
			$contentModel = $contentModel->where('a.goods_price', '>=', $min_price);
		}
		
		if (!empty($max_price)) {
			$contentModel = $contentModel->where('a.goods_price', '<=', $max_price);
		}
		
		if(!empty($order)) {
			$contentModel = $contentModel->order($order['field'], $order['order']);
		}
		else{
			$contentModel = $contentModel->order("a.sort", "desc")
						->order("a.id", "desc");
		}
		$data = $contentModel->paginate($limit, false, ['query' => request()->param()]);
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
			

			//相关内容
			if(isset($list['related']) && !empty($list['related'])) {
				$data[$key]['related_list'] = model('contentModel')->where('id', 'IN', $list['related'])->select();
			}
			
			//最近两天价格
			/* $prices = model('PriceModel')->where('content_id',$list['id'])
									->where("created_at", '<=', $nowdate)
									->order('created_at', 'desc')
									->limit(2)
									->select(); */
			/* if(count($prices)) {
				$data[$key]['today_price'] = $prices[0]['price'];
				if($prices[0]['created_at'] == $nowdate) {
					$data[$key]['yesterday_price'] = $prices[1]['price'];
				}
				else{
					$data[$key]['yesterday_price'] =  $prices[0]['price'];
				}
			}
			else{
				$data[$key]['today_price'] = 0;
				$data[$key]['yesterday_price'] = 0;
			} */
			
			foreach($list->attributes as $field=>$attr){
				$data[$key][$attr['attribute']['field']] = $attr['attr_value'];
			}
		}

		return $data;
		
	}
}


if (!function_exists('get_content_detail')) 
{
    //获取栏目内容及详情
    function get_content_detail ($id, $siteid){
		
    	$info = model('contentModel')->where('status', 1)
									->where('siteid', $siteid)
									->where('id', $id)
									->find();
		//相关内容
		if(isset($info['related']) && !empty($info['related'])) {
			$info['related_list'] = model('contentModel')->where('id', 'IN', $info['related'])->select();
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
		
    	$table_name = "form_".$info->model->table_name;
    	
    	$more = think\Db::name($table_name)->get($info->content_id);
		if($more['images']) {
			$more['images'] = array_filter(explode(',', $more['images']));
		}
		
		if($more['hangye']) {
			$more['hangye'] = str_ireplace(',',' / ', $more['hangye']);
		}
		
		/*格式化栏目
		*1、文章;2、单页面;３留言; 4、图片; 5、产品; 6、案例; 7、下载; 8、报名; 9、预约; 10、招聘
		*/
		switch ($info->model->type)
		{
			case 1:				//文章
				$viewPage = "/show_article";
				break;
			case 4:				//图片
				$viewPage = "/show_image";
				break;
			case 5:				//产品
				$viewPage = "/show_product";
				break;
			case 6:				//案例;
				$viewPage = "/show_cast";
				break;
			case 7:				//下载
				$viewPage = "/show_download";
				break;  
			default:	$viewPage = "/error";
			
		}
		$attributes = [];
		if(count($info['attributes'])>0){
			foreach($info['attributes'] as $key=>$attribute) {
				$attributes[$attribute['attribute']['field']] = $attribute['attr_value'];
			}
			$info['attr'] = $attributes;
		}
		
		$info['more'] = $more;
		$info['view'] = $viewPage;
		if($info->category->tpl_show) {
			$info['view'] = '/'.$info->category->tpl_show;
		}
		return $info;
    }
}

if (!function_exists('get_content_by_tag')) 
{
    //相关联的内容 相同标签的内容--[不要用, 已放弃.]
    function get_content_by_tag ($table, $tag){
		
		$data = model('contentModel')->field('b.*, a.id, a.cid, a.title, a.etitle, a.tag, a.pic, a.status ') 
			    ->alias('a')
				->join($table.' b', 'a.content_id = b.id')
			    ->where('a.tag', $tag)
				->select();
		$info = [];
		foreach($data as $kdy=>$value) {
			$info[$value['standard']]['standard'] = $value['standard'];
			$info[$value['standard']]['list'][] = $value;
		}
		return $info;
    }
}

if (!function_exists('get_lastAndnext')) 
{
    //获取上一篇/下一篇
    function get_lastAndnext ($id, $cid){
		$data = [];
		//获取所有id的集合,存到数组中
		//上一篇
		$data['last'] = model('contentModel')->where('status', 1)
									->where('cid', $cid)
									->where('id', '<', $id)
									->order("sort", "asc")
									->order('id','desc')
									->limit(1)
									->find();
		//下一篇							
		$data['next'] = model('contentModel')->where('status', 1)
									->where('cid', $cid)
									->where('id', '>', $id)
									->order("sort", "asc")
									->order('id', 'asc')
									->limit(1)
									->find();
		return $data;
	}
}

if (!function_exists('get_content_hot')) 
{
    //获取热门内容列表
    function get_content_hot ($cid, $limit=10){
		$cateInfo = model("categoryModel")->get($cid);
		if(empty($cateInfo)) return;
		$ids = model('categoryModel')->getSubCategories($cid);

		$table = "form_".$cateInfo->model->table_name;
		$data = model('contentModel')->field('b.*, a.* ')
			    ->alias('a')
				->join($table.' b', 'a.content_id = b.id')
			    ->where('a.cid', 'IN', $ids)
				->order('a.clicks', 'desc')
				->limit($limit)
				->select();
				
		foreach($data as $key=>$list){
			if(count($list->attributes) > 0) {
				foreach($list->attributes as $field=>$attr){
					$data[$key][$attr['attribute']['field']] = $attr['attr_value'];
				}
			}
			
		}
		return $data;
		
	}
	
}

if (!function_exists('get_subtree')) 
{
	//获取树的子节点
	function get_subtree ($tree, $id){
		$subtree = [];
		foreach($tree as $data) {
			if($data['id'] == $id){
				$subtree = $data;
				return $subtree;
			}
			else{
				$subtree = get_subtree ($data[$data['id']], $id);
				if(!empty($subtree)){
					return $subtree;
				}
			}
			
		}
		return $subtree;
	}
	
}

/* if (!function_exists('get_parent_type')) 
{
	//放弃使用...................................................................
    //获取上级栏目
    function get_parent_type ($list, $pid, $id){
		$cType = [];
				
		if($pid) {
			//2级
			foreach($list as $type) {
				if($type->id == $pid){
					$cType = $type;
					break;
				}
				else{
					if($type[$type['id']]) {
						//3级
						foreach($type[$type['id']] as $value) {
							if($value->id == $pid){
								$cType = $value;
								break;
							}
							else{
								if($value[$value['id']]) {
									//4级
									foreach($value[$value['id']] as $v) {
										if($v->id == $pid){
											$cType = $v;
											break;
										}
									}
								}
							}
						}
					}
					
				}
			}
		}
		else{
			//pid==0
			foreach($list as $type) {
				//root-->1级
				if($type->id == $id){
					$cType = $type;
					break;
				}
			}
		}

		//所有上级栏目
		$list = [];
		$cType['parents'] = model('CategoryModel')->get_parents($pid,$list);

		if($cType['parents']) {
			$cType['topid'] = current($cType['parents']);
		}
		
		return $cType;
	}
}
 */


// if (!function_exists('get_parent_type_list')) 
// {
//		//放弃使用...................................................................
//     //获取上级栏目
//     function get_parent_type_list ($list, $category){
// 		$cType = [];
// 		if($category['pid']) {
// 			foreach($list as $type) {
// 				if($type->id == $category['pid']){
// 					$cType = $type;
// 					break;
// 				}
// 				else{
// 					if($type[$type['id']]) {
// 						foreach($type[$type['id']] as $value) {
// 							if($value->id == $category['pid']){
// 								$cType = $value;
// 								break;
// 							}
// 							else{
// 								if($value[$value['id']]) {
// 									foreach($value[$value['id']] as $v) {
// 										if($v->id == $category['pid']){
// 											$cType = $v;
// 											break;
// 										}
// 									}
// 								}
// 							}
// 						}
// 					}
					
// 				}
// 			}
// 		}
// 		else{
// 			foreach($list as $type) {
// 				if($type->id == $category['id']){
// 					$cType = $type;
// 					break;
// 				}
// 			}
// 		}
		
// 		return $cType;
// 	}
// }

/*************************************************************
	ismodule 判断是否有模块
	@param   sign string   模块目录名称
	@return  int 
*************************************************************/
if (!function_exists('isModule')) 
{
	function isModule($sign)
	{
		  $F  = new \files\File();
		  
		  $installPath = env('app_path').$sign.DIRECTORY_SEPARATOR;
		  
		  return $F->d_has($installPath) ;

	}
}



/*************************************************************
	FF 将数组或者字符串写入到文件中
	@param   filename string  文件路径
	@param   data string|array   要写入文件的内容 可以为数组，如果数据为真表示写入，如果数据不为真表示读取
	@param   path string   写入文件路径 默认为当前模块的路径
	@param   type string   生成的格式 1 为[] 0为array();
	@return  bool 
*************************************************************/
if (!function_exists('FF')) 
{
	function FF($filename, $data="", $path='', $type = 1)
	{
		  $value = "";
		  if(!$path) 
		  {
			   $path = env('app_path');
			   
			   $module = request()->module();
			   
			   if($module) $path = env('module_path');
		  }
		  
		  $F  = new \files\File();
		  
		  $file = $path.$filename.".php";
		  if($data=="*")
		  {
			  $F-> f_delete($file);
			  return 1;
		  }
		  
		  if($data)
		  {

			   if(is_array($data) )
			   {
				   $data = var_export($data, true);
				   if($type) $data = preg_replace(['/array \(/','/\)/i'],['[',']'],$data);
				   $F->write($file,'<?php '.PHP_EOL.'return ' . $data . ';');
			   }
			   else
			   {
					$F->write($file,'<?php '.PHP_EOL.'return "' . $data . '";');
			   }
		  }
		  
		  if($F->f_has($file))
		  {
			  $config = include($file);
		  }
		  else
		  {
			  $config = false;
		  }
		  
		  return $config;

	}
}


/*************************************************************
	*get_price_string 取价格筛选条件显示字符
	*@min_price   最低价
	*@max_price   最高价
	*@return  最低价- 最高价
*************************************************************/
if (!function_exists('get_price_string')) 
{
	function get_price_string($min_price, $max_price)
	{
		if($min_price && $max_price) {
			return $min_price.'-'.$max_price.'元';
		}
		else if($min_price && empty($max_price)) {
			return $min_price.'元以上';
		}
		else if($max_price && empty($min_price)) {
			return $max_price.'元以下';
		}
	}
}

/*************************************************************
	*params_to_query_string 转换并合并URL QUERY参数
	*@param   array   GET数组
	*@field   string  参数名
	*@value   string　参数值
	*@replace int     是否替换参数值，0:替换；1:合并
	*@return  query string 
*************************************************************/
if (!function_exists('params_to_query_string')) 
{
	function params_to_query_string($params, $field='', $value='', $replace=0)
	{
		$query_string = '';
		
		//@params['t']
		if(is_object($value) || is_array($value)) {
			$types == '';
			foreach($value as $K=>$V) {
				$types .= $V['id'].',';
			}
			$value = rtrim($types, ',');
		}
		
		//清除价格筛选
		if($field == "price") {
			unset($params['min_price']);
			unset($params['max_price']);
		}
		
		if($field == "attr_price_1" || $field == "attr_price_2" || $field == "attr_price_3") {
			unset($params['min_price']);
			unset($params['max_price']);
		}
		
		if(array_key_exists($field, $params)) {
			foreach($params as $key=>$val) {
				if($key == $field) {
					if($replace == 1) {
						if(empty($val) || empty($value)){
							$query_string .= $field.'='.$value.'&';
						}
						else{
							$val_arr = explode(',', $val);
							if(in_array($value, $val_arr)) {
								if($field == 't') {
									//多选项,取消当前已选定项
									$cur_value = '';
									foreach($val_arr as $key => $cur_val) {
										if($cur_val != $value) {
											$cur_value .= $cur_val.',';
										}
									}
									$cur_value = rtrim($cur_value, ',');
									$query_string .= $field.'='.$cur_value.'&';
								}
								else {
									$query_string .= $field.'='.$value.'&';
								}
								
							}
							else {
								$query_string .= $field.'='.$val.','.$value.'&';
							}
						}
						
					}
					else {
						$query_string .= $field.'='.$value.'&';
					}
				}
				else {
					$query_string .= $key.'='.$val.'&';
				}
				
			}
		}
		else{
			foreach($params as $key=>$val) {
				$query_string .= $key.'='.$val.'&';
			}
			
			if($field){
				$query_string .= $field.'='.$value.'&';
			}
			
		}
		
		
		return rtrim($query_string, '&');
	}
}

/*************************************************************
	paramToQuery  截取utf-8格式的字符串
	@string   string   字符串
	@length   int 截取长度
	@etc   string　附加字符
	@result  return string 
*************************************************************/
if (!function_exists('truncate_utf8_string')) 
{
	function truncate_utf8_string($string, $length, $etc = '...') {
		$result = '';
		$string = html_entity_decode ( trim ( strip_tags ( $string ) ), ENT_QUOTES, 'UTF-8' );
		$strlen = strlen ( $string );
		for($i = 0; (($i < $strlen) && ($length > 0)); $i ++) {
			if ($number = strpos ( str_pad ( decbin ( ord ( substr ( $string, $i, 1 ) ) ), 8, '0', STR_PAD_LEFT ), '0' )) {
				if ($length < 1.0) {
					break;
				}
				$result .= substr ( $string, $i, $number );
				$length -= 1.0;
				$i += $number - 1;
			} else {
				$result .= substr ( $string, $i, 1 );
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars ( $result, ENT_QUOTES, 'UTF-8' );
		if ($i < $strlen) {
			$result .= $etc;
		}
		return $result;
	}
}

/**************************************************************************************/
/**
 * 获得全部省份列表
 */
function get_province_list()
{
    $result = extra_cache('global_get_province_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',1)
            ->getAllWithIndex('id');
        extra_cache('global_get_province_list', $result);
    }

    return $result;
}

/**
 * 获得全部城市列表
 */
function get_city_list()
{
    $result = extra_cache('global_get_city_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',2)
            ->getAllWithIndex('id');
        extra_cache('global_get_city_list', $result);
    }

    return $result;
}

/**
 * 获得全部地区列表
 */
function get_area_list()
{
    $result = extra_cache('global_get_area_list');
    if ($result == false) {
        $result = M('region')->field('id, name')
            ->where('level',3)
            ->getAllWithIndex('id');
        extra_cache('global_get_area_list', $result);
    }

    return $result;
}

/**
 * 根据地区ID获得省份名称
 */
function get_province_name($id)
{
    $result = get_province_list();
    return empty($result[$id]) ? '省份名称' : $result[$id]['name'];
}

/**
 * 根据地区ID获得城市名称
 */
function get_city_name($id)
{
    $result = get_city_list();
    return empty($result[$id]) ? '城市名称' : $result[$id]['name'];
}

/**
 * 根据地区ID获得县区名称
 */
function get_area_name($id)
{
    $result = get_area_list();
    return empty($result[$id]) ? '县区名称' : $result[$id]['name'];
}



if (!function_exists('is_realdomain')) 
{
    /**
     * 简单判断当前访问的域名是否真实
     * @param string $domain 不带协议的域名
     * @return boolean
     */
    function is_realdomain($domain = '')
    {
        $is_real = false;
        $domain = !empty($domain) ? $domain : request()->host();
        if (!preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i', $domain) && 'localhost' != $domain && '127.0.0.1' != serverIP()) {
            $is_real = true;
        }

        return $is_real;
    }
}

if (!function_exists('img_style_wh')) 
{
    /**
     * 追加指定内嵌样式到编辑器内容的img标签，兼容图片自动适应页面
     */
    function img_style_wh($content = '', $title = '')
    {
        if (!empty($content)) {
            preg_match_all('/<img.*(\/)?>/iUs', $content, $imginfo);
            $imginfo = !empty($imginfo[0]) ? $imginfo[0] : [];
            if (!empty($imginfo)) {
                $num = 1;
                $appendStyle = "max-width:100%!important;height:auto;";
                $title = preg_replace('/("|\')/i', '', $title);
                foreach ($imginfo as $key => $imgstr) {
                    $imgstrNew = $imgstr;
                    
                    /* 兼容已存在的多重追加样式，处理去重 */
                    if (stristr($imgstrNew, $appendStyle.$appendStyle)) {
                        $imgstrNew = preg_replace('/'.$appendStyle.$appendStyle.'/i', '', $imgstrNew);
                    }
                    if (stristr($imgstrNew, $appendStyle)) {
                        $content = str_ireplace($imgstr, $imgstrNew, $content);
                        $num++;
                        continue;
                    }
                    /* end */

                    // 追加style属性
                    $imgstrNew = preg_replace('/style(\s*)=(\s*)[\'|\"](.*?)[\'|\"]/i', 'style="'.$appendStyle.'${3}"', $imgstrNew);
                    if (!preg_match('/<img(.*?)style(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增style属性
                        $imgstrNew = str_ireplace('<img', "<img style=\"".$appendStyle."\" ", $imgstrNew);
                    }

                    // 移除img中多余的title属性
                    // $imgstrNew = preg_replace('/title(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', '', $imgstrNew);

                    // 追加alt属性
                    $altNew = $title."(图{$num})";
                    $imgstrNew = preg_replace('/alt(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', 'alt="'.$altNew.'"', $imgstrNew);
                    if (!preg_match('/<img(.*?)alt(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增alt属性
                        $imgstrNew = str_ireplace('<img', "<img alt=\"{$altNew}\" ", $imgstrNew);
                    }

                    // 追加title属性
                    $titleNew = $title."(图{$num})";
                    $imgstrNew = preg_replace('/title(\s*)=(\s*)[\'|\"]([\w\.]*?)[\'|\"]/i', 'title="'.$titleNew.'"', $imgstrNew);
                    if (!preg_match('/<img(.*?)title(\s*)=(\s*)[\'|\"](.*?)[\'|\"](.*?)[\/]?(\s*)>/i', $imgstrNew)) {
                        // 新增alt属性
                        $imgstrNew = str_ireplace('<img', "<img alt=\"{$titleNew}\" ", $imgstrNew);
                    }
                    
                    // 新的img替换旧的img
                    $content = str_ireplace($imgstr, $imgstrNew, $content);
                    $num++;
                }
            }
        }

        return $content;
    }
}