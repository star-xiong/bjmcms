<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RoleHasCategories extends Migrator
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
		$table = $this->table('role_has_categories', ['engine' => 'InnoDB', 'comment' => '角色拥有的栏目权限']);
		$table->addColumn('role_id', 'integer',['limit' => 11, 'comment'=>'角色ID'])
			  ->addColumn('category_id', 'integer', [ 'comment' => '栏目ID'])
			  ->create();
    }
}
