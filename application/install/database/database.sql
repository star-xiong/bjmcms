--
-- MySQL database dump
-- Created by Backup class. 
-- http://www.mike.gd.cn 
--
-- 主机: localhost
-- 生成日期: 2019 年  12 月 29 日 09:31
-- MySQL版本: 5.5.64-MariaDB
-- PHP 版本: 7.2.24

--
-- 数据库: `bjmcmsdb`
--

-- -------------------------------------------------------

--
-- 表的结构bjmcms_attributes
--

DROP TABLE IF EXISTS `bjmcms_attributes`;
CREATE TABLE `bjmcms_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `values` varchar(255) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL COMMENT '1text 2radio 3checkbox 4select',
  `sort` int(11) DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `isrequire` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `maxlength` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_attributes
--

--
-- 表的结构bjmcms_category
--

DROP TABLE IF EXISTS `bjmcms_category`;
CREATE TABLE `bjmcms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
  `subtitle` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `siteid` int(11) DEFAULT '1' COMMENT '站点id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类',
  `mid` int(11) NOT NULL COMMENT '所属模型',
  `thumpic` varchar(255) DEFAULT NULL,
  `adpic` varchar(255) NOT NULL DEFAULT '' COMMENT '封面照片',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(512) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `jumpurl` varchar(255) NOT NULL DEFAULT '' COMMENT '外部链接',
  `tpl_cover` varchar(128) NOT NULL DEFAULT '' COMMENT '封面模版',
  `tpl_list` varchar(128) NOT NULL DEFAULT '' COMMENT '列表模版',
  `tpl_show` varchar(128) NOT NULL DEFAULT '' COMMENT '内容模版',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0不显示 1显示',
  `target` int(1) NOT NULL DEFAULT '0' COMMENT '0当前 1新窗口',
  `nav` int(1) NOT NULL DEFAULT '0' COMMENT '0不显示 1主导航 2尾导航 3都显示',
  `sub_ids` text COMMENT '子栏目',
  `page_id` int(11) DEFAULT '0',
  `description` text,
  `path` varchar(50) DEFAULT NULL,
  `limit` int(5) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='内容栏目';

--
-- 转存表中的数据 bjmcms_category
--

--
-- 表的结构bjmcms_category_attributes
--

