<?php
session_start();
global $PHPShopOrder;
$SysValue = parse_ini_file('../inc/config.ini', 1);
global $SysValue;
include_once('../class/obj.class.php');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass('orm');
PHPShopObj::loadClass('cart');
PHPShopObj::loadClass('product');
PHPShopObj::loadClass('order');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('elements');

//инициализируем класс корзины order
//PHPShopObj::loadClass('order');

$PHPShopBase=new PHPShopBase('../inc/config.ini',true);
$PHPShopSystem=new PHPShopSystem();

// Массив валют
$PHPShopValutaArray= new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Корзина
$PHPShopCart = new PHPShopCart();

//база даных, запросы
$PHPShopOrm=new PHPShopOrm();

/**
 * серверная часть ajax promocode
 * @author denvkr
 * @tutorial https://trello.com/c/dy3WB3Rz/49--
 * @version 1.0
 */

$promocode_existence=$PHPShopOrm->query('select count(promocode) from phpshop_promocode where promocode=\''.$_REQUEST['promocode'].'\'');
$promocode_existence_row=mysql_fetch_array($promocode_existence, MYSQL_NUM);
//выбираем промокод из базы
$mas['item1'] = $promocode_existence_row[0];
$cnt=0;
$request_arr=array();
$normalize_arr=array();
$result_arr=array();
foreach ($_REQUEST as $key=>$val){
    if ($cnt>0)
        array_push($request_arr, array(str_replace('_','',strrchr($key,'_'))=>$val));
    else
        array_push($request_arr, array($key=>$val));
    $cnt++;
}
for( $cnt=1;$cnt<=count($request_arr); $cnt++ ){
    if ($cnt>0 && $cnt%2==0) {
        array_push($normalize_arr,array($request_arr[$cnt-1],$request_arr[$cnt]));
    }
}
//получаем массив с ценами для всех товаров корзины
foreach ($normalize_arr as $normalize_arr_item) {
    $key=array_keys($normalize_arr_item[0]);
    $promocode_existence=$PHPShopOrm->query('select discountprice from phpshop_promocode ppc join phpshop_product_promo_relation pppr on ppc.id=pppr.promo_id where promocode=\''.$_REQUEST['promocode'].'\' and pppr.product_id='.$key[0]);
    $promocode_existence_row=mysql_fetch_array($promocode_existence, MYSQL_NUM);
    if (!empty($promocode_existence_row[0])) {
        $result_arr[]=array($key[0],$promocode_existence_row[0]);
        //переписываем значение цены в корзине для товаров с промкодом
        //$key_cart=array_keys($PHPShopCart->getArray());
        if ( ($_COOKIE['sincity']=="sp") AND ($PHPShopCart->getArray()[$key[0]]['price2']!=0) ) {
                $tovar_price='price2';
        } else if( ($_COOKIE['sincity']=="chb") AND ($PHPShopCart->getArray()[$key[0]]['price3']!=0) ) {
                $tovar_price='price3';
        }
        else {
                $tovar_price='price';
        }
        $PHPShopCart->setArray($key[0], $tovar_price, $PHPShopCart->getArray()[$key[0]]['discountprice']);
    }
}

$mas['item2']=$result_arr;
foreach ($PHPShopCart->getArray() as $cartitem){
    if ( ($_COOKIE['sincity']=="sp") AND ($cartitem['price2']!=0) ) {
            $tovar_price=$cartitem['price2'];
    } else if( ($_COOKIE['sincity']=="chb") AND ($cartitem['price3']!=0) ) {
            $tovar_price=$cartitem['price3'];
    }
    else {
            $tovar_price=$cartitem['price'];
    }
    $total+=($tovar_price*$cartitem['num']);
}
//считаем сумму по корзине
//проходим по всей корзине и считаем тотал
//
$mas['total'] = $total;
//var_dump($PHPShopCart->getArray());
//echo $total;
echo json_encode($mas);


function WinToUtf8($data)
{
        if (is_array($data))
        {
                $d = array();
                foreach ($data as $k => &$v) $d[WinToUtf8($k)] = WinToUtf8($v);
                return $d;
        }
        if (is_string($data))
        {
                if (function_exists('iconv')) return iconv('cp1251', 'utf-8//IGNORE//TRANSLIT', $data);
                if (! function_exists('cp1259_to_utf8')) include_once 'cp1259_to_utf8.php';
                return WinToUtf8($data);
        }
        if (is_scalar($data) or is_null($data)) return $data;
        #throw warning, if the $data is resource or object:
        trigger_error('An array, scalar or null type expected, ' . gettype($data) . ' given!', E_USER_WARNING);
        return $data;
}
?>