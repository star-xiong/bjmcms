<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\common\model;

class CategoryModel extends BaseModel
{
    protected $name = 'category';
    private $sysfield = ['app','caches','config','data','system','template','uploads'];
	protected $type = [
		'sub_ids'    => 'array',
    ];
	
	/**
	 * Category List
	 *
	 * @time at 2019年03月12日
	 * @param $roles 
	 * @return Collection
	 */
	public function getCatgoryByRole(string $roles, $where='') {
		$category = $this->where('siteid', session('site'));
		if($where) {
			$category->where($where);
		}
		
		if($roles) {
			$cat_str = implode(',', db('role_has_categories')->where('site_id', session('site'))
															->where('role_id', 'IN', $roles)
															->column('category_id'));
			return $category->where('id', 'IN', $cat_str)
						->with(['model','typelist','attrlist'])
						->order('sort asc')
						->select();
		}
		else{
			return $category->with(['model','typelist','attrlist'])
						->order('sort asc')
						->select();
		}
	}
	
	//模型
	public function model()
    {
    	return $this->belongsTo('ModelModel','mid');
    }
	
	//内容
	public function contents()
	{
		return $this->hasMany('ContentModel','cid');
	}
	
	//推荐到栏目的内容
	public function top_contents()
	{
		return $this->hasMany('ContentTopModel','top_id');
	}

	//商品
	public function goods()
	{
		return $this->hasMany('GoodsModel','cid');
	}
	
	//栏目类型
	public function typelist()
	{
		return $this->belongsToMany('TypeModel','category_types','type_id','category_id');
	}
	
	//栏目属性
	public function attrlist()
	{
		return $this->belongsToMany('AttributeModel','category_attributes','attribute_id','category_id');
	}
	
	/**
	 * 取栏目已绑定类型及属性
	 * @param {Object} $cid 栏目ID
	 * @return {Data}
	 */
	public function getTypeAndAttrs ($cid)
	{
		$data = [];
		
		$data['category'] = $this->get($cid);
		
		//栏目类型
		foreach($data['category']->typelist as $key=>$value) {
			$data['types'][] = $value['id'];
		}
		
		//栏目属性
		foreach($data['category']->attrlist as $key=>$value) {
			$data['attrs'][] = $value['id'];
		}

		return $data;
	}
	
	 /**
	 * 通过ID 获得子节点信息
	 * @param $cateid
	 * @return array
	 */
	public function getchilrenid($cateid){
		$cateres=$this->select();
		return $this->_getchilrenid($cateres,$cateid);
	}

	/**
	 * 递归方法
	 * @param $cateres
	 * @param $cateid
	 * @return array
	 */
	public function _getchilrenid($cateres,$cateid){
		static $arr=array();
		foreach ($cateres as $k => $v) {
			if($v['pid'] == $cateid){
				$arr[]=$v['id'];
				$this->_getchilrenid($cateres,$v['id']);
			}
		}
		return $arr;
	}
	
	/**
	 * 取子栏目
	 * @param {Object} $category_id
	 */
	public function getSubCategories ($cid)
	{
		$ids_str = '';
		if(is_array($cid)) {
			$ids_str = implode(',', $cid);
		}
		else {
			$ids_str = $cid;
		}
		
		$ids = $this->where('pid', 'IN', $ids_str)->column('id');
		
		if($ids){
			$idsStr = self::getSubCategories($ids);
			$ids_str .= ','.$idsStr;
		}
		
		return $ids_str;
		
	}
	
	/**
	 * 取上级父栏目，第一个元素为根；
	 * @param array()
	 *
	 *return array parents
	 */
	public function get_parents($pid)
	{
		static $parents = [];
		$cat = $this->get($pid);
		$parents[] = $cat;
		
		if($cat['pid']){
			$this->get_parents($cat['pid']);
		}
		krsort($parents);
		return array_values($parents);
	}
/* 
	public function get_parents($pid,&$parents)
	{
		$cat = $this->get($pid);
		$parents[] = $cat;
		
		if($cat['pid']){
			$this->get_parents($cat['pid'], $parents);
		}
		krsort($parents);
		return array_values($parents);
	}
 */
	/**
	 * 删除 栏目相关的角色权限
	 *
	 * @author star xiong at 2019年03月14日
	 * @return int
	 */
	public function detachRole($category_id)
	{
		$ids =  db('role_has_categories')->where('site_id', session('site'))
											->where('category_id', $category_id)
											->column('id');
		return db('role_has_categories')->delete($ids);
	}

	/**
	 * update
	 *
	 * @author star xiong at 2019年03月14日
	 * @param $id
	 * @param $data
	 * @return static
	 */
	public function updateBy(int $id, array $data)
	{
		return self::where('id', $id)->update($data);
	}
	
	/**
	 * update sub category
	 *
	 * @author star xiong at 2019年07月02日
	 * @param $pid
	 * @return static
	 */
	public function updateSubIds(int $pid)
	{

		$ids = $this->where('pid', $pid)->order('sort', 'acs')->column('id');
		if(empty($ids)) {
			$ids = '';
		}
		return self::where('id', $pid)
					->json(['sub_ids'])
					->update(array('sub_ids' => $ids));
	}
	
	/**
	 * Delete category
	 *
	 * @author star xiong at 2019年03月14日
	 * @param $category_id
	 * @return bool|int
	 */
	public function deleteBy($category_id)
	{
		return self::get($category_id)->delete();
	}
	
	/**
	 * Attach Categories To Role
	 * 增加关联
	 * @author star xiong 
	 * @param $role_id
	 * @param null $categories
	 * @return mixed
	 */
	public function attachCategories($role_id, $categories = null)
	{
		$data = [];
		if(is_array($categories)) {
			foreach($categories as $value) {
				$data[] = ['site_id'=>session('site'), 'role_id'=>$role_id, 'category_id'=>$value];
			}
		}
		
		return db('role_has_categories')->insertAll($data);
	}
	
	/**
	 * Detach Categories of Role
	 * 删除关联
	 * @author star xiong
	 * @param $role_id
	 * @param null $categories
	 * @return mixed
	 */
	public function detachCategories($role_id, $categories = null)
	{
		$ids = db('role_has_categories')->where('site_id', session('site'))
											->where('role_id', $role_id)
											->column('id');
		return db('role_has_categories')->delete($ids);
	}
}