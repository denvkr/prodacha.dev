

--
-- Структура таблицы `phpshop_modules_sqloptimizer_system`
--

DROP TABLE IF EXISTS `phpshop_modules_sqloptimizer_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_sqloptimizer_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;



INSERT INTO `phpshop_modules_admlog_system` VALUES (1,'0','');
