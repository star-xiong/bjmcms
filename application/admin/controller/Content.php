<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\TreeService;
use app\admin\request\ContentRequest;
use app\admin\traits\FieldTrait;
use app\admin\traits\ContentTrait;
use app\admin\request\DiyformRequest;
use think\Db;

class Content extends Base
{
	use FieldTrait, ContentTrait;
	
	public $category = [];		//当前栏目
	public $categories;			//栏目列表
	public $treeService;

	public function __construct()
	{
		parent::__construct();
		$this->treeService = new TreeService();
	}
	
	/**
	 * 初始化栏目信息
	 *
	 * @time at 2019年03月21日
	 */
	private function init($cid = 0) {
		$user= session(config('permissions.user'))->toArray();
		if($user['id'] == 1) {
			//超级管理员
			$catelist = model('CategoryModel')->getCatgoryByRole('');
		}
		else{
			$catelist = model('CategoryModel')->getCatgoryByRole(implode(',', array_column($user['roles'], 'id')));
		}
		
		$this->categories = $this->treeService->sort($catelist);
		
		if ($cid) {
			$this->category = model('CategoryModel')->with(['model','typelist','attrlist'])->get($cid);
		} else {
			$this->category = ['id'=>0,'title'=>"顶级栏目"];
		}
	}
	
	public function index(){
		
		$cid = intval($this->request->param('cid'));		
		$this->init($cid);
		$categories = $this->categories;
		
		/*格式化栏目
		*1、文章;2、单页面、３留言；4、图片;5、产品;6、案例;7、下载；8、报名；9、预约；10、招聘
		*/
		$zNodes = "[";
		foreach ($categories as $key => $val) {
			if($val->model->type != 5) {
				switch ($val->model->type)
				{
					case 1:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					  $typeurl = url('content/list', array('cid'=>$val['id']));
					  break;  
					case 2:
					  $gourl = url('content/page', array('cid'=>$val['id']));
					  $typeurl = url("content/page", array('cid'=>$val['id']));
					  break;
					case 3:
					case 9:
					case 10:
					  $typeurl = url('content/diyForm', array('cid'=>$val['id']));
					  break;
					default:
					  $typeurl = url('content/list', array('cid'=>$val['id']));
				}
				
				$zNodes .= "{"."id:{$val['id']}, pId:{$val['pid']}, name:\"{$val['title']}\", url:'{$typeurl}',target:'content_body'";
				
				/*默认展开一级栏目*/
				if (empty($val['pid'])) {
					$zNodes .= ",open:true";
				}
				
				/*栏目有下级栏目时，显示图标*/
				if (empty($val['sub_ids'])) {
					$zNodes .= ",isParent:false";
				} else {
					$zNodes .= ",isParent:true";
				}

				$zNodes .= "},";
			}
		}
		$zNodes .= "]";
		
	
		$this->assign([
		    'catelist'  => $zNodes
		]);
		return $this->fetch();
	}
	
	public function list(){
		$cid = intval($this->request->param('cid'));		
		$this->init($cid);
		$this->assign([
		    'info'  => $this->category,
			'categories'  => $this->treeService->sort(model('CategoryModel')->where(['siteid'=>session('site'),'mid'=>$this->category->model->id])->All()),
		]);

		return $this->fetch();
	}
	
	public function diyForm(){
		$cid = intval($this->request->param('cid'));		
		$this->init($cid);
	
		$this->assign([
		    'info'  => $this->category
		]);
	
		return $this->fetch();
	}
	
	//获取栏目内容列表[留言、报名、预约]
	public function getDiyList (){
		$cid = intval($this->request->param('cid'));		
	
		$params = $this->request->param();
		$this->checkParams($params);
		
		$category = model('CategoryModel')->get($cid);
		
		$table_name = "form_".$category->model->table_name;
		
		$diyList = Db::name($table_name);
		if ($this->request->param('keywords')) {
		    $diyList->whereOr('name', 'like', '%'.$this->request->param('keywords').'%')
					->whereOr('phone', 'like', '%'.$this->request->param('keywords').'%')
					->whereOr('content', 'like', '%'.$this->request->param('keywords').'%');
		}
		
		$list = $diyList->paginate($this->limit, false, ['query' => request()->param()]);

		return json($this->layuiPaginator($list));
	}
	
	//删除[留言、报名、预约]数据
	public function delDiyForm () {
		$cid = intval($this->request->param('cid'));
		$id = intval($this->request->param('id'));
		
		$category = model('CategoryModel')->get($cid);
		
		$table_name = "form_".$category->model->table_name;
		
		$falg = Db::name($table_name)->delete($id);
		
		return ['code'=> 0, 'msg'=>'删除数据成功', 'data'=> $falg];
		
	}

