# Host: 127.0.0.1:33060  (Version 5.7.22-0ubuntu18.04.1)
# Date: 2019-03-20 11:40:03
# Generator: MySQL-Front 6.1  (Build 1.26)


#
# Structure for table "mkcms_model"
#

CREATE TABLE `mkcms_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '模型名称',
  `table_name` varchar(50) NOT NULL DEFAULT '' COMMENT '表名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类别 1用户模型 2系统模型',
  `sort` int(6) NOT NULL DEFAULT '9' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='模型表';

#
# Data for table "mkcms_model"
#

INSERT INTO `mkcms_model` VALUES (1,1,'文章模型','article','文章模型',1,2),(2,1,'产品模型','product','产品模型',1,2),(3,1,'图片模型','image','图片模型',1,3),(4,1,'单页模型','page','空模型',1,3),(5,2,'文章模型','article_en','文章模型',1,2),(6,2,'产品模型','product_en','产品模型',1,2),(7,2,'图片模型','image_en','图片模型',1,3),(8,2,'单页模型','page_en','空模型',1,3);
