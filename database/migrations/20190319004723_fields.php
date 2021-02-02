<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Fields extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
		$table = $this->table('field', ['engine' => 'InnoDB', 'comment' => '模型字段表']);
		$table->addColumn('mid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'comment'=>'所属模型ID'])
			->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点id'])
			->addColumn('title', 'string',['limit' => 60, 'default'=>'','comment'=>'字段名称'])
		    ->addColumn('field', 'string',['limit' => 30, 'default'=>'','comment'=>'字段'])
			->addColumn('values', 'text',['default'=> null,'comment'=>'字段可选值'])
			->addColumn('class', 'string',['limit' => 20, 'default'=>'0','comment'=>'字段类型'])
			->addColumn('default_value', 'string',['limit' => 255, 'default'=> null,'comment'=>'默认值'])
			->addColumn('isrequire', 'integer',['limit' => 1, 'default'=>'0','comment'=>'0非必填 1必填'])
			->addColumn('type', 'integer',['limit' => 1, 'default'=>'1','comment'=>'1系统字段 2用户字段'])
			->addColumn('maxlength', 'integer',['limit' => 3, 'default'=> null,'comment'=>'字段长度'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('remark', 'string',['limit' => 255, 'default'=>'0','comment'=>'备注'])
		    ->create();
    }
}