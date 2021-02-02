<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class GoodsGalleryModel extends BaseModel
{
    protected $name = 'goods_gallery';
	
	/*获取图片，并以属性id为键(key)
	* @goods_id 商品id
	* @return array("attr_id"=>$galleryList)
	*/
	public function getListByGoodsid($goods_id) {
		$data = [];
		$list = $this->where("goods_id", $goods_id)->order('img_sort')->All()->toArray();
		foreach($list as $key=>$value){
			$data[$value['goods_attr_id']][] = $value;
		}
		return $data;
	}
}