DROP TABLE IF EXISTS `bjmcms_category_attributes`;
CREATE TABLE `bjmcms_category_attributes` (
  `category_id` int(11) NOT NULL DEFAULT '0',
  `attribute_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `cid_aid` (`category_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_category_attributes
--

--
-- 表的结构bjmcms_category_types
--

DROP TABLE IF EXISTS `bjmcms_category_types`;
CREATE TABLE `bjmcms_category_types` (
  `category_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `cid_tid` (`category_id`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_category_types
--

--
-- 表的结构bjmcms_content
--

DROP TABLE IF EXISTS `bjmcms_content`;
CREATE TABLE `bjmcms_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `mid` int(11) NOT NULL COMMENT '所属模型ID',
  `content_id` int(11) NOT NULL COMMENT '所属模型内容ID',
  `siteid` int(11) NOT NULL DEFAULT '1' COMMENT '站点id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '内容标题',
  `etitle` varchar(255) NOT NULL DEFAULT '' COMMENT '内容副标题',
  `jumpurl` varchar(255) NOT NULL DEFAULT '' COMMENT '外部链接',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `description` text COMMENT '内容简介',
  `seo_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(512) NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0未发布 1发布',
  `istop` varchar(255) NOT NULL DEFAULT '0' COMMENT '头条 0不推荐 1推荐',
  `tag` varchar(255) NOT NULL DEFAULT '' COMMENT '标签',
  `clicks` int(9) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `target` tinyint(1) DEFAULT '0' COMMENT '1新窗口，0不开新窗口',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  `type_id` int(11) DEFAULT '0',
  `goods_price` decimal(10,2) DEFAULT NULL COMMENT '售价',
  `group` varchar(255) DEFAULT NULL,
  `related` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='内容主表';

--
-- 转存表中的数据 bjmcms_content
--

--
-- 表的结构bjmcms_content_attributes
--

DROP TABLE IF EXISTS `bjmcms_content_attributes`;
CREATE TABLE `bjmcms_content_attributes` (
  `content_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `attr_value` varchar(255) DEFAULT NULL,
  UNIQUE KEY `contentid_attrid` (`content_id`,`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_content_attributes
--

--
-- 表的结构bjmcms_content_groups
--

DROP TABLE IF EXISTS `bjmcms_content_groups`;
CREATE TABLE `bjmcms_content_groups` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `group_id` char(4) NOT NULL DEFAULT '0',
  UNIQUE KEY `cgid` (`content_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_content_groups
--

--
-- 表的结构bjmcms_content_tags
--

DROP TABLE IF EXISTS `bjmcms_content_tags`;
CREATE TABLE `bjmcms_content_tags` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(50) NOT NULL DEFAULT '',
  UNIQUE KEY `ctag` (`content_id`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_content_tags
--

--
-- 表的结构bjmcms_content_top_types
--

DROP TABLE IF EXISTS `bjmcms_content_top_types`;
CREATE TABLE `bjmcms_content_top_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_content_top_types
--

INSERT INTO `bjmcms_content_top_types` VALUES('1','推荐');
INSERT INTO `bjmcms_content_top_types` VALUES('2','新品');
INSERT INTO `bjmcms_content_top_types` VALUES('3','精品');
INSERT INTO `bjmcms_content_top_types` VALUES('4','热门');
--
-- 表的结构bjmcms_content_tops
--

DROP TABLE IF EXISTS `bjmcms_content_tops`;
CREATE TABLE `bjmcms_content_tops` (
  `top_id` int(10) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `tcid` (`top_id`,`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='内容推荐表';

--
-- 转存表中的数据 bjmcms_content_tops
--

--
-- 表的结构bjmcms_field
--

DROP TABLE IF EXISTS `bjmcms_field`;
CREATE TABLE `bjmcms_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '所属模型ID',
  `siteid` int(11) NOT NULL DEFAULT '1' COMMENT '站点id',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '字段名称',
  `field` varchar(30) NOT NULL DEFAULT '' COMMENT '字段',
  `values` text COMMENT '字段可选值',
  `class` varchar(20) DEFAULT NULL COMMENT '字段类型',
  `default_value` varchar(255) DEFAULT NULL COMMENT '默认值',
  `isrequire` int(1) NOT NULL DEFAULT '0' COMMENT '0非必填 1必填',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1用户字段 2系统字段',
  `maxlength` int(3) NOT NULL COMMENT '字段长度',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `remark` varchar(255) NOT NULL DEFAULT '0' COMMENT '提示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='模型字段表';

--
-- 转存表中的数据 bjmcms_field
--

--
-- 表的结构bjmcms_form_apply
--

DROP TABLE IF EXISTS `bjmcms_form_apply`;
CREATE TABLE `bjmcms_form_apply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_apply
--

--
-- 表的结构bjmcms_form_articles
--

DROP TABLE IF EXISTS `bjmcms_form_articles`;
CREATE TABLE `bjmcms_form_articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text,
  `publisher` varchar(50) DEFAULT '小编',
  `logo` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_articles
--

--
-- 表的结构bjmcms_form_ask
--

DROP TABLE IF EXISTS `bjmcms_form_ask`;
CREATE TABLE `bjmcms_form_ask` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT '',
  `answer` varchar(255) DEFAULT '',
  `createdat` varchar(20) DEFAULT '',
  `asktime` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_ask
--

--
-- 表的结构bjmcms_form_cases
--

DROP TABLE IF EXISTS `bjmcms_form_cases`;
CREATE TABLE `bjmcms_form_cases` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bigpic` varchar(255) DEFAULT '',
  `content` text,
  `code` varchar(255) DEFAULT '',
  `psd` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `jianjie` text,
  `links` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_cases
--

--
-- 表的结构bjmcms_form_download
--

DROP TABLE IF EXISTS `bjmcms_form_download`;
CREATE TABLE `bjmcms_form_download` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT '',
  `fileurl` varchar(255) DEFAULT '',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_download
--

--
-- 表的结构bjmcms_form_feedback
--

DROP TABLE IF EXISTS `bjmcms_form_feedback`;
CREATE TABLE `bjmcms_form_feedback` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_feedback
--

--
-- 表的结构bjmcms_form_guestbook
--

DROP TABLE IF EXISTS `bjmcms_form_guestbook`;
CREATE TABLE `bjmcms_form_guestbook` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `phone` varchar(20) DEFAULT '',
  `content` text,
  `reply` text,
  `ischeck` text,
  `subject` text,
  `company` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `files` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_guestbook
--

--
-- 表的结构bjmcms_form_images
--

DROP TABLE IF EXISTS `bjmcms_form_images`;
CREATE TABLE `bjmcms_form_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `publisher` varchar(50) DEFAULT '小编',
  `images` text,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_images
--

--
-- 表的结构bjmcms_form_jobs
--

DROP TABLE IF EXISTS `bjmcms_form_jobs`;
CREATE TABLE `bjmcms_form_jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_jobs
--

--
-- 表的结构bjmcms_form_page
--

DROP TABLE IF EXISTS `bjmcms_form_page`;
CREATE TABLE `bjmcms_form_page` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` longtext,
  `summary` text,
  `homecontent` longtext,
  `images` text,
  `mobilecontent` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_page
--

--
-- 表的结构bjmcms_form_product
--

DROP TABLE IF EXISTS `bjmcms_form_product`;
CREATE TABLE `bjmcms_form_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `content` text,
  `images` text,
  `mainpic` varchar(255) DEFAULT '',
  `flowchart` varchar(255) DEFAULT '',
  `video` varchar(255) DEFAULT '',
  `videopic` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_product
--

--
-- 表的结构bjmcms_form_reservation
--

DROP TABLE IF EXISTS `bjmcms_form_reservation`;
CREATE TABLE `bjmcms_form_reservation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_form_reservation
--

--
-- 表的结构bjmcms_links
--

DROP TABLE IF EXISTS `bjmcms_links`;
CREATE TABLE `bjmcms_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类型：1=文字链接，2=图片链接',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '网站Logo',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '网站标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '网站地址',
  `description` text COMMENT '网站简况',
  `target` int(1) NOT NULL DEFAULT '0' COMMENT '是否开启浏览器新窗口',
  `email` varchar(50) NOT NULL DEFAULT '',
  `lang` varchar(50) NOT NULL DEFAULT 'cn' COMMENT '语言标识',
  `siteid` int(11) NOT NULL DEFAULT '0' COMMENT '站点目录',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态：1=显示，0=屏蔽',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `delete_at` timestamp NULL DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='友情链接';

--
-- 转存表中的数据 bjmcms_links
--

--
-- 表的结构bjmcms_member_favorites
--

DROP TABLE IF EXISTS `bjmcms_member_favorites`;
CREATE TABLE `bjmcms_member_favorites` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '2019-09-01 10:10:10',
  UNIQUE KEY `cmid` (`member_id`,`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_member_favorites
--

--
-- 表的结构bjmcms_members
--

DROP TABLE IF EXISTS `bjmcms_members`;
CREATE TABLE `bjmcms_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(255) DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) DEFAULT '' COMMENT '手机',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `remember_token` varchar(255) DEFAULT '' COMMENT '记住token',
  `login_ip` varchar(50) DEFAULT '' COMMENT '登录IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `login_at` timestamp NULL DEFAULT NULL COMMENT '最近登录时间',
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户表';

--
-- 转存表中的数据 bjmcms_members
--

--
-- 表的结构bjmcms_migrations
--

DROP TABLE IF EXISTS `bjmcms_migrations`;
CREATE TABLE `bjmcms_migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 bjmcms_migrations
--

INSERT INTO `bjmcms_migrations` VALUES('20180928122842','Rbac','2019-03-07 16:04:38','2019-03-07 16:04:38','0');
INSERT INTO `bjmcms_migrations` VALUES('20190226053124','Users','2019-03-07 16:04:38','2019-03-07 16:04:38','0');
INSERT INTO `bjmcms_migrations` VALUES('20190226055910','Log','2019-03-07 16:04:38','2019-03-07 16:04:38','0');
INSERT INTO `bjmcms_migrations` VALUES('20190312003748','Category','2019-03-12 10:02:01','2019-03-12 10:02:01','0');
INSERT INTO `bjmcms_migrations` VALUES('20190312030116','RoleHasCategories','2019-03-12 11:08:19','2019-03-12 11:08:19','0');
INSERT INTO `bjmcms_migrations` VALUES('20190312055914','Sites','2019-03-12 14:36:26','2019-03-12 14:36:26','0');
INSERT INTO `bjmcms_migrations` VALUES('20190315010253','Models','2019-03-15 11:04:54','2019-03-15 11:04:55','0');
INSERT INTO `bjmcms_migrations` VALUES('20190315021702','Contents','2019-03-15 11:10:18','2019-03-15 11:10:18','0');
INSERT INTO `bjmcms_migrations` VALUES('20190319004723','Fields','2019-03-19 09:25:49','2019-03-19 09:25:49','0');
INSERT INTO `bjmcms_migrations` VALUES('20190411080634','Links','2019-04-11 16:31:47','2019-04-11 16:31:47','0');
INSERT INTO `bjmcms_migrations` VALUES('20190412021418','Banners','2019-04-12 10:22:46','2019-04-12 10:22:46','0');
INSERT INTO `bjmcms_migrations` VALUES('20190703053124','Members','2019-07-03 15:40:47','2019-07-03 15:40:47','0');
--
-- 表的结构bjmcms_model
--

DROP TABLE IF EXISTS `bjmcms_model`;
CREATE TABLE `bjmcms_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '模型名称',
  `table_name` varchar(50) NOT NULL DEFAULT '' COMMENT '表名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1文章 2单页面 3留言 4图片 5产品 6案例 7下载 8报名 9预约 10招聘',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `issystem` tinyint(1) DEFAULT '1' COMMENT '类别 1系统模型 2用户模型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='模型表';

--
-- 转存表中的数据 bjmcms_model
--

INSERT INTO `bjmcms_model` VALUES('1','1','文章','articles','图文内容','1','1','2');
INSERT INTO `bjmcms_model` VALUES('2','1','图组','images','','4','2','2');
INSERT INTO `bjmcms_model` VALUES('3','1','产品','product','','5','3','2');
INSERT INTO `bjmcms_model` VALUES('4','1','案例','cases','','6','4','2');
INSERT INTO `bjmcms_model` VALUES('5','1','下载','download','','7','5','2');
INSERT INTO `bjmcms_model` VALUES('6','1','单页面','page','','2','6','2');
INSERT INTO `bjmcms_model` VALUES('7','1','留言','guestbook','','3','7','2');
INSERT INTO `bjmcms_model` VALUES('8','1','报名','apply','','8','8','2');
INSERT INTO `bjmcms_model` VALUES('9','1','预约','reservation','','9','9','2');
INSERT INTO `bjmcms_model` VALUES('10','1','招聘','jobs','','10','10','2');
INSERT INTO `bjmcms_model` VALUES('11','1','客户反馈','feedback','客户反馈','3','9','2');
INSERT INTO `bjmcms_model` VALUES('12','1','问答','ask','问答','3','9','2');
--
-- 表的结构bjmcms_option_log
--

DROP TABLE IF EXISTS `bjmcms_option_log`;
CREATE TABLE `bjmcms_option_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '模块',
  `controller` varchar(20) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(20) NOT NULL DEFAULT '' COMMENT '方法',
  `option` varchar(50) NOT NULL DEFAULT '' COMMENT '操作',
  `method` varchar(15) NOT NULL DEFAULT '' COMMENT '请求方法',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='操作日志表';

--
-- 转存表中的数据 bjmcms_option_log
--

INSERT INTO `bjmcms_option_log` VALUES('1','mysuperadmin','1','admin','System','clearcache','清除缓存','GET','2019-12-29 09:30:53');
INSERT INTO `bjmcms_option_log` VALUES('2','mysuperadmin','1','admin','System','clearlog','清除日志文件','GET','2019-12-29 09:30:56');
INSERT INTO `bjmcms_option_log` VALUES('3','mysuperadmin','1','admin','Category','index','栏目设置','GET','2019-12-29 09:31:01');
INSERT INTO `bjmcms_option_log` VALUES('4','mysuperadmin','1','admin','Category','getlist','栏目列表','GET','2019-12-29 09:31:01');
INSERT INTO `bjmcms_option_log` VALUES('5','mysuperadmin','1','admin','Content','index','内容管理','GET','2019-12-29 09:31:02');
INSERT INTO `bjmcms_option_log` VALUES('6','mysuperadmin','1','admin','Content','list','内容列表','GET','2019-12-29 09:31:02');
INSERT INTO `bjmcms_option_log` VALUES('7','mysuperadmin','1','admin','Content','getlist','获取内容列表','GET','2019-12-29 09:31:02');
INSERT INTO `bjmcms_option_log` VALUES('8','mysuperadmin','1','admin','Type','index','类型管理','GET','2019-12-29 09:31:04');
INSERT INTO `bjmcms_option_log` VALUES('9','mysuperadmin','1','admin','Type','getlist','类型列表','GET','2019-12-29 09:31:04');
INSERT INTO `bjmcms_option_log` VALUES('10','mysuperadmin','1','admin','Attribute','index','属性管理','GET','2019-12-29 09:31:05');
INSERT INTO `bjmcms_option_log` VALUES('11','mysuperadmin','1','admin','Attribute','getlist','属性列表','GET','2019-12-29 09:31:05');
INSERT INTO `bjmcms_option_log` VALUES('12','mysuperadmin','1','admin','Model','index','模型管理','GET','2019-12-29 09:31:07');
INSERT INTO `bjmcms_option_log` VALUES('13','mysuperadmin','1','admin','Model','getlist','模型列表','GET','2019-12-29 09:31:07');
INSERT INTO `bjmcms_option_log` VALUES('14','mysuperadmin','1','admin','Model','index','模型管理','GET','2019-12-29 09:31:10');
INSERT INTO `bjmcms_option_log` VALUES('15','mysuperadmin','1','admin','Model','getlist','模型列表','GET','2019-12-29 09:31:10');
INSERT INTO `bjmcms_option_log` VALUES('16','mysuperadmin','1','admin','Model','index','模型管理','GET','2019-12-29 09:31:11');
INSERT INTO `bjmcms_option_log` VALUES('17','mysuperadmin','1','admin','Model','getlist','模型列表','GET','2019-12-29 09:31:11');
INSERT INTO `bjmcms_option_log` VALUES('18','mysuperadmin','1','admin','Database','index','数据库管理','GET','2019-12-29 09:31:15');
INSERT INTO `bjmcms_option_log` VALUES('19','mysuperadmin','1','admin','Database','backup','备份数据库','POST','2019-12-29 09:31:16');
--
-- 表的结构bjmcms_permissions
--

DROP TABLE IF EXISTS `bjmcms_permissions`;
CREATE TABLE `bjmcms_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `pid` smallint(6) NOT NULL COMMENT '父级菜单ID',
  `module` varchar(50) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action` varchar(50) DEFAULT NULL COMMENT '方法名称',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 展示 2 隐藏',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_permissions
--

INSERT INTO `bjmcms_permissions` VALUES('1','系统管理','layui-icon-auz','0','admin','admin','index','1','2019-03-15 00:50:03','2019-03-15 00:50:03');
INSERT INTO `bjmcms_permissions` VALUES('2','管理员','','1','admin','user','index','1','2019-07-03 07:21:13','2019-07-03 07:21:13');
INSERT INTO `bjmcms_permissions` VALUES('3','角色管理','','1','admin','role','index','1','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('4','菜单管理','','1','admin','permission','index','1','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('5','创建用户','','2','admin','user','create','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('6','编辑用户','','2','admin','user','edit','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('7','删除用户','','2','admin','user','delete','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('8','创建角色','','3','admin','role','create','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('9','编辑角色','','3','admin','role','edit','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('10','删除角色','','3','admin','role','delete','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('11','获取角色权限','','3','admin','role','getPermissionsOfRole','2','2019-03-08 05:37:22','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('12','分配权限','','3','admin','role','givePermissions','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('13','分配角色','','2','admin','user','giveRoles','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('14','创建菜单','','4','admin','permission','create','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('15','编辑菜单','','4','admin','permission','edit','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('16','删除菜单','','4','admin','permission','delete','2','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('17','日志记录','','1','admin','Log','index','1','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('18','用户列表','','2','admin','user','getList','2','2019-03-08 01:18:16','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('19','角色列表','','3','admin','role','getList','2','2019-03-08 01:18:04','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('20','菜单列表','','4','admin','permission','getList','2','2019-03-08 01:18:09','2019-03-08 08:47:00');
INSERT INTO `bjmcms_permissions` VALUES('22','日志列表','','17','admin','log','getList','2','2019-03-08 05:55:08','2019-03-07 16:04:41');
INSERT INTO `bjmcms_permissions` VALUES('23','内容管理','layui-icon-app','0','admin','content','index','1','2019-03-14 00:57:28','2019-03-14 00:57:28');
INSERT INTO `bjmcms_permissions` VALUES('24','栏目设置','','23','admin','category','index','1','2019-03-12 10:26:37','2019-03-12 10:26:37');
INSERT INTO `bjmcms_permissions` VALUES('25','管理内容','','23','admin','content','index','1','2019-03-12 02:06:14','2019-03-12 02:06:14');
INSERT INTO `bjmcms_permissions` VALUES('26','栏目列表','','24','admin','category','getList','2','2019-03-14 05:49:25','2019-03-14 05:49:25');
INSERT INTO `bjmcms_permissions` VALUES('27','创建栏目','','24','admin','category','create','2','2019-03-14 05:49:34','2019-03-14 05:49:34');
INSERT INTO `bjmcms_permissions` VALUES('28','设置','layui-icon-set','0','admin','upload','','1','2019-03-14 00:55:48','2019-03-14 00:55:48');
INSERT INTO `bjmcms_permissions` VALUES('29','图片上传','','28','admin','upload','image','2','2019-03-14 00:54:33','2019-03-14 00:54:33');
INSERT INTO `bjmcms_permissions` VALUES('30','删除栏目','','24','admin','category','delete','2','2019-03-14 05:49:46','2019-03-14 05:49:46');
INSERT INTO `bjmcms_permissions` VALUES('31','编辑栏目','','24','admin','category','edit','2','2019-03-14 05:50:01','2019-03-14 05:50:01');
INSERT INTO `bjmcms_permissions` VALUES('32','模型管理','','1','admin','model','index','1','2019-03-15 00:48:11','2019-03-15 00:48:11');
INSERT INTO `bjmcms_permissions` VALUES('33','模型列表','','32','admin','model','getList','2','2019-03-15 06:16:03','2019-03-15 06:16:03');
INSERT INTO `bjmcms_permissions` VALUES('34','创建模型','','32','admin','model','create','2','2019-03-15 06:40:49','2019-03-15 06:40:49');
INSERT INTO `bjmcms_permissions` VALUES('35','编辑模型','','32','admin','model','edit','2','2019-03-15 08:03:11','2019-03-15 08:03:11');
INSERT INTO `bjmcms_permissions` VALUES('36','删除模型','','32','admin','model','delete','2','2019-03-15 09:55:36','2019-03-15 09:55:36');
INSERT INTO `bjmcms_permissions` VALUES('37','字段管理','','1','admin','field','index','2','2019-03-19 02:25:10','2019-03-19 02:25:10');
INSERT INTO `bjmcms_permissions` VALUES('38','字段列表','','37','admin','field','getList','2','2019-03-19 02:26:00','2019-03-19 02:26:00');
INSERT INTO `bjmcms_permissions` VALUES('39','创建字段','','37','admin','field','create','2','2019-03-19 02:31:04','2019-03-19 02:31:04');
INSERT INTO `bjmcms_permissions` VALUES('40','取字段默认值','','37','admin','field','defaultValue','2','2019-03-19 03:33:57','2019-03-19 03:33:57');
INSERT INTO `bjmcms_permissions` VALUES('41','编辑字段','','37','admin','field','edit','2','2019-03-20 00:37:20','2019-03-20 00:37:20');
INSERT INTO `bjmcms_permissions` VALUES('42','删除字段','','37','admin','field','delete','2','2019-03-20 00:38:24','2019-03-20 00:38:24');
INSERT INTO `bjmcms_permissions` VALUES('43','内容列表','','25','admin','content','list','2','2019-03-20 07:53:43','2019-03-20 07:53:43');
INSERT INTO `bjmcms_permissions` VALUES('44','内容单页面','','25','admin','content','page','2','2019-03-21 00:38:37','2019-03-21 00:38:37');
INSERT INTO `bjmcms_permissions` VALUES('45','发布内容','','25','admin','content','create','2','2019-03-21 06:53:52','2019-03-21 06:53:52');
INSERT INTO `bjmcms_permissions` VALUES('46','CK上传图片','','28','admin','upload','ckimage','2','2019-03-22 05:54:59','2019-03-22 05:54:59');
INSERT INTO `bjmcms_permissions` VALUES('47','获取内容列表','','25','admin','content','getList','2','2019-03-25 06:07:43','2019-03-25 06:07:43');
INSERT INTO `bjmcms_permissions` VALUES('48','编辑内容','','25','admin','content','edit','2','2019-03-25 07:27:24','2019-03-25 07:27:24');
INSERT INTO `bjmcms_permissions` VALUES('49','删除内容条目','','25','admin','content','delete','2','2019-03-25 08:59:15','2019-03-25 08:59:15');
INSERT INTO `bjmcms_permissions` VALUES('50','分配栏目权限','','3','admin','role','giveCategories','2','2019-03-27 00:59:08','2019-03-27 00:59:08');
INSERT INTO `bjmcms_permissions` VALUES('51','获取角色拥有的栏目','','3','admin','role','getCategoriesOfRole','2','2019-03-27 01:26:48','2019-03-27 01:26:48');
INSERT INTO `bjmcms_permissions` VALUES('52','站点设置','','28','admin','site','index','1','2019-04-11 01:00:45','2019-04-11 01:00:45');
INSERT INTO `bjmcms_permissions` VALUES('53','站点列表','','52','admin','site','getList','2','2019-04-11 01:02:15','2019-04-11 01:02:15');
INSERT INTO `bjmcms_permissions` VALUES('54','创建站点','','52','admin','site','create','2','2019-04-11 01:03:32','2019-04-11 01:03:32');
INSERT INTO `bjmcms_permissions` VALUES('55','编辑站点','','52','admin','site','edit','2','2019-04-11 01:04:13','2019-04-11 01:04:13');
INSERT INTO `bjmcms_permissions` VALUES('56','删除站点','','52','admin','site','delete','2','2019-04-11 01:04:50','2019-04-11 01:04:50');
INSERT INTO `bjmcms_permissions` VALUES('57','编辑我的资料','','2','admin','user','info','2','2019-04-11 06:34:05','2019-04-11 06:34:05');
INSERT INTO `bjmcms_permissions` VALUES('58','友情链接','','28','admin','link','index','1','2019-04-11 08:42:32','2019-04-11 08:42:32');
INSERT INTO `bjmcms_permissions` VALUES('59','友情链接列表','','58','admin','link','getList','2','2019-04-11 08:43:13','2019-04-11 08:43:13');
INSERT INTO `bjmcms_permissions` VALUES('60','创建友情链接','','58','admin','link','create','2','2019-04-11 08:44:12','2019-04-11 08:44:12');
INSERT INTO `bjmcms_permissions` VALUES('61','编辑友情链接','','58','admin','link','edit','2','2019-04-11 08:44:57','2019-04-11 08:44:57');
INSERT INTO `bjmcms_permissions` VALUES('62','删除友情链接','','58','admin','link','delete','2','2019-04-11 08:45:45','2019-04-11 08:45:45');
INSERT INTO `bjmcms_permissions` VALUES('68','留言管理','','25','admin','content','diyForm','2','2019-07-03 01:09:44','2019-07-03 01:09:44');
INSERT INTO `bjmcms_permissions` VALUES('69','获取留言列表','','25','admin','content','getDiyList','2','2019-07-03 01:50:14','2019-07-03 01:50:14');
INSERT INTO `bjmcms_permissions` VALUES('70','删除留言','','25','admin','content','delDiyForm','2','2019-07-03 02:26:51','2019-07-03 02:26:51');
INSERT INTO `bjmcms_permissions` VALUES('71','文件上传','','28','admin','upload','file','2','2019-07-11 01:15:18','2019-07-11 01:15:18');
INSERT INTO `bjmcms_permissions` VALUES('72','编辑留言','','25','admin','content','editDiyform','2','2019-07-09 08:34:23','2019-07-09 08:34:23');
INSERT INTO `bjmcms_permissions` VALUES('73','模板设置','','28','admin','templete','index','1','2019-07-11 00:56:32','2019-07-11 00:56:32');
INSERT INTO `bjmcms_permissions` VALUES('74','模板列表','','73','admin','templete','getList','2','2019-07-11 00:57:53','2019-07-11 00:57:53');
INSERT INTO `bjmcms_permissions` VALUES('75','创建模板','','73','admin','templete','create','2','2019-07-11 00:59:09','2019-07-11 00:59:09');
INSERT INTO `bjmcms_permissions` VALUES('76','编辑模板','','73','admin','templete','edit','2','2019-07-11 00:59:45','2019-07-11 00:59:45');
INSERT INTO `bjmcms_permissions` VALUES('77','删除模板','','73','admin','templete','delete','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('78','清除缓存','','28','admin','system','clearCache','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('79','清除日志文件','','28','admin','system','clearLog','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('80','类型管理','','23','admin','type','index','1','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('81','类型列表','','80','admin','type','getList','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('82','创建类型','','80','admin','type','create','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('83','编辑类型','','80','admin','type','edit','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('84','删除类型','','80','admin','type','delete','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('85','属性管理','','23','admin','attribute','index','1','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('86','属性列表','','85','admin','attribute','getList','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('87','创建属性','','85','admin','attribute','create','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('88','编辑属性','','85','admin','attribute','edit','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('89','删除属性','','85','admin','attribute','delete','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('90','数据库管理','','1','admin','database','index','1','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('91','备份数据库','','90','admin','database','backup','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('92','数据库文件列表','','90','admin','database','databaseList','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('93','数据表文件列表','','90','admin','database','tableList','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('94','删除备份文件','','90','admin','database','delete','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('95','还原数据库','','90','admin','database','import','2','2019-07-11 01:00:53','2019-07-11 01:00:53');
INSERT INTO `bjmcms_permissions` VALUES('96','模板位置管理','','73','admin','position','index','2','2019-09-05 09:43:15','2019-09-05 09:49:49');
INSERT INTO `bjmcms_permissions` VALUES('97','创建模板位置','','73','admin','position','create','2','2019-09-05 09:44:23','2019-09-05 09:44:23');
INSERT INTO `bjmcms_permissions` VALUES('98','编辑模板位置','','73','admin','position','edit','2','2019-09-05 09:45:05','2019-09-05 09:45:05');
INSERT INTO `bjmcms_permissions` VALUES('99','删除模板位置','','73','admin','position','delete','2','2019-09-05 09:46:32','2019-09-05 09:46:32');
INSERT INTO `bjmcms_permissions` VALUES('100','模板位置列表','','73','admin','position','getList','2','2019-09-05 09:47:26','2019-09-05 09:49:59');
INSERT INTO `bjmcms_permissions` VALUES('101','批量操作','','25','admin','content','batch','2','2019-10-12 09:20:01','2019-10-12 09:20:01');
INSERT INTO `bjmcms_permissions` VALUES('102','复制站点','','52','admin','site','copy','2','2019-10-25 01:51:08','2019-10-25 01:51:08');
INSERT INTO `bjmcms_permissions` VALUES('103','导出数据','','25','admin','content','export','2','2019-12-04 10:31:57','2019-12-04 10:31:57');
INSERT INTO `bjmcms_permissions` VALUES('104','价格管理','','23','admin','price','index','2','2019-12-05 09:11:46','2019-12-20 02:48:01');
INSERT INTO `bjmcms_permissions` VALUES('105','产品列表','','104','admin','price','getList','2','2019-12-05 10:28:18','2019-12-05 10:28:18');
INSERT INTO `bjmcms_permissions` VALUES('106','导入价格','','104','admin','price','import','2','2019-12-05 10:58:13','2019-12-05 10:58:13');
INSERT INTO `bjmcms_permissions` VALUES('107','分片上传','','28','admin','upload','uploadFile','2','2019-12-24 01:39:06','2019-12-24 01:39:06');
--
-- 表的结构bjmcms_prices
--

DROP TABLE IF EXISTS `bjmcms_prices`;
CREATE TABLE `bjmcms_prices` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `created_at` date NOT NULL DEFAULT '2019-12-01',
  `year` smallint(4) NOT NULL DEFAULT '2019',
  `month` tinyint(2) NOT NULL DEFAULT '12',
  `day` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`content_id`,`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- 转存表中的数据 bjmcms_prices
--

--
-- 表的结构bjmcms_role_has_categories
--

DROP TABLE IF EXISTS `bjmcms_role_has_categories`;
CREATE TABLE `bjmcms_role_has_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned NOT NULL DEFAULT '1',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `category_id` int(11) NOT NULL COMMENT '栏目ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='角色拥有的栏目权限';

--
-- 转存表中的数据 bjmcms_role_has_categories
--

INSERT INTO `bjmcms_role_has_categories` VALUES('147','1','2','2');
INSERT INTO `bjmcms_role_has_categories` VALUES('148','1','2','3');
INSERT INTO `bjmcms_role_has_categories` VALUES('149','1','2','4');
INSERT INTO `bjmcms_role_has_categories` VALUES('150','1','2','43');
INSERT INTO `bjmcms_role_has_categories` VALUES('151','1','2','44');
INSERT INTO `bjmcms_role_has_categories` VALUES('152','1','2','45');
INSERT INTO `bjmcms_role_has_categories` VALUES('153','1','2','46');
INSERT INTO `bjmcms_role_has_categories` VALUES('154','1','2','47');
INSERT INTO `bjmcms_role_has_categories` VALUES('155','1','2','48');
INSERT INTO `bjmcms_role_has_categories` VALUES('156','1','2','54');
INSERT INTO `bjmcms_role_has_categories` VALUES('157','1','2','5');
INSERT INTO `bjmcms_role_has_categories` VALUES('158','1','2','8');
INSERT INTO `bjmcms_role_has_categories` VALUES('159','1','2','9');
INSERT INTO `bjmcms_role_has_categories` VALUES('160','1','2','25');
INSERT INTO `bjmcms_role_has_categories` VALUES('161','1','2','26');
INSERT INTO `bjmcms_role_has_categories` VALUES('162','1','2','27');
INSERT INTO `bjmcms_role_has_categories` VALUES('163','1','2','28');
INSERT INTO `bjmcms_role_has_categories` VALUES('164','1','2','13');
INSERT INTO `bjmcms_role_has_categories` VALUES('165','1','2','14');
INSERT INTO `bjmcms_role_has_categories` VALUES('166','1','2','15');
INSERT INTO `bjmcms_role_has_categories` VALUES('167','1','2','16');
INSERT INTO `bjmcms_role_has_categories` VALUES('168','1','2','17');
INSERT INTO `bjmcms_role_has_categories` VALUES('169','1','2','18');
INSERT INTO `bjmcms_role_has_categories` VALUES('170','1','2','19');
INSERT INTO `bjmcms_role_has_categories` VALUES('171','1','2','20');
INSERT INTO `bjmcms_role_has_categories` VALUES('172','1','2','21');
INSERT INTO `bjmcms_role_has_categories` VALUES('173','1','2','22');
INSERT INTO `bjmcms_role_has_categories` VALUES('174','1','2','23');
INSERT INTO `bjmcms_role_has_categories` VALUES('175','1','2','29');
INSERT INTO `bjmcms_role_has_categories` VALUES('176','1','2','30');
INSERT INTO `bjmcms_role_has_categories` VALUES('177','1','2','31');
INSERT INTO `bjmcms_role_has_categories` VALUES('178','1','2','32');
INSERT INTO `bjmcms_role_has_categories` VALUES('179','1','2','33');
INSERT INTO `bjmcms_role_has_categories` VALUES('180','1','2','34');
INSERT INTO `bjmcms_role_has_categories` VALUES('181','1','2','35');
INSERT INTO `bjmcms_role_has_categories` VALUES('182','1','2','36');
INSERT INTO `bjmcms_role_has_categories` VALUES('183','1','2','37');
INSERT INTO `bjmcms_role_has_categories` VALUES('184','1','2','38');
INSERT INTO `bjmcms_role_has_categories` VALUES('185','1','2','39');
INSERT INTO `bjmcms_role_has_categories` VALUES('186','1','2','40');
INSERT INTO `bjmcms_role_has_categories` VALUES('187','1','2','41');
INSERT INTO `bjmcms_role_has_categories` VALUES('188','1','2','42');
INSERT INTO `bjmcms_role_has_categories` VALUES('189','1','2','55');
INSERT INTO `bjmcms_role_has_categories` VALUES('190','1','2','58');
INSERT INTO `bjmcms_role_has_categories` VALUES('191','1','2','59');
INSERT INTO `bjmcms_role_has_categories` VALUES('192','1','2','57');
INSERT INTO `bjmcms_role_has_categories` VALUES('193','1','2','56');
INSERT INTO `bjmcms_role_has_categories` VALUES('194','1','2','0');
INSERT INTO `bjmcms_role_has_categories` VALUES('234','1','1','1');
INSERT INTO `bjmcms_role_has_categories` VALUES('235','1','1','2');
INSERT INTO `bjmcms_role_has_categories` VALUES('236','1','1','9');
INSERT INTO `bjmcms_role_has_categories` VALUES('237','1','1','10');
INSERT INTO `bjmcms_role_has_categories` VALUES('238','1','1','11');
INSERT INTO `bjmcms_role_has_categories` VALUES('239','1','1','3');
INSERT INTO `bjmcms_role_has_categories` VALUES('240','1','1','12');
INSERT INTO `bjmcms_role_has_categories` VALUES('241','1','1','13');
INSERT INTO `bjmcms_role_has_categories` VALUES('242','1','1','14');
INSERT INTO `bjmcms_role_has_categories` VALUES('243','1','1','15');
INSERT INTO `bjmcms_role_has_categories` VALUES('244','1','1','30');
INSERT INTO `bjmcms_role_has_categories` VALUES('245','1','1','31');
INSERT INTO `bjmcms_role_has_categories` VALUES('246','1','1','4');
INSERT INTO `bjmcms_role_has_categories` VALUES('247','1','1','16');
INSERT INTO `bjmcms_role_has_categories` VALUES('248','1','1','17');
INSERT INTO `bjmcms_role_has_categories` VALUES('249','1','1','18');
INSERT INTO `bjmcms_role_has_categories` VALUES('250','1','1','19');
INSERT INTO `bjmcms_role_has_categories` VALUES('251','1','1','20');
INSERT INTO `bjmcms_role_has_categories` VALUES('252','1','1','5');
INSERT INTO `bjmcms_role_has_categories` VALUES('253','1','1','21');
INSERT INTO `bjmcms_role_has_categories` VALUES('254','1','1','22');
INSERT INTO `bjmcms_role_has_categories` VALUES('255','1','1','23');
INSERT INTO `bjmcms_role_has_categories` VALUES('256','1','1','24');
INSERT INTO `bjmcms_role_has_categories` VALUES('257','1','1','25');
INSERT INTO `bjmcms_role_has_categories` VALUES('258','1','1','26');
INSERT INTO `bjmcms_role_has_categories` VALUES('259','1','1','27');
INSERT INTO `bjmcms_role_has_categories` VALUES('260','1','1','28');
INSERT INTO `bjmcms_role_has_categories` VALUES('261','1','1','29');
INSERT INTO `bjmcms_role_has_categories` VALUES('262','1','1','6');
INSERT INTO `bjmcms_role_has_categories` VALUES('263','1','1','7');
INSERT INTO `bjmcms_role_has_categories` VALUES('264','1','1','8');
INSERT INTO `bjmcms_role_has_categories` VALUES('265','1','1','32');
INSERT INTO `bjmcms_role_has_categories` VALUES('266','1','1','33');
INSERT INTO `bjmcms_role_has_categories` VALUES('267','1','1','34');
INSERT INTO `bjmcms_role_has_categories` VALUES('268','1','1','0');
--
-- 表的结构bjmcms_role_has_permissions
--

DROP TABLE IF EXISTS `bjmcms_role_has_permissions`;
CREATE TABLE `bjmcms_role_has_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `permission_id` int(11) NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2262 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_role_has_permissions
--

INSERT INTO `bjmcms_role_has_permissions` VALUES('1819','2','1');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1820','2','90');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1821','2','91');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1822','2','92');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1823','2','93');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1824','2','94');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1825','2','95');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1826','2','23');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1827','2','24');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1828','2','26');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1829','2','27');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1830','2','30');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1831','2','31');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1832','2','25');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1833','2','43');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1834','2','44');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1835','2','45');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1836','2','47');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1837','2','48');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1838','2','49');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1839','2','68');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1840','2','69');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1841','2','70');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1842','2','72');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1843','2','101');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1844','2','103');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1845','2','28');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1846','2','29');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1847','2','46');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1848','2','52');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1849','2','53');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1850','2','55');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1851','2','58');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1852','2','59');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1853','2','60');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1854','2','61');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1855','2','62');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1856','2','71');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1857','2','78');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1858','2','79');
INSERT INTO `bjmcms_role_has_permissions` VALUES('1859','2','0');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2160','1','1');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2161','1','2');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2162','1','5');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2163','1','6');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2164','1','7');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2165','1','13');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2166','1','18');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2167','1','57');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2168','1','3');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2169','1','8');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2170','1','9');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2171','1','10');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2172','1','11');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2173','1','12');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2174','1','19');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2175','1','50');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2176','1','51');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2177','1','4');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2178','1','14');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2179','1','15');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2180','1','16');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2181','1','20');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2182','1','17');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2183','1','22');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2184','1','32');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2185','1','33');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2186','1','34');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2187','1','35');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2188','1','36');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2189','1','37');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2190','1','38');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2191','1','39');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2192','1','40');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2193','1','41');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2194','1','42');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2195','1','90');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2196','1','91');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2197','1','92');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2198','1','93');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2199','1','94');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2200','1','95');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2201','1','23');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2202','1','24');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2203','1','26');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2204','1','27');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2205','1','30');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2206','1','31');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2207','1','25');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2208','1','43');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2209','1','44');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2210','1','45');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2211','1','47');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2212','1','48');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2213','1','49');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2214','1','68');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2215','1','69');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2216','1','70');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2217','1','72');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2218','1','101');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2219','1','103');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2220','1','80');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2221','1','81');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2222','1','82');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2223','1','83');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2224','1','84');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2225','1','85');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2226','1','86');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2227','1','87');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2228','1','88');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2229','1','89');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2230','1','104');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2231','1','105');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2232','1','106');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2233','1','28');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2234','1','29');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2235','1','46');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2236','1','52');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2237','1','53');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2238','1','54');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2239','1','55');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2240','1','56');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2241','1','102');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2242','1','58');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2243','1','59');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2244','1','60');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2245','1','61');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2246','1','62');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2247','1','71');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2248','1','73');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2249','1','74');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2250','1','75');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2251','1','76');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2252','1','77');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2253','1','96');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2254','1','97');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2255','1','98');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2256','1','99');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2257','1','100');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2258','1','78');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2259','1','79');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2260','1','107');
INSERT INTO `bjmcms_role_has_permissions` VALUES('2261','1','0');
--
-- 表的结构bjmcms_roles
--

