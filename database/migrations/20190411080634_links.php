<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Links extends Migrator
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
		$table = $this->table('links', ['engine' => 'InnoDB', 'comment' => '友情链接']);
		$table->addColumn('type', 'integer',['limit' => 1, 'default'=>'1','comment'=>'类型：1=文字链接，2=图片链接'])
			->addColumn('logo', 'string',['limit' => 255, 'default'=>'','comment'=>'网站Logo'])
			->addColumn('title', 'string',['limit' => 50, 'default'=>'','comment'=>'网站标题'])
			->addColumn('url', 'string',['limit' => 255, 'default'=>'','comment'=>'网站地址'])
			->addColumn('description', 'text',['comment'=>'网站简况'])
			->addColumn('target', 'integer',['limit' => 1, 'default'=>'0','comment'=>'是否开启浏览器新窗口'])
			->addColumn('email', 'string',['limit' => 50, 'default'=>''])
			->addColumn('lang', 'string',['limit' => 50, 'default'=>'cn','comment'=>'语言标识'])
			->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点ID'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('status', 'integer',['limit' => 1, 'default'=>'1','comment'=>'状态：1=显示，0=屏蔽'])
			->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
			->addColumn('update_at', 'timestamp', [ 'default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
			->addColumn('delete_at', 'timestamp', [ 'default' => 'CURRENT_TIMESTAMP', 'comment' => '软删除时间'])
		    ->create();
    }
}