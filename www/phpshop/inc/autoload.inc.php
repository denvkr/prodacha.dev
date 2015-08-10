<?php
/**
 * Автозагрузка элементов
 * @package PHPShopInc
 */

// Защищаем от дублей /index.php/index.php
if (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
    header('Location: /error/');
    exit();
}

// Шаблон дизайна по умолчанмю
$PHPShopCoreElement = new PHPShopCoreElement();
$PHPShopCoreElement->init('skin',false,false);
$PHPShopCoreElement->init('checkskin');
$PHPShopCoreElement->init('setdefault');

// Выбор шаблона
$PHPShopSkinElement = new PHPShopSkinElement();
$PHPShopSkinElement->init('skinSelect');

// Стили шаблона дизайна
$PHPShopCoreElement->init('pageCss',false,false);

// Загрузка модулей
PHPShopObj::loadClass('modules');
$PHPShopModules = new PHPShopModules();
$PHPShopModules->doLoad();

// Подключаем файлы autoload
foreach ($SysValue['autoload'] as $val)
    if (is_file($val))
        include_once($val);

// Подключаем поиск брендов
//$PHPShopSortElement = new PHPShopSortElement();
//$PHPShopSortElement->brand('brand',14,'Бренды');

// Авторизация пользователей
$PHPShopUserElement = new PHPShopUserElement();
$PHPShopUserElement->init('usersDisp');

// Новинки в центр
$PHPShopProductIndexElements = new PHPShopProductIndexElements();
$PHPShopProductIndexElements->init('specMain');

// Новинки в колонку
$PHPShopProductIconElements = new PHPShopProductIconElements();
$PHPShopProductIconElements->init('specMainIcon');

// Последние покупки
$PHPShopProductIndexElements->init('nowBuy');

// Меню каталогов
$PHPShopShopCatalogElement = new PHPShopShopCatalogElement();
$PHPShopShopCatalogElement->init('leftCatal');
$PHPShopShopCatalogElement->init('leftCatalTable');

// Меню каталогов страниц
$PHPShopPageCatalogElement = new PHPShopPageCatalogElement();
$PHPShopPageCatalogElement->init('pageCatal');

// Опрос
$PHPShopOprosElement = new PHPShopOprosElement();
$PHPShopOprosElement->init('oprosDisp');

// Мини-новости
$PHPShopNewsElement = new PHPShopNewsElement();
$PHPShopNewsElement->init('miniNews');

// Баннер
$PHPShopBannerElement = new PHPShopBannerElement();
$PHPShopBannerElement->init('banersDisp');

// Облако тегов
$PHPShopCloudElement = new PHPShopCloudElement();
$PHPShopCloudElement->init('cloud');

// Текстовый блок
$PHPShopTextElement = new PHPShopTextElement();
$PHPShopTextElement->init('leftMenu', true); // Вывод левого блока
$PHPShopTextElement->init('rightMenu', true); // Вывод правого блока
$PHPShopTextElement->init('topMenu', true); // Вывод главного меню
// Выбор валюты
$PHPShopCurrencyElement = new PHPShopCurrencyElement();
$PHPShopCurrencyElement->init('valutaDisp');

// Корзина
$PHPShopCartElement = new PHPShopCartElement();
$PHPShopCartElement->init('miniCart');

// RSS грабер новостей
$PHPShopRssParser = new PHPShopRssParser();
?>