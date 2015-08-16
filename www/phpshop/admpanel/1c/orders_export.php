<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("delivery");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();


if (isset($_GET['orderID'])) {
    $PHPShopOrder = new PHPShopOrderFunction($_GET['orderID']);

    $sql = "select * from " . $SysValue['base']['table_name1'] . " where id=" . intval($_GET['orderID']);
    $result = mysql_query($sql);


    $num = 0;
    $csv1 = "Начало личных данных;;;;;;;;;;\n";
    $csv2 = "Начало заказанных товаров;;;;;;;;;;\n";
    $csv3 = "Начало данных доставки;;;;;;;;;;\n";
    $row = mysql_fetch_array($result);

    if (!is_array($row))
        die('Заказа с таким номером не существует');

    $id = $row['id'];
    $datas = $row['datas'];
    $uid = $row['uid'];
    $order = unserialize($row['orders']);
    $status = unserialize($row['status']);
    $num = $row['num'];
    $mail = $order['Person']['mail'];
    $name = $order['Person']['name_person'];
    $conpany = str_replace("&quot;", "", $order['Person']['org_name']);
    $inn = $order['Person']['org_inn'];
    $kpp = $order['Person']['org_kpp'];
    $tel = $order['Person']['tel_name'];
    $user = $row['user'];
    $adres = str_replace("&quot;", "", $order['Person']['adr_name']);
    $adres = PHPShopSecurity::CleanOut($adres);
    $oplata = $PHPShopOrder->getOplataMetodName();
    $sum = $PHPShopOrder->returnSumma($order['Cart']['sum'], $order['Person']['discount']);
    $discount = $order['Person']['discount'];
    $weight = $order['Cart']['weight'];

    $csv1.="$uid;$datas;$mail;$name;$conpany;$tel;$oplata;$sum;$discount;$inn;$adres;$kpp;$user;\n";

    if (is_array($order['Cart']['cart']))
        foreach ($order['Cart']['cart'] as $val) {
            $id = $val['id'];
            $uid = $val['uid'];
            $num = $val['num'];
            $sum = $PHPShopOrder->returnSumma($val['price'] * $num, $order['Person']['discount']);

            // Нахождение кода валюты
            $valuta = $PHPShopOrder->getValutaIso($id);
            $csv2.="$id;$uid;$num;$sum;$valuta;;;;;;\n";
        }

    // Доставка

    $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
    $csv3.=$PHPShopDelivery->getCity() . ";" . $PHPShopDelivery->getPrice($sum, $weight) . ";" . $valuta . "\n";

    $csv = $csv1 . $csv2 . $csv3;

    $file = $row['uid'] . ".csv";
    @$fp = fopen("../csv/" . $file, "w+");
    if ($fp) {
        fputs($fp, $csv);
        fclose($fp);
    }
    header("Location: ../csv/" . $file);
}
?>