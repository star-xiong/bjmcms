<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\traits;

trait GoodsTrait
{
	/*获取选定的商品属性，（价格及库存）；
	*@return array
	*/
	private function getSelectedAttributes($params){
	    $goods = [];
	    $goods["goods_id"] = $params['goods_id'];
	    //$goods["goods_price"] = $params['goods_price'];
		
	    
	    unset($params['goods_id']);
	    unset($params['number']);
	    unset($params['goods_price']);
		$attr_arr = [];
		if(isset($params['spec_attr_type']) && !empty($params['spec_attr_type'])) {
			$attr_arr = model("AttributeModel")->field('title, attr_type, id')
											->where("id", "IN", implode(',', $params['spec_attr_type']))
											->order('sort')
											->select()
											->toArray();
			unset($params['spec_attr_type']);
		}
		

		$attr = [];
	    $radio_arr = [];
		if(!empty($attr_arr)){
			foreach($attr_arr as $value){
				if($value['attr_type'] == 1){
					$key = 'spec_'.$value['id'];
					$radio_arr[] = $params[$key];
				}
				$attr[$value['id']] = $value['title'];
				unset($params[$key]);
			}
		}
	    								
	    
	    $checkbox_arr = array_pop($params);
	    
	    if($checkbox_arr == null){
	    	$all_arr = $radio_arr;
	    }
	    else{			
	    	$all_arr = array_merge($checkbox_arr, $radio_arr);
	    }
	    
		//获取商品信息
		$goods_info = model("GoodsModel")->field('title as goods_name, goods_sn, pic as goods_img, type_id, goods_price, market_price, goods_number')
										->get($goods["goods_id"])
										->toArray();
		$goods["goods_price"] = $goods_info["goods_price"];
		unset($goods_info["goods_price"]);
	    //规格价合计
		if(!empty($all_arr)){
			$goods["goods_attr_id"] = implode(',',$all_arr);
			$attr_value = model("GoodsAttributeModel")->where("goods_attr_id", "IN", $goods["goods_attr_id"])->select();
			
			$goods["goods_attr"] = '';
			foreach($attr_value as $item) {
				$goods["goods_price"] += $item['attr_price'];
				$goods["goods_attr"] .= $attr[$item['attr_id']].':'.$item['attr_value'].' ';
			}
		}
				
		$goods = array_merge($goods, $goods_info);
		$goods["stock_id"] = 0;
		
	    //商品库存
		if(!empty($radio_arr)){
			$goods_stocks = model("GoodsStockModel")->where("goods_id",$goods["goods_id"])
												->where("goods_attr",implode('|',$radio_arr))
												->find();
												
			$goods["goods_number"] = $goods_stocks['goods_number'];
			$goods["stock_id"] = $goods_stocks['goods_stock_id'];
		}
		
		return $goods;
	}
	
}