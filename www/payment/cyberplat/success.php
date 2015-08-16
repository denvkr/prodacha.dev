<?
/**
 * Обработчик оплаты заказа через CyberPlat
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['inv'])) {
    $order_metod="rbs";
    $my_crc = "NoN";
    $crc = "NoN";
    $success_function=false; // Выключаем функцию обновления статуса заказа
    $d_hash1=base64_decode($_GET['inv']);
    $mH=substr($d_hash1,2,strlen($d_hash1));
    $mT=substr($mH,0,strlen($mH)-5);
    $inv_id=base64_decode($mT);
}
?>