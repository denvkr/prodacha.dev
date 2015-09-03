<?php
/**
 * Доставка
 * @package PHPShopAjaxElements
 */
session_start();
$_classPath="../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();

// Модули
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// Подключаем библиотеку поддержки.
require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Подключаем библиотеку доставки
require_once $_classPath."core/order.core/delivery.php";


function GetDeliveryPrice($deliveryID,$sum,$weight=0) {
    global $SysValue;
	
	
    if(!empty($deliveryID)) {
        if ($_COOKIE['sincity']=="sp" && $deliveryID==69) {
            if ($sum>=1000 && $sum<=4999) {
                $sql="select id,city,300 as price,enabled,flag,price_null,price_null_enabled,PID,taxa,is_folder from ".$SysValue['base']['table_name30']." where id='$deliveryID' ".$usl." and enabled='1'";
            }
            if ($sum>=5000){
                $sql="select id,city,price,enabled,flag,price_null,price_null_enabled,PID,taxa,is_folder from ".$SysValue['base']['table_name30']." where id='$deliveryID' ".$usl." and enabled='1'";                            
            }
        } else {
                $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' ".$usl." and enabled='1'";            
        }
        $result=mysql_query($sql);
        $num=mysql_numrows($result);
        $row = mysql_fetch_array($result);
        
        if($num == 0) {
            $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' ".$usl."  and enabled='1'";
            $result=mysql_query($sql);
            $row = mysql_fetch_array($result);
        }
    }
    else {
        $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' ".$usl."  and enabled='1'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
    }
    
    if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
        return 0;
    } else {
        if ($row['taxa']>0) {
            $addweight=$weight-500;
            if ($addweight<0) {
                $addweight=0;
                $at='';
            } else {
                $at='';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight=ceil($addweight/500)*$row['taxa'];
            $endprice=$row['price']+$addweight;
            return $at.$endprice;
        } else {
            return $row['price'];
        }
    }
}

$GetDeliveryPrice=GetDeliveryPrice($_REQUEST['xid'],$_REQUEST['sum'],$_REQUEST['wsum']);
$totalsumma=$PHPShopOrder->returnSumma($_REQUEST['sum']+$GetDeliveryPrice,$PHPShopOrder->ChekDiscount($_REQUEST['sum']));
$dellist=delivery(false,$_REQUEST['xid'],$totalsumma-$GetDeliveryPrice);

// Результат
$_RESULT = array(
        'delivery' => $GetDeliveryPrice,
        'dellist'=> $dellist,
        'total' => $totalsumma,
        'city' => $_COOKIE['sincity']
); 

// Перехват модуля в начале функции
$hook=$PHPShopModules->setHookHandler('delivery','delivery', $_RESULT, $_REQUEST['xid']);
if($hook) $_RESULT=$hook;
?>
