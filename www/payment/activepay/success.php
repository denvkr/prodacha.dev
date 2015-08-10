<?php
/**
 * Обработчик оплаты заказа через Activepay
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


if(isset($_GET['merchant_data'])){
$order_metod="Activepay";
$success_function=true; // Включаем функцию обновления статуса заказа

// Регистрационная информация
$secret_key=$SysValue['activepay']['secret_key'];
$merchant_contract=$SysValue['activepay']['merchant_contract'];
$merchant_data=explode("-",$_GET['merchant_data']);

// Номер заказа
$inv_id = $merchant_data[0];

// Сумма заказа
$out_summ = $merchant_data[1];


// Библиотека с функциями
include_once("payment/activepay/lib.php");

$crc = $_GET["signature"];

// Подпись
$my_crc=sign("GET", $_SERVER['SERVER_NAME'], "/success/", $secret_key,array(
        "result" => "success",
        "merchant_data" => $_GET['merchant_data'],
        "payment_id" => $_GET['payment_id'],
));

}
?>