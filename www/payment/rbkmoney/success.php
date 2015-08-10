<?php
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function RBKMoney   |
+-------------------------------------+
*/


if (empty($GLOBALS['SysValue']))
    exit(header("Location: /"));

// Определение платежной системы по $_GET['payment']
if (!empty($_REQUEST['payment']))
    if ($_REQUEST['payment'] == 'rbkmoney') {
        $order_metod = "rbkmoney";
        $success_function = false; // Выключаем функцию обновления статуса заказа, операция уже выполнена в result.php
        $my_crc = "NoN";
        $crc = "NoN";
        $inv_id = $_GET['inv_id'];
    }
?>