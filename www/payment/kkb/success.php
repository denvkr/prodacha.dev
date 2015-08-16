<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function KKB           |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['pg_order_id'])) {
    $order_metod="KKB";
    $success_function=true; // Включаем функцию обновления статуса заказа
    $PrivateSecurityKey=$SysValue['platron']['secret_key'];
    $MerchantId=$SysValue['platron']['merchant_id'];
    $crc = $_GET["pg_sig"];
    $my_crc = md5('success.html;'.$_GET['amount'].';'.$_GET['pg_order_id'].';'.$_GET['pg_payment_id'].';'.$_GET['pg_salt'].';'.$PrivateSecurityKey);
    $inv_id = $_GET['pg_order_id'];
    $out_summ = $_GET['amount'];
}

?>