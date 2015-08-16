<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function ROBOXchange       |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['crc'])){
$order_metod="Robox";
$success_function=true; // ¬ключаем функцию обновлени€ статуса заказа
$mrh_login = $SysValue['roboxchange']['mrh_login'];    //логин
$mrh_pass1 = $SysValue['roboxchange']['mrh_pass1'];    // пароль1
$crc = strtoupper($_GET['crc']); 
$my_crc = strtoupper(md5("$_GET[out_summ]:$_GET[inv_id]:$mrh_pass1"));
$inv_id = $_GET['inv_id'];
$out_summ = $_GET['out_summ'];
}
?>
