<?php
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: star xiong <875376798@qq.com>
// | QQ: 875376798
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;

class Sites extends Migrator
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
		$table = $this->table('site', ['engine' => 'InnoDB', 'comment' => '站点设置']);
		$table->addColumn('name', 'string',['limit' => 60, 'default'=>'','comment'=>'站点名称'])
			->addColumn('logo', 'string',['limit' => 255, 'default'=>'','comment'=>'站点Logo'])
			->addColumn('seo_title', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO标题'])
			->addColumn('seo_keywords', 'string',['limit' => 255, 'default'=>'','comment'=>'SEO关键词'])
			->addColumn('seo_description', 'string',['limit' => 512, 'default'=>'','comment'=>'SEO描述'])
			->addColumn('domain', 'string',['limit' => 128, 'default'=>'','comment'=>'绑定域名'])
			->addColumn('mark', 'string',['limit' => 128, 'default'=>'','comment'=>'en、zh、cn、guangzhou'])
			->addColumn('default_style', 'string',['limit' => 128, 'default'=>'','comment'=>'站点风格'])
			->addColumn('dirname', 'string',['limit' => 30, 'default'=>'','comment'=>'站点目录'])
			->addColumn('code', 'text',['comment'=>'统计代码'])
			->addColumn('notice', 'text',['comment'=>'站点公告'])			
			->addColumn('setting', 'text',['comment'=>'其他配置'])
			->addColumn('sort', 'integer',['limit' => 6, 'default'=>'9','comment'=>'排序'])
			->addColumn('status', 'integer',['limit' => 1, 'default'=>'1','comment'=>'0关闭站点 1启用站点'])
			->addColumn('isdefault', 'integer',['limit' => 1, 'default'=>'0','comment'=>'前端默认站'])
		    ->create();
    }
}
