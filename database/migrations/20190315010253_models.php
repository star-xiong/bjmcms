<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Models extends Migrator
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
		$table = $this->table('model', ['engine' => 'InnoDB', 'comment' => '模型表']);
		$table->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点id'])
			->addColumn('title', 'string',['limit' => 60, 'default'=>'','comment'=>'模型名称'])
			->addColumn('table_name', 'string',['limit' => 50, 'default'=>'','comment'=>'表名称'])
			->addColumn('description', 'string',['limit' => 255, 'default'=>'','comment'=>'简介'])
			->addColumn('type', 'integer',['limit' => 2, 'default'=>'1','comment'=>'1、文章;2、单页面、３留言；4、图片;5、产品;6、案例;7、下载；8、报名；9、预约；10、招聘'])
			->addColumn('issystem', 'integer',['limit' => 1, 'default'=>'1','comment'=>'类别 1系统模型 2用户模型'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
		    ->create();

    }
}
