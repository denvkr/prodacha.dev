<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
PHPShopObj::loadClass('product');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('elements');

$PHPShopBase=new PHPShopBase('../inc/config.ini',true);

$PHPShopSystem=new PHPShopSystem();

// ћассив валют
$PHPShopValutaArray= new PHPShopValutaArray();

//база даных, запросы
$PHPShopOrm=new PHPShopOrm($PHPShopBase->getParam('base.products'));

/**
 * серверна€ часть ajax podtip
 * @author denvkr
 * @tutorial https://trello.com/c/jNuM6vfr/91--
 * @version 1.0
 */
$PHPShopOrm->cache=false;
$podtip_price=$PHPShopOrm->select(array('price','price2','price3'), array('id'=>'='.$_REQUEST['prod_podtip_id']), false,array('limit'=>1));
        //переписываем значение цены в корзине дл€ товаров с промкодом
        if ( ($_COOKIE['sincity']=="sp") AND ($podtip_price['price2']!=0) ) {
                $tovar_price=$podtip_price['price2'];
        } else if( ($_COOKIE['sincity']=="chb") AND ($podtip_price['price3']!=0) ) {
                $tovar_price=$podtip_price['price3'];
        }
        else {
                $tovar_price=$podtip_price['price'];
        }
$mas['prod_podtip_price']=$tovar_price;
//echo $mas['prod_podtip_price'];
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
