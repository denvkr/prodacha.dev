<?php
session_start();
global $PHPShopOrder;
$SysValue = parse_ini_file('../inc/config.ini', 1);
global $SysValue;
include_once('../class/obj.class.php');
// Подключаем библиотеку поддержки.
//require_once "../lib/Subsys/JsHttpRequest/Php.php";

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
//$PHPShopSystem=new PHPShopSystem();

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
 * @tutorial https://trello.com/c/WqIAmdGc/88--
 * @version 1.0
 */

//$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

$promocode_existence=$PHPShopOrm->query('select count(promocode),promo_counter from phpshop_promocode where promocode=\''.$_REQUEST['promocode'].'\' LIMIT 1');
//echo mysql_num_rows($promocode_existence);
if (mysql_num_rows($promocode_existence)>0) {
    $promocode_existence_row=mysql_fetch_array($promocode_existence, MYSQL_NUM);
    //echo $promocode_existence_row[1];
    //заносим клик по промокоду
    $PHPShopOrm->query('UPDATE phpshop_promocode SET promo_counter='.++$promocode_existence_row[1].' where promocode=\''.$_REQUEST['promocode'].'\'');
    //выводим азвание товара для промокода
    $promocode_name=$PHPShopOrm->query('select name from phpshop_promocode ppc join phpshop_product_promo_relation pppr on ppc.id=pppr.promo_id join phpshop_products pp on pp.id=pppr.product_id where ppc.promocode=\''.$_REQUEST['promocode'].'\'');
    $promocode_name_row=mysql_fetch_array($promocode_name, MYSQL_NUM);
    //echo $promocode_name_row[0];
} else {
    $promocode_existence_row[0]=0;
}
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
$mas['prod_name'] = cp1251_utf8($promocode_name_row[0]);//$promocode_name_row[0];
$mas['total'] = $total;

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
function cp1251_utf8( $sInput )
{
    $sOutput = "";

    for ( $i = 0; $i < strlen( $sInput ); $i++ )
    {
        $iAscii = ord( $sInput[$i] );

        if ( $iAscii >= 192 && $iAscii <= 255 )
            $sOutput .=  "&#".( 1040 + ( $iAscii - 192 ) ).";";
        else if ( $iAscii == 168 )
            $sOutput .= "&#".( 1025 ).";";
        else if ( $iAscii == 184 )
            $sOutput .= "&#".( 1105 ).";";
        else
            $sOutput .= $sInput[$i];
    }
   
    return $sOutput;
}
?>