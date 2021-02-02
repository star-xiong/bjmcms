<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\model\ContentModel;
use app\admin\request\PriceRequest;
use app\common\model\PriceModel;
use app\admin\traits\ContentTrait;
use app\common\model\CategoryModel;
use think\Db;
class Price extends Base
{
	use ContentTrait;
	
    public function index(){
		
		return $this->fetch();
    }
	

	//获取栏目内容列表
	public function getList (PriceModel $priceModel, ContentModel $contentModel, CategoryModel $categoryModel){
		
		if($this->request->param('cid')) {
			$cid = intval($this->request->param('cid'));
			$ids = $categoryModel->getSubCategories($cid);
		}

		$params = $this->request->param();
		$this->checkParams($params);
		
		if($cid && $ids){
			$params['cid'] = $ids;
		}
		
		//默认开启产品模型价格管理
		//$params['mid'] = 3;		//不可以使用mid==3
		$params['siteid'] = session('site');
		
		$list = $contentModel->getList($params, $this->limit);

		if($list) {
			foreach ($list as $key => $value) {
				$list[$key]['cname'] = $value->category->title;
				$lastprice = $priceModel->where('content_id',$value['id'])->order('created_at', 'desc')->find();
				$list[$key]['lastprice'] = $lastprice->price;
				$list[$key]['price_date'] = $lastprice->created_at;
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

	//获取栏目内容列表
	public function import (PriceRequest $request, PriceModel $priceModel, CategoryModel $categoryModel){
		$cid = $this->request->param('cid');
		
		$category = $categoryModel->get($cid);
		if(empty($category)) {
			$data = ['code'=> 1, 'msg'=>'栏目不存在，请选择正确的栏目！', 'data'=> null];
			return json($data);
		}
		$mid = $category['mid'];

		if ($request->isPost()) {
			$param = $request->post();
			
			
			//$filename = ".".$param['filename'];
			$filename = "public/uploads/files/".$param['filename'];
			
			$handle = fopen($filename, 'r'); 
			
			$result = getCsv($handle, 0, 0);
			
			$len_result = count($result); 
			if($len_result==0){
				$data = ['code'=> 0, 'msg'=>'没有任何数据！', 'data'=> $result];
				return json($data);
			}
			
			$insert_arr = [];
			$today = $param['created_at'];
			$year = date('Y',strtotime($today));
			$month = date('m',strtotime($today));
			$day = date('d',strtotime($today));
			$contentIds = '';
			
			//$title = array('ID', '品名', '品牌', '型号', '栏目', '价格', '单位', '交货地点', '推荐供应商');

			for ($i = 1; $i < $len_result; $i++) {					//循环获取各字段值
				if($result[$i][0]){
					
					if($contentIds) {
						$contentIds .= ','.$result[$i][0];
					}
					else{
						$contentIds = $result[$i][0];
					}

					$insert_arr[$i]['content_id'] = $result[$i][0];
					$insert_arr[$i]['price'] = $result[$i][5];
					$insert_arr[$i]['created_at'] = $today;
					$insert_arr[$i]['year'] = $year;
					$insert_arr[$i]['month'] = $month;
					$insert_arr[$i]['day'] = $day;
				}else{
					//如果ID不存在，先插入数据，再更新价格表
					if($result[$i][3] && $cid && $mid) {
						$data_arr = [];
						$data_arr['attr_brand'] = $result[$i][1];			//品名
						$data_arr['attr_manufacturer'] = $result[$i][2];	//品牌
						$data_arr['title'] = $result[$i][3];				//型号
						$data_arr['cid'] = $cid;							//栏目
						
						$data_arr['unit'] = $result[$i][6];					//单位
						$data_arr['attr_address'] = $result[$i][7];			//交货地点
						$data_arr['place'] = $result[$i][8];				//推荐供应商
						
						$res = $this->save($data_arr, $mid);
						if($res['data']){
							$insert_arr[$i]['content_id'] = $res['data'];
							$insert_arr[$i]['price'] = $result[$i][5];		//价格
							$insert_arr[$i]['created_at'] = $today;
							$insert_arr[$i]['year'] = $year;
							$insert_arr[$i]['month'] = $month;
							$insert_arr[$i]['day'] = $day;
						}
					}
				}

			}
			
			fclose($handle);				//关闭指针 

			if($contentIds) {
				$priceModel->where('created_at', $today)
							->where('content_id', 'IN', $contentIds)
							->delete();
			}
						
			if ($priceModel->saveAll($insert_arr) !== false) {
				if(file_exists($filename)){
					unlink($filename);
				}
				$data = ['code'=> 0, 'msg'=>'成功导入'.count($insert_arr).'条数据的价格！', 'data'=> null];
			} else {
				$data = ['code'=> 1, 'msg'=>'价格导入失败', 'data'=> null];
			}
			
			return json($data);
			
		}
		$this->assign('category', $category);
		return $this->fetch();
	}


}