<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class TypeModel extends BaseModel
{
    protected $name = 'types';
	protected $type = [
	    'attributes'      =>  'array',
	];
	
	public function contents()
	{
		return $this->hasMany('ContentModel','type_id');
	}
	
	public function attributes()
	{
		return $this->hasMany('AttributeModel','type_id');
	}
	// //栏目
	// public function categorys()
	// {
	// 	return $this->hasManyThrough('CategoryTypeModel', 'CategoryModel', 'id', 'type_id');
	// }
}