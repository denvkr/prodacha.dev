<?php

// Библиотека работы с почтой
PHPShopObj::loadClass("mail");

// Номер заказа для счет-фактуры
function GetNumOrders($cid) {
    $sql="select uid from ".$GLOBALS['SysValue']['base']['table_name9']." where cid='$cid'";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    return $row['uid'];
}

// Сообщение пользователю
function SendMailUser($id,$flag="accounts") {

    // Счет-фактура
    if($cid=="invoice") $id = GetNumOrders($id);

    $sql="select * from ".$GLOBALS['SysValue']['base']['table_name1']." where id=$id";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    $order=unserialize($row['orders']);
    $mail=$order['Person']['mail'];
    $name=$order['Person']['name_person'];
    $uid=$row['uid'];
    $zag="Бухгалтерские документы по заказу №".$row['uid'];
    $from="robot@".str_replace("www.","",$_SERVER['SERVER_NAME']);
    $content="Доброго времени!
--------------------------------------------------------

Уважаемый(ая) пользователь ".$name.", по заказу №".$uid." стали доступны 
бухгалтерские документы в личном кабинете.";

    if($row['user']==1) {
        $content.="

Вы всегда можете проверить статус заказа, загрузить файлы, распечатать платежные 
документы он-лайн через 'Личный кабинет' или по ссылке http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/users/";
    }
    else {
        $content.="
Вы всегда можете проверить статус заказа, загрузить файлы, распечатать платежные 
документы он-лайн по ссылке http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/clients/?mail=".$mail."&order=".$uid."
E-mail: ".$mail."
№ Заказа: ".$uid;
    }

    $content.="

Дата: ".date("d-m-y H:s a")."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$GLOBALS['SysValue']['license']['product_name'];

    // Отправление сообщения
    $PHPShopMail = new PHPShopMail($mail,$from,$zag,$content);
}
?>