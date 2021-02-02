<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class TempleteModel extends BaseModel
{
    protected $name = 'templetes';
	public function position()
	{
		return $this->belongsTo('TempletePositionModel','position_id');
	}
}