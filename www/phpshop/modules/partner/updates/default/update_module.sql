
ALTER TABLE `phpshop_modules_partner_system` ADD `version` FLOAT(2) DEFAULT '1.6' NOT NULL;

DROP TABLE IF EXISTS `phpshop_modules_partner_key`;
CREATE TABLE `phpshop_modules_partner_key` (
 `id` int(11) NOT NULL auto_increment,
 `partner_id` INT( 11) NOT NULL ,
 `url` VARCHAR( 256) NOT NULL ,
 `date` INT( 11) NOT NULL ,
 `url_key` VARCHAR( 256) NOT NULL ,
 PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
