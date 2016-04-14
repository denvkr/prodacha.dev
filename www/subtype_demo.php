<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//инициализируем настройки системы
$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
global $SysValue;
include_once('phpshop/class/obj.class.php');
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

$PHPShopBase=new PHPShopBase('phpshop/inc/config.ini',true);
//$PHPShopSystem=new PHPShopSystem();

// Массив валют
$PHPShopValutaArray= new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

//база даных, запросы
$PHPShopOrm=new PHPShopOrm($PHPShopBase->getParam('base.products') );
$PHPShopOrm->debug=true;
echo $PHPShopBase->getParam('base.products');
//настраиваем запрос
$retval=$PHPShopOrm->select(array('id','name','price'), array('id'=>' in (388,301,302)'), array('order' => ' id DESC'));
//$data=  mysql_fetch_assoc($retval);
$div='<div style="position:absolute;width:270px;height:40px;overflow-y: scroll;"><table>';
foreach ($retval as $data_item){
    $table_item.='<tr style=""><td>'.$data_item['id'].'</td><td>'.$data_item['name'].'</td><td>'.$data_item['price'].'</td></tr>';
}

$jscript='<script>'
        . ''
        . ''
        . ''
        . ''
        . '</script>';
$styles='<style>'
        . 'table tr:hover{'
        . 'background-color:blue;'
        . '}'
        . '</style>';
$div.=$table_item.'</table></div>'.$styles;
echo $div;