

DROP TABLE IF EXISTS `phpshop_modules_yescredit_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_yescredit_system` (
  `id` int(11) NOT NULL auto_increment,
  `payment_id` tinyint(11) NOT NULL default '0',
  `MERCHANT_ID` int(11) NOT NULL default '0',
  `serial` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `phpshop_modules_yescredit_system` VALUES (1,25, 777777, '');

INSERT INTO `phpshop_payment_systems` VALUES (25, 'Оформить кредит (Yes Credit)', 'message', '1', 0, 'Заявка на кредит отправлена в банк, очень скоро с вами свяжутся представители банка.', 'Спасибо за заказ');
