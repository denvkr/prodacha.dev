<?php
/**
 * Обработчик оплаты заказа через PayOnline
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['SecurityKey'])) {
    $order_metod="Payonlinesystem";
    $success_function=true; // Включаем функцию обновления статуса заказа
    $PrivateSecurityKey=$SysValue['payonlinesystem']['PrivateSecurityKey'];
    $MerchantId=$SysValue['payonlinesystem']['MerchantId'];
    $crc = $_GET["SecurityKey"];
    $my_crc = md5("DateTime=$_GET[DateTime]&TransactionID=$_GET[TransactionID]&OrderId=$_GET[OrderId]&Amount=$_GET[Amount]&Currency=$_GET[Currency]&PrivateSecurityKey=$PrivateSecurityKey");
    $inv_id = $_GET['OrderId'];
    $out_summ = $_GET['Amount'];


    // Переход в личный кабинет
    if(!empty($_SESSION['UsersId']))
        header("Location: ".$SysValue['dir']['dir']."/users/order.html?orderId=".$_POST['ouid']."#PphpshopOrder");
    else header("Location: ".$SysValue['dir']['dir']."/clients/?mail=".$_POST['mail']."&order=".$_POST['ouid']."");

}

?>