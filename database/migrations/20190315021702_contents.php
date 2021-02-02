<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Contents extends Migrator
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
		$table = $this->table('content', ['engine' => 'InnoDB', 'comment' => '内容主表']);
		$table->addColumn('cid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'0','comment'=>'栏目ID'])
		    ->addColumn('mid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'comment'=>'所属模型ID'])
			->addColumn('content_id', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'comment'=>'所属模型内容ID'])
			->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点id'])
			->addColumn('uid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'0','comment'=>'内容创建者id'])
			->addColumn('title', 'string',['limit' => 200, 'default'=>'','comment'=>'内容标题'])
		    ->addColumn('etitle', 'string',['limit' => 255, 'default'=>'','comment'=>'内容副标题'])
			->addColumn('jumpurl', 'string',['limit' => 255, 'default'=>'','comment'=>'外部链接'])
			->addColumn('pic', 'string',['limit' => 255, 'default'=>'','comment'=>'缩略图'])
			->addColumn('description', 'text',['comment'=>'内容简介'])
			->addColumn('seo_title', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO标题'])
			->addColumn('seo_keywords', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO关键词'])
			->addColumn('seo_description', 'string',['limit' => 512, 'default'=>'','comment'=>'SEO描述'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('status', 'integer',['limit' => 1, 'default'=>'0','comment'=>'0不发布 1发布'])
			->addColumn('target', 'integer',['limit' => 1, 'default'=>'0','comment'=>'0不发布 1发布'])
			->addColumn('istop', 'integer',['limit' => 1, 'default'=>'0','comment'=>'头条 0不推荐 1推荐'])
			->addColumn('tag', 'string',['limit' => 255, 'default'=>'','comment'=>'标签'])
			->addColumn('clicks', 'integer',['limit' => 9, 'default'=>'0','comment'=>'点击次数'])
			->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
			->addColumn('update_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
		    ->create();

    }
}