	//获取栏目内容列表
	public function getList (){
		$cid = intval($this->request->param('cid'));		

		$ids = model('CategoryModel')->getSubCategories($cid);
		
		$params = $this->request->param();
		$this->checkParams($params);
		
		if($cid && $ids){
			$params['cid'] = $ids;
		}
		$params['siteid'] = session('site');
		$list = model('ContentModel')->getList($params, $this->limit);
		if($list) {
			foreach ($list as $key => $value) {
				if ($value['cid'] == 0) {
					$list[$key]['cname'] = "默认顶级栏目";
				} else {
					$list[$key]['cname'] = $value->category->title;
				}

				$content = $this->getContentFull($value['id']);
				
				foreach($content['more'] as $K=>$V) {
					if($K != 'id'){
						$list[$key][$K] = $V;
					}
				}
				if(!empty($list[$key]['remarks'])) {
					$list[$key]['title'] = '<font color=red>['.$list[$key]['remarks'].']</font> '.$list[$key]['title'];
				} 
			}
		}
		return json($this->layuiPaginator($list));
	}
	
	public function page(ContentRequest $request)
	{
		$cid = intval($this->request->param('cid'));
		
		if (!$cid) {
			$this->error('不存在的数据！');
		}
		
		if ($request->isPost()) {
			$param = $request->post();
			$res = $this->updateCatAndPage($param);
			return json($res);
		}
		
		$this->init($cid);
		
		$page = [];
		if($this->category['page_id']) {
			$page = $this->getPage($this->category['mid'], $this->category['page_id']);
		}
				
		$fieldlist = model('FieldModel')->where('siteid', session('site'))
								->where('mid', $this->category['mid'])
								->select();
		
		$fieldhtml = $this->fieldformat($fieldlist,$page);
		
		$data = model('CategoryModel')->getTypeAndAttrs ($cid);
		
		$this->assign([
            'info'  => $this->category,
            'catelist' => $this->categories,
			'type_id_arr' => $data['types'],
			'attr_id_arr' => $data['attrs'],
			'types' => model('TypeModel')->order('sort', 'asc')->All(),
			'attributes' => model('AttributeModel')->order('sort', 'asc')->All(),
			'fieldhtml' => $fieldhtml
        ]);

		return $this->fetch();
	}
	
	/**
	 * Create Content
	 *
	 * @time at 2019年03月21日
	 * @return mixed|string
	 */
	public function create(ContentRequest $request)
	{
		$cid = intval($this->request->param('cid'));		
		$this->init($cid);
		
		if ($request->isPost()) {
			$param = $request->post();			
			$res = $this->save($param, $this->category['mid']);
			return json($res);
		}
		
		$fieldlist = model('FieldModel')->where('siteid', session('site'))
								->where('mid', $this->category['mid'])
								->order('sort','asc')
								->select();
		$fieldhtml = $this->fieldformat($fieldlist);
		
		$attrlist = $this->category->attrlist->toArray();
		foreach($attrlist as $key=>$value) {
			$attrlist[$key]['field'] = 'attr_'.$value['id'];
		}
		$attrhtml = $this->fieldformat($attrlist);
		
		$this->assign([
		    'info'  => $this->category,
			'fieldhtml' => $fieldhtml,
			'attrhtml' => $attrhtml,
			'relatedlist' => $this->getRelated($cid),
			'catelist'  => $this->treeService->sort(model('CategoryModel')->where(['siteid'=>session('site'),'mid'=>$this->category->mid])->order('sort')->All()),
			'categories'  => model('CategoryModel')->where('siteid', session('site'))->where('status', 1)->where('istop', 1)->All(),
		]);
		return $this->fetch();
	}
	