DROP TABLE IF EXISTS `bjmcms_roles`;
CREATE TABLE `bjmcms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_roles
--

INSERT INTO `bjmcms_roles` VALUES('1','超级管理员','2019-03-07 16:04:41','2019-03-07 16:04:41');
INSERT INTO `bjmcms_roles` VALUES('2','管理员','2019-03-07 08:20:39','2019-03-07 16:04:41');
--
-- 表的结构bjmcms_site
--

DROP TABLE IF EXISTS `bjmcms_site`;
CREATE TABLE `bjmcms_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '站点名称',
  `logo` varchar(255) DEFAULT '' COMMENT '站点Logo',
  `seo_title` varchar(255) DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(512) DEFAULT '' COMMENT 'SEO描述',
  `domain` varchar(128) DEFAULT '' COMMENT '绑定域名',
  `mark` varchar(128) NOT NULL DEFAULT '' COMMENT 'en、zh、cn、guangzhou',
  `default_style` varchar(128) NOT NULL DEFAULT '' COMMENT '站点风格',
  `dirname` varchar(30) DEFAULT '' COMMENT '站点目录',
  `code` text COMMENT '统计代码',
  `notice` text COMMENT '站点公告',
  `setting` text COMMENT '其他配置',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0关闭站点 1启用站点',
  `isdefault` tinyint(1) DEFAULT '0',
  `telephone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `wechat` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `footer_code` text,
  `company` varchar(255) DEFAULT NULL,
  `point_x` varchar(20) DEFAULT NULL,
  `point_y` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mark` (`mark`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='站点设置';

--
-- 转存表中的数据 bjmcms_site
--

INSERT INTO `bjmcms_site` VALUES('1','BJMCMS','','bjmcms','bjmcms','bjmcms','bjmcms','chs','bjmcms','chs','','周一到周五8：30~17：30','','1','1','1','','','','','bjmcms','','','','','bjmcms','','');
--
-- 表的结构bjmcms_sms_log
--

DROP TABLE IF EXISTS `bjmcms_sms_log`;
CREATE TABLE `bjmcms_sms_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sms_type` varchar(50) NOT NULL DEFAULT '' COMMENT '短信供应商 如 dysms',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '短信类型 如 register',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `params` varchar(3000) NOT NULL DEFAULT '' COMMENT '参数(json)',
  `out_id` varchar(255) DEFAULT '0' COMMENT '返回的第三方id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `mobile` (`mobile`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信日志表';

--
-- 转存表中的数据 bjmcms_sms_log
--

INSERT INTO `bjmcms_sms_log` VALUES('1','clsms','register','13553810187','{\"code\":\"3286\"}','20191220032912','1576826993');
--
-- 表的结构bjmcms_sms_template
--

DROP TABLE IF EXISTS `bjmcms_sms_template`;
CREATE TABLE `bjmcms_sms_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sms_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT '短信类型 local | qcloudsms | dysms | clsms | htsms',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '业务类型(如register | find_password)',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '短信标题',
  `template_id` varchar(50) NOT NULL DEFAULT '' COMMENT '短信模板ID',
  `params` varchar(500) NOT NULL DEFAULT '' COMMENT '短信参数（''code'', ''time'' | {1}, {2}）',
  `template` varchar(500) NOT NULL DEFAULT '' COMMENT '短信模板内容',
  PRIMARY KEY (`id`),
  KEY `sms_type` (`sms_type`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信模板';

--
-- 转存表中的数据 bjmcms_sms_template
--

INSERT INTO `bjmcms_sms_template` VALUES('1','local','register','用户注册','local_register','code','您好，欢迎注册，您的手机验证码是：${code}，若非本人操作，请忽略！');
INSERT INTO `bjmcms_sms_template` VALUES('2','dysms','register','用户注册','SMS_13092xxxx','code','您好，欢迎注册，您的手机验证码是：${code}，若非本人操作，请忽略！');
INSERT INTO `bjmcms_sms_template` VALUES('3','qcloudsms','register','用户注册','158xxx','{1}','您好，欢迎注册，您的手机验证码是：{1}，若非本人操作，请忽略！');
--
-- 表的结构bjmcms_templete_positions
--

DROP TABLE IF EXISTS `bjmcms_templete_positions`;
CREATE TABLE `bjmcms_templete_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `siteid` int(11) DEFAULT '0',
  `param_name` varchar(255) DEFAULT NULL COMMENT '模板调用变量名',
  `type` tinyint(1) DEFAULT '1' COMMENT '调用数据类型: 1模板 2栏目 3单页 4列表',
  `sort` int(5) DEFAULT '9' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 0停用 1启用',
  `class` tinyint(1) DEFAULT '1' COMMENT '1电脑 2手机',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_templete_positions
--

--
-- 表的结构bjmcms_templetes
--

DROP TABLE IF EXISTS `bjmcms_templetes`;
CREATE TABLE `bjmcms_templetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '幻灯片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `description` text NOT NULL COMMENT '备注',
  `target` int(1) NOT NULL DEFAULT '0' COMMENT '是否开启浏览器新窗口',
  `siteid` int(11) NOT NULL DEFAULT '1' COMMENT '站点ID',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态：1=显示，0=屏蔽',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `update_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `position_id` int(11) DEFAULT '0',
  `cid` int(11) DEFAULT NULL COMMENT '推荐栏目',
  `type` text COMMENT '0默认 1推荐 2新品 3精品 4热门',
  `iscat` tinyint(1) DEFAULT NULL COMMENT '是否分栏目展示',
  `limit` int(11) DEFAULT NULL COMMENT '数据条数',
  `thumpic` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='首页幻灯片';

--
-- 转存表中的数据 bjmcms_templetes
--

--
-- 表的结构bjmcms_types
--

DROP TABLE IF EXISTS `bjmcms_types`;
CREATE TABLE `bjmcms_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `attributes` text,
  `istop` tinyint(3) DEFAULT '0',
  `pic` varchar(255) DEFAULT NULL,
  `group` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='类型';

--
-- 转存表中的数据 bjmcms_types
--

--
-- 表的结构bjmcms_user_has_roles
--

DROP TABLE IF EXISTS `bjmcms_user_has_roles`;
CREATE TABLE `bjmcms_user_has_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 bjmcms_user_has_roles
--

INSERT INTO `bjmcms_user_has_roles` VALUES('67','2','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('68','3','1');
INSERT INTO `bjmcms_user_has_roles` VALUES('81','4','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('82','5','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('83','6','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('84','7','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('85','8','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('86','9','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('87','10','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('88','11','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('89','12','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('90','13','2');
INSERT INTO `bjmcms_user_has_roles` VALUES('91','1','1');
--
-- 表的结构bjmcms_users
--

DROP TABLE IF EXISTS `bjmcms_users`;
CREATE TABLE `bjmcms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `remember_token` varchar(255) NOT NULL DEFAULT '' COMMENT '记住token',
  `login_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `login_at` timestamp NULL DEFAULT NULL COMMENT '最近登录时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户表';

--
-- 转存表中的数据 bjmcms_users
--

INSERT INTO `bjmcms_users` VALUES('1','mysuperadmin','875376798@qq.com','$2y$10$v0MiZ7VpSXdrK/EErguOG.is4rmNEO85kYyPjtQzxGsgmAQMkFC6W','null','120.229.143.239','2019-03-07 16:04:41','2019-12-29 08:54:51');
