-- --------------------------------------------------------

-- 
-- Структура таблицы `phpshop_modules_socauth_system`
-- 

DROP TABLE IF EXISTS `phpshop_modules_socauth_system`;
CREATE TABLE IF NOT EXISTS `phpshop_modules_socauth_system` (
  `id` smallint(1) NOT NULL default '0',
  `authConfig` blob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- Дамп данных таблицы `phpshop_modules_socauth_system`
-- 

INSERT INTO `phpshop_modules_socauth_system` (`id`, `authConfig`) VALUES (1, '');
