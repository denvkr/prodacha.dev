-----------------------
-- PHPShop CMS Free
-- Module Install SQL
-----------------------

--
-- Структура таблицы `phpshop_modules_stat_sebots`
--

DROP TABLE IF EXISTS `phpshop_modules_stat_sebots`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_stat_sebots` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `useragent` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL,
  `qvar` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `phpshop_modules_stat_sebots`
--

INSERT INTO `phpshop_modules_stat_sebots` (`id`, `name`, `useragent`, `host`, `qvar`) VALUES
(1, 'Google', 'google', 'htt(p|ps):\\/\\/www.google', 'q'),
(2, 'Яндекс', 'yandex', 'http:\\/\\/yandex', 'text'),
(3, 'Рамблер', 'rambler', 'http:\\/\\/nova.rambler', 'query');

--
-- Структура таблицы `phpshop_modules_stat_visitors`
--

DROP TABLE IF EXISTS `phpshop_modules_stat_visitors`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_stat_visitors` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(20) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `referer_host` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `request_uri` varchar(255) NOT NULL,
  `sebot_id` int(11) NOT NULL default '0',
  `seword` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `gyear` int(11) NOT NULL,
  `gmonth` int(11) NOT NULL,
  `gweek` int(11) NOT NULL,
  `gday` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`),
  KEY `timestamp` (`timestamp`),
  KEY `gyear` (`gyear`),
  KEY `gmonth` (`gmonth`),
  KEY `gweek` (`gweek`),
  KEY `gday` (`gday`),
  KEY `referer_host` (`referer_host`),
  KEY `seword` (`seword`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;