	/**
	 * edit Content
	 *
	 * @time at 2019年03月21日
	 * @return mixed|string
	 */
	public function edit(ContentRequest $request)
	{
		$id = intval($this->request->param('id'));
		if (!$id) {
			$this->error('不存在的数据！');
		}
		
		if ($request->isPost()) {
			$param = $request->post();
			$res = $this->update($param);
			return json($res);
		}
		
		$content = $this->getContentFull($id);
		
		//$content['top_arr'] = explode(',', $content['istop']);
		
		$content['top_arr'] = $content->tops->column('top_id');

		if($content){
			$this->init($content['cid']);
		}
		
		$fieldlist = model('FieldModel')->where('siteid', session('site'))
								->where('mid', $this->category['mid'])
								->order('sort','asc')
								->select();
		
		$fieldhtml = $this->fieldformat($fieldlist, $content['more']);
		$attrlist = $this->category->attrlist->toArray();
		foreach($attrlist as $key=>$value) {
			$attrlist[$key]['field'] = 'attr_'.$value['id'];
		}
		$attrhtml = $this->fieldformat($attrlist, $content['attributes']);
		
		$this->assign([
		    'info'  => $this->category,
			'content' => $content,
			'fieldhtml' => $fieldhtml,
			'attrhtml' => $attrhtml,
			'relatedlist' => $this->getRelated($content['cid'], $id),
			'catelist'  => $this->treeService->sort(model('CategoryModel')->where(['siteid'=>session('site'),'mid'=>$this->category->mid])->order('sort')->All()),
			'categories'  => model('CategoryModel')->where('siteid', session('site'))->where('status', 1)->where('istop', 1)->All(),
		]);
		return $this->fetch();
	}
	
	/**
	 * edit Content
	 *
	 * @time at 2019年03月21日
	 * @return mixed|string
	 */
	public function editDiyform(DiyformRequest $request)
	{
		$id = intval($this->request->param('id'));
		$cid = intval($this->request->param('cid'));
		if (!($cid || $id)) {
			$this->error('不存在的数据！');
		}
		
		$this->init($cid);
		
		$table_name = "form_".$this->category->model->table_name;
		
		if ($request->isPost()) {
			$param = $request->post();
			unset($param['__token__']);
			unset($param['cid']);

			$res = Db::name($table_name)->update($param);
			
			if($res){

				return json(['code' => 0, 'data' => '', 'msg' => '保存成功']);
			}else{
				return json(['code' => 1, 'data' => '', 'msg' => '没有数据更新']);
			}

		}
		
		
		
		$more = Db::name($table_name)->get($id);
		
		$fieldlist = model('FieldModel')->where('siteid', session('site'))
								->where('mid', $this->category['mid'])
								->order('sort','asc')
								->select();
						
		$fieldhtml = $this->fieldformat($fieldlist, $more);
		
		$this->assign([
			'id' => $id,
		    'info'  => $this->category,
			'fieldhtml' => $fieldhtml
		]);
		return $this->fetch();
	}
	
