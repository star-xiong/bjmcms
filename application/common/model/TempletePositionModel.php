<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class TempletePositionModel extends BaseModel
{
    protected $name = 'templete_positions';	
	
	public function templetes()
	{
		return $this->hasMany('TempleteModel', 'position_id');
	}
}