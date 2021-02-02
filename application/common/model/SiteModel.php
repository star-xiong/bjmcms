<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class SiteModel extends BaseModel
{
    protected $name = 'site';
	
	public function models()
	{
		return $this->hasMany('ModelModel', 'siteid');
	}
	
	public function categories()
	{
		return $this->hasMany('CategoryModel', 'siteid');
	}
}