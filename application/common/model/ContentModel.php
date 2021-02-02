<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class ContentModel extends BaseModel
{
    protected $name = 'content';
	/**
	 * content List
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
	
		$content = $this;
		
		if (isset($params['status'])) {
			$content = $content->where('status', $params['status']);
		}
		
		if (isset($params['siteid'])) {
			$content = $content->where('siteid', $params['siteid']);
		}
		
		if (isset($params['mid'])) {
			$content = $content->where('mid', $params['mid']);
		}
		
		if (isset($params['cid'])) {
			$content = $content->where('cid', 'IN', $params['cid']);
		}
		
		if (isset($params['keywords'])) {
		    $content = $content->whereLike('title', '%'.$params['keywords'].'%');
	    }
	
		$list = $content->with('category')->order("sort", "asc")
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
		return $this->hasMany('ContentAttributeModel', 'content_id');
	}
	
	public function prices()
	{
		return $this->hasMany('PriceModel', 'content_id');
	}
	
	public function tops()
	{
		return $this->hasMany('ContentTopModel', 'content_id');
	}
}