<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class ModelTypeModel extends BaseModel
{
    protected $name = 'model_types';
	public function models()
	{
		return $this->hasMany('ModelModel','type');
	}
}