<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function MainPay           |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['service_id'])){
$order_metod="Mainpay";
$success_function=true; // Включаем функцию обновления статуса заказа
$SecretKey=$SysValue['mainpay']['SecretKey'];
$crc = $_GET["check"];
$data = array(
    'tid'            => $_GET['tid'],
    'name'           => $_GET['name'],
    'comment'        => $_GET['comment'],
    'partner_id'     => $_GET['partner_id'],
    'service_id'     => $_GET['service_id'],
    'order_id'       => $_GET['order_id'],
    'type'           => $_GET['type'],
    'partner_income' => $_GET['partner_income'],
    'system_income'  => $_GET['system_income'],
    $SecretKey,
    );
$my_crc = md5(join("",$data));
$inv_id = $_GET['comment'];
$out_summ = $_GET['system_income'];
}

?>
