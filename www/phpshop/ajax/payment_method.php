<?php

/**
 * Корзина
 * @package PHPShopAjaxElements
 */
//session_start();
$_classPath = "../";
include_once($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
//PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("payment");
//include_once($_classPath . "core/order.core.php");

// Подключаем библиотеку поддержки.
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

$PHPShopOrder = new PHPShopPaymentArray();

//if(PHPShopSecurity::true_param($_REQUEST['region'],$_REQUEST['delivery'])) 
$payment_method=$PHPShopOrder->payment_output($_REQUEST['delivery']);
//$payment_method='<div id="order_metod_div" style="display:table-cell;"><input type="radio" name="order_metod" value="26" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>testtest</label><br></div>';
// Формируем результат
$_RESULT = array(
    "payment_method" => $payment_method
);
?>