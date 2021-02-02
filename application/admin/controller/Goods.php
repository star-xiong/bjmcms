<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\TreeService;
use app\admin\request\GoodsRequest;
use app\admin\traits\FieldTrait;
use app\admin\traits\GoodsTrait;
use think\Db;

class Goods extends Base
{
	use FieldTrait, GoodsTrait;
	
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
		
		$zNodes = "[";
		foreach ($categories as $key => $val) {
			if($val->model->type == 5) {
				$typeurl = url('goods/list', array('cid'=>$val['id']));
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
	public function page(GoodsRequest $request)
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
		$list = model('GoodsModel')->getList($params, $this->limit);
		if($list) {
			foreach ($list as $key => $value) {
				if ($value['cid'] == 0) {
					$list[$key]['cname'] = "默认顶级栏目";
				} else {
					$list[$key]['cname'] = $value->category->title;
				}

				$goods = $this->getGoodsFull($value['id']);
				
				foreach($goods['more'] as $K=>$V) {
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

	public function getAttrList() {
		$type_id = intval($this->request->param('tid'));
		$goods_id = intval($this->request->param('gid'));
		//商品属性
		$data = [];
		$default_attr = model('AttributeModel')->where("type_id", $type_id)->order('sort')->select()->toArray();		//默认属性
		$goods_attr_list = model('GoodsAttributeModel')->getListByGoodsid($goods_id);	//现有属性
		$attr_html = '';
		foreach($default_attr as $key=>$item){
			if(count($goods_attr_list[$item['id']]) > 0) {
				$i = 1;
				foreach($goods_attr_list[$item['id']] as $K=>$V) {
					$attr_html .= '<div  class="layuiadmin-form-attribute">';
					$attr_html .= '	<div class="layui-form-item">';
					$attr_html .= '		<label class="layui-form-label">';
					if($item['attr_type'] != 0 && $i == 1) {
						$attr_html .= '			<a href="javascript:;" class="attr-add" data-attrid="' . $item['id'] . '" data-attrval="' . $item['title'] . '">[+]</a>';
					}
					else{
						$attr_html .= '			<a href="javascript:;" class="attr-del" data-attrid="' . $item['id'] . '" data-attrval="' . $item['title'] . '">[-]</a>';
					}
					$attr_html .=  $item['title'] . '</label>';
					$attr_html .= '		<div class="layui-input-inline">';
					$attr_html .= '			<input type="text" name="goods_attr[' . $item['id'] . '][value][]" value="'.$V['attr_value'].'" class="layui-input">';
					$attr_html .= '		</div>';
					$attr_html .= '		<label class="layui-form-label">属性价格</label>';
					$attr_html .= '		<div class="layui-input-inline">';
					$attr_html .= '			<input type="text" name="goods_attr[' . $item['id'] . '][price][]" value="'.$V['attr_price'].'" class="layui-input">';
					$attr_html .= '		</div>';
					$attr_html .= '	</div>';
					$attr_html .= '</div>';
					++$i;
				}
				
			}
			else{
				$attr_html .= '<div  class="layuiadmin-form-attribute">';
				$attr_html .= '	<div class="layui-form-item">';
				$attr_html .= '		<label class="layui-form-label">';
				if($item['attr_type']) {
					$attr_html .= '			<a href="javascript:;" class="attr-add" data-attrid="' . $item['id'] . '" data-attrval="' . $item['title'] . '">[+]</a>';
				}
				$attr_html .=  $item['title'] . '</label>';
				$attr_html .= '		<div class="layui-input-inline">';
				$attr_html .= '			<input type="text" name="goods_attr[' . $item['id'] . '][value][]" value="" class="layui-input">';
				$attr_html .= '		</div>';
				$attr_html .= '		<label class="layui-form-label">属性价格</label>';
				$attr_html .= '		<div class="layui-input-inline">';
				$attr_html .= '			<input type="text" name="goods_attr[' . $item['id'] . '][price][]" value="" class="layui-input">';
				$attr_html .= '		</div>';
				$attr_html .= '	</div>';
				$attr_html .= '</div>';
			}
		}
		return json($attr_html);
	}

	/**
	 * Create Goods
	 *
	 * @time at 2019年03月21日
	 * @return mixed|string
	 */
	public function create(GoodsRequest $request)
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
		// $attrlist = $this->category->attrlist->toArray();
		// foreach($attrlist as $key=>$value) {
		// 	$attrlist[$key]['field'] = 'attr_'.$value['id'];
		// }
		// $attrhtml = $this->fieldformat($attrlist);
		
		
		$this->assign([
		    'info' => $this->category,
			'fieldhtml' => $fieldhtml,
			'brands' =>model('BrandModel')->where('siteid',session('site'))->All(),
			'relatedlist' => $this->getGoodsRelated($cid),
			'catelist'  => $this->treeService->sort(model('CategoryModel')->where(['siteid'=>session('site'),'mid'=>$this->category->mid])->order('sort')->All()),
			//'categories'  =>model('CategoryModel')->where('siteid', session('site'))->All(),
		]);
		return $this->fetch();
	}
	
	/**
	 * edit Goods
	 *
	 * @time at 2019年03月21日
	 * @return mixed|string
	 */
	public function edit(GoodsRequest $request)
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
		
		$goods = $this->getGoodsFull($id);
		
		//商品属性
		$default_attr = $goods->type()->attributes()->order('sort')->select()->toArray();	//默认属性
		$goods_attr_list = model('GoodsAttributeModel')->getListByGoodsid($goods['id']);	//现有属性
		$goods_gallery_list = model('GoodsGalleryModel')->getListByGoodsid($goods['id']);	//商品相册
		
		//商品库存
		$goods_stock_list = model('GoodsStockModel')->getListByGoodsid($goods['id']);
		
		//启用相册的属性ID
		$goods_attr_gallery_id = 0;
		foreach($default_attr as $value){
			if($value['is_image'] == 1){
				$goods_attr_gallery_id = $value['id'];
				break;
			}
		}

		if($goods){
			$this->init($goods['cid']);
		}
		
		$fieldlist = model('FieldModel')->where('siteid', session('site'))
								->where('mid', $this->category['mid'])
								->order('sort','asc')
								->select();
		
		$fieldhtml = $this->fieldformat($fieldlist, $goods['more']);
		
		$this->assign([
		    'info'  => $this->category,
			'goods' => $goods,
			'fieldhtml' => $fieldhtml,
			'brands' => model('BrandModel')->where('siteid',session('site'))->All(),
			'attrlist' => $default_attr,
			'goods_attr_list' => $goods_attr_list,
			'goods_gallery_list' => $goods_gallery_list,
			'goods_stock_list' => $goods_stock_list,
			'goods_attr_gallery_id' => $goods_attr_gallery_id,
			'relatedlist' => $this->getGoodsRelated($goods['cid'], $id),
			'catelist'  => $this->treeService->sort(model('CategoryModel')->where(['siteid'=>session('site'),'mid'=>$this->category->mid])->order('sort')->All()),
		]);
		return $this->fetch();
	}
	
	//获取内目商品及相关商品
	public function getGoodsList (){
		$cid = intval($this->request->param('cid'));		
		$goods_id = intval($this->request->param('id'));
		$data = $this->getGoodsRelated($cid, $goods_id);
	
		return json($data);
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
						$id_arr = model('GoodsModel')->where("mid", $category['mid'])
									->where("id", "IN", $ids)
									->column("id");
						$id_str = implode(',', $id_arr);
						$flag = model('GoodsModel')->where('id', 'IN', $id_str)
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
					/* $flag = model('GoodsModel')->where('id', 'IN', $ids)
										->update(["cid"=>$cid]);
					if($flag) {
						$data = ['code'=> 0, 'msg'=>'文档推荐成功'];					
					}
					else{
						$data = ['code'=> 0, 'msg'=>'没有文档被推荐'];
					} */
				}
				
				else if($do == "delete") {

					$flag = model('GoodsModel')->where('id', 'IN', $ids)
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
					$flag = model('GoodsModel')->where('id', 'IN', $ids)
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
			$result = get_goods_list (session('site'), $cid, 1000, [], ture);
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
	
	private function getGoodsRelated($cid, $goods_id=0){
		$data = [];
		if($goods_id) {
			$goods = model('GoodsModel')->get($goods_id);
			if($goods['related']){
				$related = model('GoodsModel')->where('siteid', session('site'))->where('id', 'IN', $goods['related'])->select();
				foreach ($related as $key => $value) {
					$data_array = [];
					$data_array['id'] = $value['id'];
					$data_array['name'] = $value['title'];
					$data[] = $data_array;
				}
				
			}
		}
		
		if(isset($goods['related']) && $goods['related']){
			$list = model('GoodsModel')->where('siteid', session('site'))->where('id', 'NOT IN', $goods['related'])->where('cid', $cid)->select();
		}
		else{
			$list = model('GoodsModel')->where('siteid', session('site'))->where('cid', $cid)->select();
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