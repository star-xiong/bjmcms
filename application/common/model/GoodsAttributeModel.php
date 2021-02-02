<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class GoodsAttributeModel extends BaseModel
{
    protected $name = 'goods_attributes';
	
	public function attribute()
	{		
		return $this->belongsTo('AttributeModel','attr_id');
	}
	
	/*获取属性，并以属性id为键(key)
	* @goods_id 商品id
	* @return array("attr_id"=>$attributeList)
	*/
	public function getListByGoodsid($goods_id) {
		$data = [];		//array("attr_id"=>$attributeList)
		$list = $this->where("goods_id", $goods_id)->order('goods_attr_id')->All()->toArray();
		foreach($list as $key=>$value){
			$data[$value['attr_id']][] = $value;
		}
		return $data;
	}
}