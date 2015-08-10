
ALTER TABLE `phpshop_categories` ADD `icon` VARCHAR(255) NOT NULL;
ALTER TABLE `phpshop_categories` ADD `icon_description` VARCHAR(255) NOT NULL;

--
-- Структура таблицы `phpshop_modules_iconcat_system`
--

DROP TABLE IF EXISTS `phpshop_modules_iconcat_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_iconcat_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_iconcat_system` VALUES (1,'');