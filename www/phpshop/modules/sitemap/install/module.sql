-----------------------
-- PHPShop
-- Module Install SQL
-----------------------

DROP TABLE IF EXISTS `phpshop_modules_sitemap_system`;
CREATE TABLE `phpshop_modules_sitemap_system` (
  `id` int(11) NOT NULL auto_increment,
  `seo_enabled` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_sitemap_system` VALUES (1,'0','');
