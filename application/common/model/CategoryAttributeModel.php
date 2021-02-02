<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class CategoryAttributeModel extends BaseModel
{
    protected $name = 'category_attributes';
	
	public function category()
	{
		return $this->hasOne('CategoryModel','id','category_id');
	}
	
	public function attr()
	{
		return $this->hasOne('AttributeModel','id','attribute_id');
	}
	
	/**
	 * 绑定栏目属性
	 * @param {Object} $attributes 栏目类型
	 * @param {Object} $cid   栏目ID
	 * @param {boolean} $isupdate   true:新增; false:更新;
	 */
	public function saveAttributes($attributes, $cid, $isupdate=false)
	{
		$data = [];
		if($isupdate === true) {
			foreach($attributes as $key=>$value) {
				$data[$key]['category_id'] = $cid;
				$data[$key]['attribute_id'] = $value;
			}
			$this->where('category_id', $cid)->delete();
			$this->saveAll($data);
		}
		else {
			foreach($attributes as $key=>$value) {
				$data[$key]['category_id'] = $cid;
				$data[$key]['attribute_id'] = $value;
			}

			$this->saveAll($data);
		}
	}
}