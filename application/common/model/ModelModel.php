<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class ModelModel extends BaseModel
{
    protected $name = 'model';
	
	public function categories()
    {
    	return $this->hasMany('CategoryModel','mid');
    }
	
	public function fields()
	{
		return $this->hasMany('FieldModel', 'mid');
	}
	
	public function typeinfo()
	{
		return $this->belongsTo('ModelTypeModel','type');
	}
}