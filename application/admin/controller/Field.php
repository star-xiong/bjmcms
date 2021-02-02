<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\request\FieldRequest;
use app\admin\traits\FieldTrait;
use think\Config;
use think\Db;

class Field extends Base
{
	use FieldTrait;
	
	public $fields;
	public $formtype;
	public $mid;
	public function __construct()
	{
	    parent::__construct();
	    $this->fields = ['text' => '单行文本', 'textarea' => '多行文本', 'seditor' => '简约编辑器', 'editor' => '富文本编辑器', 'file' => '附件', 'image' => '单图上传','images' => '多图上传', 'datetime' => '日期和时间', 'number' => '数字', 'radio' => ' 单选按钮', 'checkbox' => '复选框', 'select' => '下拉框'];
	    $this->formtype = [
	        'text'=>'varchar',
	        'textarea'=>'text',
	        'seditor'=>'text',
	        'editor'=>'text',
	        'file'=>'varchar',
	        'image'=>'varchar',
	        'images'=>'text',
	        'datetime'=>'varchar',
	        'radio'=>'text',
	        'checkbox'=>'text',
	        'number'=>'int',
	        'select'=>'text'
	    ];
		
		$this->mid  = $this->request->param('id') ?? 0;
	}
	
	public function index(){
		$this->url = url('field/create','id='.$this->mid);	
		$this->id = $this->mid;
		return $this->fetch();
	}
	
	/**
	 * Get model list
	 *
	 * @time at 2019年03月15日
	 * @return json
	 */
	public function getList()
	{
		$list = model('FieldModel')->where('siteid',session('site'))
							->where('mid',$this->mid)
							->order('sort', 'asc')
							->All();
		foreach($list as $key => $row) {
			$row['class'] = $this->fields[$row['class']];
			$list[$key] = $row;
		}
		return json($this->layuiPaginator($list));
	}
	
	/**
	 * Create model
	 *
	 * @time at 2019年03月15日
	 * @return mixed|string
	 */
	public function create(FieldRequest $request)
	{
		if ($request->isPost()) {
			$param = $request->post();
			$param['isrequire'] = switch_on_to_1($param['isrequire']);
			$param['siteid'] = session('site');
			$param['type'] = 2;		//用户模型为2
			if (model('FieldModel')->store($param)) {

				if ($this->addField($param, $this->formtype) === false) {
					$data = ['code'=> 1, 'msg'=>'创建失败', 'data'=> null];
				} else {
					$data = ['code'=> 0, 'msg'=>'创建成功', 'data'=> null];
				}
			} else {
				$data = ['code'=> 2, 'msg'=>'创建失败', 'data'=> null];
			}
		
			return json($data);
	    }

		$this->classlist = $this->fields;
		$this->id = $this->mid;
		return $this->fetch();
	}
	
	
	/**
	 * Edit model
	 *
	 * @time at 2019年03月07日
	 * @return mixed|string
	 */
	public function edit(FieldRequest $request)
	{
		if (!$this->request->param('id')) {
			$this->error('不存在的数据');
		}
		
		$field_info = model('FieldModel')->get($this->request->param('id'));
		
		if ($request->isPost()) {
			$param = $request->post();
			$param['isrequire'] = switch_on_to_1($param['isrequire']);
			$flag = model('FieldModel')->where('id', $param['id'])
				->data($param)
				->update();

            if (false === $flag) {
				$data = ['code'=> 1, 'msg'=>'没有更改', 'data'=> $flag];
			} else {
                //修改数据表字段
				if ($res = $this->editField($param, $field_info['field'], $this->formtype) !== 'false') {
					$data = ['code'=> 0, 'msg'=>'修改数据表字段成功', 'data'=> $res];
				} else {
					$data = ['code'=> 1, 'msg'=>'修改数据表字段失败', 'data'=> $res];
				}

			}
			return json($data);
		}
		

		$this->classlist = $this->fields;
		
		$default = $this->getInput($field_info['class'], $field_info['default_value'], $field_info['values']);
		
		$field_info['default_tips'] = $default['typename'];
		$field_info['default_value'] = $default['html'];
		$this->_field = $field_info;

		
		return $this->fetch();
	}

	public function delete()
	{
		$id = $this->request->post('id');
		if (!$id) {
			$data = ['code'=> 1, 'msg'=>'不存在数据', 'data'=> null];
			return json($data);
		}

		$field = model('FieldModel')->get($id);
		
	    if ($field && model('FieldModel')->delete($id)) {
	        //删除数据表字段
			if (($res = $this->deleteField($field)) === 'false') {
				$data = ['code'=> 2, 'msg'=>'删除数据表字段失败', 'data'=> $res];
			} else {
				$data = ['code'=> 0, 'msg'=>'删除数据表字段成功', 'data'=> $res];
			}
	    } else {
			$data = ['code'=> 1, 'msg'=>'删除数据失败', 'data'=> $res];
		}
	    return json($data);
	}

	public function defaultValue()
	{
		if (!$this->request->param('formtype')) {
			$this->error('不存在的数据');
		}
	    $formtype = $this->request->param('formtype');
	    return json($this->getInput($formtype));
	}
	

	
}