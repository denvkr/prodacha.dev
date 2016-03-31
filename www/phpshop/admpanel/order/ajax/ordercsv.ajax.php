<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_errors', true);
$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("delivery");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

/*
  // Тестирование
  $_GET['do']='add';
  $_POST['name']='Тестовый товар';
  $_POST['uid']='53';
  $_POST['xid']='1';
  $_POST['num']=10;
 */

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopValutaArray = new PHPShopValutaArray();

// Load JsHttpRequest backend.
require_once $_classPath . "lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
$PHPShopOrm->debug = false;

//извлекаю все записи из orders
$order=$PHPShopOrm->select(array('*'), false, false);
$cnt=0;
//читаем данные в цикле в файл
if (is_array($order)) {
    foreach($order as $order_item) {
        $order_elem = unserialize($order_item['orders']);
        $status = unserialize($order_item['status']);
        //первый раз получаем заголовоки колонок
        if (!isset($csv_head)) {
           $csv_head=array_keys($order_elem);
           $csv_head_result=array_merge($csv_head,array_keys($order_elem['Person']));
           $csv_data='datas|uid|';
           $csv_data.=implode ( '|', $csv_head_result );
           $csv_data.="|maneger|time|user|seller|statusi\r\n";
           
        }
        $csv_data.=$order_item['datas'].'|';
        $csv_data.=$order_item['uid'].'|';
        $csv_data.=implode ( '|',$order_elem['Cart']);
        $csv_data.='||'.preg_replace('/\r\n?/', "", implode ( '|',$order_elem['Person']));
        $csv_data.='|'.implode( '|',$status).'|';
        $csv_data.=$order_item['user'].'|';
        $csv_data.=$order_item['seller'].'|';
        $csv_data.=$order_item['statusi'];
        $csv_data.="\r\n";
        $cnt++;
        //if ($cnt==20) break;
    }
    //экспортируем все в файл
    $fp=fopen( $_REQUEST['file'].'orders'.date('YmdGi').'.csv' ,'w+' );
    fwrite($fp, $csv_data);
    fclose($fp);
    $retval='Экспорт успешно завершен в '.str_replace('../','',$_REQUEST['file']).'orders'.date('YmdGi').'.csv';
} else
      $retval='При экспорте возникли ошибки';  

$_RESULT = array(
    "content" => $retval
);