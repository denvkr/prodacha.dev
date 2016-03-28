-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.45-log - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица u301639.phpshop_product_promo_relation
CREATE TABLE IF NOT EXISTS `phpshop_product_promo_relation` (
  `product_id` int(6) NOT NULL COMMENT 'ссылка на товар',
  `promo_id` int(6) NOT NULL COMMENT 'ссылка на промокод',
  KEY `product_id_indx` (`product_id`) USING BTREE,
  KEY `promo_id_indx` (`promo_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='таблица отношений товаров и скидо на товары через ID';

-- Экспортируемые данные не выделены.


-- Дамп структуры для таблица u301639.phpshop_promocode
CREATE TABLE IF NOT EXISTS `phpshop_promocode` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'идентификатор промокода',
  `promocode` varchar(10) DEFAULT NULL COMMENT 'промокод',
  `discountprice` int(10) DEFAULT NULL COMMENT 'цена с учетом скидки',
  KEY `id` (`id`) USING BTREE,
  KEY `promocode` (`promocode`) USING HASH,
  KEY `discountprice` (`discountprice`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='таблица промокодов скидок для товаров';

-- Экспортируемые данные не выделены.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
