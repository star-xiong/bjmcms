<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class GoodsStockModel extends BaseModel
{
    protected $name = 'goods_stocks';
	
	/*获取商品库存
	* @goods_id 商品id
	* @return array
	*/
	public function getListByGoodsid($goods_id) {
		$data = [];		//array("attr_id"=>$attributeList)
		$attr_list = db('goods_attributes')->where('goods_id', $goods_id)->column('*', 'goods_attr_id');

		$list = $this->where("goods_id", $goods_id)->order('goods_stock_id')->All()->toArray();

		foreach($list as $key=>$value){
			$data[$key]['goods_stock_id'] = $value['goods_stock_id'];
			$data[$key]['goods_id'] = $value['goods_id'];
			$data[$key]['goods_sn'] = $value['goods_sn'];
			$data[$key]['goods_number'] = $value['goods_number'];
			$goods_attr = [];
			if(!empty($value['goods_attr'])){
				$attr = explode('|', $value['goods_attr']);

				foreach($attr as $K=>$V){
					$goodsAttr = [];
					
					$goodsAttr['attr_id'] = $attr_list[$V]['attr_id'];
					$goodsAttr['goods_attr_id'] = $V;
					$goodsAttr['attr_value'] = $attr_list[$V]['attr_value'];
					$goods_attr[] = $goodsAttr;
				}
				$data[$key]['goods_attr'] = $goods_attr;
			}
		}

		return $data;
	}
}