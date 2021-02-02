<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class MemberAddressModel extends BaseModel
{
	protected $name = 'member_address';
	
	public function provinceinfo()
	{
		return $this->hasOne('RegionModel', 'region_id', 'province');
	}
	
	public function cityinfo()
	{
		return $this->hasOne('RegionModel', 'region_id', 'city');
	}
	
	public function districtinfo()
	{
		return $this->hasOne('RegionModel', 'region_id', 'district');
	}
}