

DROP TABLE IF EXISTS `phpshop_modules_pickpoint_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_pickpoint_system` (
  `id` int(11) NOT NULL auto_increment,
  `city` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `type_service` varchar(64) NOT NULL default '',
  `type_reception` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_pickpoint_system` VALUES (1,'PickPoint','������� ��������� ����� ������','STD','CUR','');
