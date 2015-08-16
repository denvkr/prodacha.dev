-----------------------
-- PHPShop
-- Module Install SQL
-----------------------

DROP TABLE IF EXISTS `phpshop_modules_facebookpage_system`;
CREATE TABLE `phpshop_modules_facebookpage_system` (
`id` TINYINT( 1 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`skin` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;



INSERT INTO `phpshop_modules_facebookpage_system` ( `id` , `skin` )
VALUES (
NULL , 'aqua'
);

