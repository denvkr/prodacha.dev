<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function Z-payment         |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_REQUEST['LMI_PAYMENT_NO'])){
$order_metod="Z-payment";
$success_function=true; // Выключаем функцию обновления статуса заказа
$my_crc = "NoN";
$crc = "NoN";
$inv_id = $_REQUEST['LMI_PAYMENT_NO'];
$out_summ=0;
}

?>
