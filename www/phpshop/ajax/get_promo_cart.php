<?php
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
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('elements');

//инициализируем класс корзины order
//PHPShopObj::loadClass('order');

$PHPShopBase=new PHPShopBase('../inc/config.ini',true);
$PHPShopSystem=new PHPShopSystem();
$PHPShopOrm=new PHPShopOrm();

/**
 * серверная часть ajax promocode
 * @author denvkr
 * @tutorial https://trello.com/c/dy3WB3Rz/49--
 * @version 1.0
 */

$promocode_existence=$PHPShopOrm->query('select count(promocode) from phpshop_promocode where promocode=\''.$_POST['promocode'].'\'');
$promocode_existence_row=mysql_fetch_array($promocode_existence, MYSQL_NUM);
//выбираем промокод из базы
$mas['item1'] = $promocode_existence_row[0];
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