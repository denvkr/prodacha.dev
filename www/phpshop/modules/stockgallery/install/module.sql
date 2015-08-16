
 
DROP TABLE IF EXISTS `phpshop_modules_stockgallery_system`;
CREATE TABLE `phpshop_modules_stockgallery_system` (
  `id` int(11) NOT NULL auto_increment,
  `enabled` enum('0','1') NOT NULL default '1',
  `width` int(11) NOT NULL default '0',
  `limit` tinyint(11) NOT NULL default '0',
  `img_height` int(11) NOT NULL default '0',
  `img_width` int(11) NOT NULL default '0',
  `border` int(11) NOT NULL default '1',
  `border_color` varchar(64) NOT NULL default '',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;



INSERT INTO `phpshop_modules_stockgallery_system` VALUES (1, '1', 470, 10, 0, 100, 2, 'eee', '');