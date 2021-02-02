<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Banners extends Migrator
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
		$table = $this->table('banners', ['engine' => 'InnoDB', 'comment' => '首页幻灯片']);
		$table->addColumn('title', 'string',['limit' => 50, 'default'=>'0'])
			->addColumn('pic', 'string',['limit' => 255, 'default'=>'','comment'=>'幻灯片'])
			->addColumn('url', 'string',['limit' => 255, 'default'=>'','comment'=>'链接地址'])
			->addColumn('description', 'text',['comment'=>'备注'])
			->addColumn('target', 'integer',['limit' => 1, 'default'=>'0','comment'=>'是否开启浏览器新窗口'])
			->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点ID'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('status', 'integer',['limit' => 1, 'default'=>'1','comment'=>'状态：1=显示，0=屏蔽'])
			->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
			->addColumn('update_at', 'timestamp', [ 'default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
		    ->create();
    }
}
