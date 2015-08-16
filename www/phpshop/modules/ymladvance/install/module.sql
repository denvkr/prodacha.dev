
CREATE TABLE `phpshop_modules_ymladvance_system` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(64) NOT NULL default '',
  `vendor` blob NOT NULL,
  `warranty_enabled` enum('0','1') NOT NULL default '1',
  `version` float(2) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 ;

-- 
-- Дамп данных таблицы `phpshop_modules_ymladvance_system`
-- 

INSERT INTO `phpshop_modules_ymladvance_system` VALUES (1, '', '', '0', '1.1');
      