	public function delete()
	{
		$id = $this->request->post('id');
		if (!$id) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}
		$data = $this->del($id);
	    return json($data);
	}
	
	public function batch($do) {
		$actions = ['recommend', 'move', 'delete', 'update'];
		
		if(in_array($do, $actions)) {
			$ids = $this->request->post('ids');
			$cid = $this->request->post('cid');
			if(empty($ids)) {
				$data = ['code'=> 0, 'msg'=>'不存在数据', 'data'=> null];
			}
			else {
				if($do == "move") {
					if(empty($cid)){
						$data = ['code'=> 0, 'msg'=>'不存在数据', 'data'=> null];
					}
					else{
						$category = model('CategoryModel')->get($cid);
						$id_arr = model('ContentModel')->where("mid", $category['mid'])
									->where("id", "IN", $ids)
									->column("id");
						$id_str = implode(',', $id_arr);
						$flag = model('ContentModel')->where('id', 'IN', $id_str)
											->update(["cid"=>$cid]);
						if($flag) {
							$data = ['code'=> 0, 'msg'=>'移动文档成功'];					
						}
						else{
							$data = ['code'=> 0, 'msg'=>'没有文档被移动'];
						}
					}
				}
				
				else if($do == "recommend") {
					/* $flag = model('ContentModel')->where('id', 'IN', $ids)
										->update(["cid"=>$cid]);
					if($flag) {
						$data = ['code'=> 0, 'msg'=>'文档推荐成功'];					
					}
					else{
						$data = ['code'=> 0, 'msg'=>'没有文档被推荐'];
					} */
				}
				
				else if($do == "delete") {

					$flag = model('ContentModel')->where('id', 'IN', $ids)
										->delete();
					if($flag) {
						$data = ['code'=> 0, 'msg'=>'删除文档成功'];					
					}
					else{
						$data = ['code'=> 0, 'msg'=>'没有文档被删除'];
					}
				}
				
				else if($do == "update") {
					$new_date = date('Y-m-d H:i:s', time());
					$flag = model('ContentModel')->where('id', 'IN', $ids)
										->update(["update_at"=>$new_date]);
					if($flag) {
						$data = ['code'=> 0, 'msg'=>'更新文档成功'];					
					}
					else{
						$data = ['code'=> 0, 'msg'=>'没有文档被更新'];
					}
				}
				
				else {
					$data = ['code'=> 1, 'msg'=>'无效的操作', 'data'=> null];
				}
			}
		}
		else {
			$data = ['code'=> 1, 'msg'=>'无效的操作', 'data'=> null];
		}
		return json($data);
	}
	
	public function export()
	{

		set_time_limit(0);
		ini_set('memory_limit', '128M');
		$cid = intval($this->request->param('cid'));
		$fileName = date('YmdHis', time());
		header('Content-Type: application/vnd.ms-execl');
		header('Content-Disposition: attachment;filename="prices_' . $fileName . '.csv"');
		 
		//$begin = microtime(true);
		 
		//打开php标准输出流
		//以写入追加的方式打开
		$fp = fopen('php://output', 'a');
		 
		
		//我们试着用fputcsv从数据库中导出1百万的数据
		//我们每次取1万条数据，分100步来执行
		//如果线上环境无法支持一次性读取1万条数据，可把$nums调小，$step相应增大。
		$step = 1;
		$nums = 10000;
		 
		//设置标题
		$title = array('ID', '品名', '品牌', '型号', '栏目', '价格', '单位', '交货地点', '推荐供应商');

		foreach($title as $key => $item) {
		    $title[$key] = iconv('UTF-8', 'GBK', $item);
		}
		//将标题写到标准输出中
		fputcsv($fp, $title);
		 
		for($s = 1; $s <= $step; ++$s) {			
			$result = get_content_list (session('site'), $cid, 1000, [], ture);
		    if($result) {
		        foreach($result['data'] as $key =>$row) {
					$data = [];
					$data['id'] = iconv('UTF-8', 'GBK', $row['id']);
					
					//品名
					if(isset( $row['attr_brand'])) {
						$data['attr_brand'] = iconv('UTF-8', 'GBK', $row['attr_brand']);
					}
					else{
						$data['attr_brand'] = '';
					}
					
					//品牌
					if(isset( $row['attr_manufacturer'])) {
						$data['attr_manufacturer'] = iconv('UTF-8', 'GBK', $row['attr_manufacturer']);
					}
					else{
						$data['attr_manufacturer'] = '';
					}
					
					//型号
					$data['title'] = iconv('UTF-8', 'GBK', $row['title']);
					
					//栏目
					$data['category'] = iconv('UTF-8', 'GBK', $row['category']['title']);
					
					//价格
					$data['price'] = iconv('UTF-8', 'GBK', $row['today_price']);
					
					//单位
					if(isset( $row['unit'])) {
						$data['unit'] = iconv('UTF-8', 'GBK', $row['unit']);
					}
					else{
						$data['unit'] = '';
					}
					
					//交货地点
					if(isset( $row['attr_address'])) {
						$data['attr_address'] = iconv('UTF-8', 'GBK', $row['attr_address']);
					}
					else{
						$data['attr_address'] = '';
					}
					
					//推荐供应商
					if(isset( $row['place'])) {
						$data['place'] = iconv('UTF-8', 'GBK', $row['place']);
					}
					else{
						$data['place'] = '';
					}
					
		            fputcsv($fp, $data);
		        }
				
		        ob_flush();
		        flush();
		    }
		}
		 
		//$end = microtime(true);
	}
	
	//获取栏目内容及相关内容
	public function getRelatedList (){
		$cid = intval($this->request->param('cid'));		
		$content_id = intval($this->request->param('id'));
		$data = $this->getRelated($cid, $content_id);
	
		return json($data);
	}
	//获取相关内容
	private function getRelated($cid, $content_id=0){
		$data = [];
		if($content_id) {
			$content = model('ContentModel')->get($content_id);
			if($content['related']){
				$related = model('ContentModel')->where('siteid', session('site'))->where('id', 'IN', $content['related'])->select();
				foreach ($related as $key => $value) {
					$data_array = [];
					$data_array['id'] = $value['id'];
					$data_array['name'] = $value['title'];
					$data[] = $data_array;
				}
				
			}
		}
		
		if(isset($content['related']) && $content['related']){
			$list = model('ContentModel')->where('siteid', session('site'))->where('id', 'NOT IN', $content['related'])->where('cid', $cid)->select();
		}
		else{
			$list = model('ContentModel')->where('siteid', session('site'))->where('cid', $cid)->select();
		}
		
		if($list) {
			foreach ($list as $key => $value) {
				$data_array = [];
				$data_array['id'] = $value['id'];
				$data_array['name'] = $value['title'];
				$data[] = $data_array;
			}
		}
		unset($list);
		
		return $data;
	}
}