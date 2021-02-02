<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Category extends Migrator
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
		$table = $this->table('category', ['engine' => 'InnoDB', 'comment' => '内容栏目']);
		$table->addColumn('title', 'string',['limit' => 200, 'default'=>'','comment'=>'栏目名称'])
		    ->addColumn('subtitle', 'string',['limit' => 255, 'default'=>'','comment'=>'栏目标题'])
			->addColumn('siteid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'1','comment'=>'站点id'])
		    ->addColumn('pid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'0','comment'=>'上级栏目'])
			->addColumn('sub_ids', 'text',['comment'=>'子栏目'])
		    ->addColumn('mid', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'comment'=>'所属模型'])
			->addColumn('thumpic', 'string',['limit' => 255, 'default'=>'','comment'=>'栏目缩略图片'])
			->addColumn('adpic', 'string',['limit' => 255, 'default'=>'','comment'=>'栏目广告图片'])
			->addColumn('seo_title', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO标题'])
			->addColumn('seo_keywords', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO关键词'])
			->addColumn('seo_description', 'string',['limit' => 512, 'default'=>'','comment'=>'SEO描述'])
			->addColumn('jumpurl', 'string',['limit' => 255, 'default'=>'','comment'=>'外部链接'])
			->addColumn('tpl_cover', 'string',['limit' => 128, 'default'=>'','comment'=>'封面模版'])
			->addColumn('tpl_list', 'string',['limit' => 128, 'default'=>'','comment'=>'列表模版'])
			->addColumn('tpl_show', 'string',['limit' => 128, 'default'=>'','comment'=>'内容模版'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('status', 'integer',['limit' => 1, 'default'=>'1','comment'=>'0不显示 1显示'])
			->addColumn('target', 'integer',['limit' => 1, 'default'=>'0','comment'=>'0当前 1新窗口'])
			->addColumn('nav', 'integer',['limit' => 1, 'default'=>'0','comment'=>'0不显示 1主导航 2尾导航 3都显示'])
			->addColumn('page_id', 'integer',['limit' => MysqlAdapter::INT_REGULAR, 'default'=>'0', 'comment'=>'栏目内容ID'])
			->addColumn('type', 'integer',['limit' => 1, 'default'=>'1','comment'=>'栏目类型：1列表  0频道'])
		    ->create();
    }
}
