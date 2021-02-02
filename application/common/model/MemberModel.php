<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class MemberModel extends BaseModel
{
	protected $name = 'members';
	public function getList($params, $limit = self::LIMIT)
	{
		$members = $this;
		
		if (isset($params['name'])) {
			$members = $members->whereLike('name', '%'.$params['name'].'%');
		}
		if (isset($params['email'])) {
			$members = $members->whereLike('email', '%'.$params['email'].'%');
		}
		if (isset($params['phone'])) {
			$members = $members->whereLike('phone', '%'.$params['phone'].'%');
		}
		$list = $members->paginate($limit, false, ['query' => request()->param()]);
		
		return $list;
		
	}
	
	public function address()
	{
		return $this->hasMany('MemberAddressModel','user_id');
	}

	public function defaultAddress()
	{
		return $this->hasOne('MemberAddressModel', 'id', 'address_id');
	}
}