<?php

/**
 * Календарь
 * @package PHPShopAjaxElements
 */

session_start();

$_classPath="../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopSystem = new PHPShopSystem();
PHPShopObj::loadClass('parser');

// Подключаем библиотеку поддержки.
require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Подключаем функцию
include($_classPath.'core/news.core/calendar.php');

$calres=calendar(false,$_REQUEST['year'],$_REQUEST['month']);

$_RESULT = array(
        'calres' => $calres
); 
?>