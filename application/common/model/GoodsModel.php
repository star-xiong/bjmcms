<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class GoodsModel extends BaseModel
{
    protected $name = 'goods';
	/**
	 * goods List
	 *
	 * @time at 2019年03月25日
	 * @param $params
	 * @return \think\Paginator
	 */
	public function getList($params, $limit = self::LIMIT)
	{
		
		if (!count($params)) {
			return $this->with('category')->paginate($limit);
	    }
	
		$goods = $this;
		
		if (isset($params['status'])) {
			$goods = $goods->where('status', $params['status']);
		}
		
		if (isset($params['siteid'])) {
			$goods = $goods->where('siteid', $params['siteid']);
		}
		
		if (isset($params['mid'])) {
			$goods = $goods->where('mid', $params['mid']);
		}
		
		if (isset($params['cid'])) {
			$goods = $goods->where('cid', 'IN', $params['cid']);
		}
		
		if (isset($params['keywords'])) {
		    $goods = $goods->whereLike('title', '%'.$params['keywords'].'%');
	    }
	
		$list = $goods->with('category')->order("sort", "asc")
									->order("id", "desc")
									->paginate($limit, false, ['query' => request()->param()]);
		
		return $list;
	}
	
	public function category()
	{
		return $this->belongsTo('CategoryModel', 'cid');
	}
	
	public function model()
	{
		return $this->belongsTo('ModelModel', 'mid');
	}
	
	public function type()
	{
		return $this->belongsTo('TypeModel', 'type_id');
	}
	
	public function attributes()
	{
		return $this->hasMany('GoodsAttributeModel', 'goods_id');
	}
	public function galleries()
	{
		return $this->hasMany('GoodsGalleryModel', 'goods_id');
	}
	public function stocks()
	{
		return $this->hasMany('GoodsStockModel', 'goods_id');
	}
	public function tags()
	{
		return $this->hasMany('GoodsTagModel', 'goods_id');
	}
	public function prices()
	{
		return $this->hasMany('PriceModel', 'goods_id');
	}
}