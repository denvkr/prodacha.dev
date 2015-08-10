

DROP TABLE IF EXISTS `phpshop_modules_supplierdate_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_supplierdate_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '1',
  `serial` varchar(64) NOT NULL default '',
  `version` FLOAT(2) DEFAULT '1.0' NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


INSERT INTO `phpshop_modules_supplierdate_system` VALUES (1,'1','','1.0');

ALTER TABLE `phpshop_products` ADD `mod_supplier_date` VARCHAR(64) NOT NULL;
ALTER TABLE `phpshop_orders` ADD `mod_supplier_file` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_orders` ADD `mod_supplier_info` VARCHAR(255) NOT NULL;