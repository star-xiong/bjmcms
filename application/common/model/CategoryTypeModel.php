<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class CategoryTypeModel extends BaseModel
{
    protected $name = 'category_types';
	
	public function category()
	{
		return $this->hasOne('CategoryModel','id','category_id');
	}
	
	public function type()
	{
		return $this->hasOne('TypeModel','id','type_id');
	}

	/**
	 * 绑定栏目类型
	 * @param {Object} $types 栏目类型
	 * @param {Object} $cid   栏目ID
	 * @param {boolean} $isupdate   true:新增; false:更新;
	 */
	public function saveTypes($types, $cid, $isupdate=false)
	{
		//栏目类型
		$data = [];
		
		if($isupdate === true) {
			foreach($types as $key=>$value) {
				$data[$key]['category_id'] = $cid;
				$data[$key]['type_id'] = $value;
			}
			
			$this->where('category_id', $cid)->delete();
			$this->saveAll($data);
		}
		else {

			foreach($types as $key=>$value) {
				$data[$key]['category_id'] = $cid;
				$data[$key]['type_id'] = $value;
			}
			
			$this->saveAll($data);
		}
		
	}
}