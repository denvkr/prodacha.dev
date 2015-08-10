<?php
/**
 * Вывод характеристик в поиске
 * @package PHPShopAjaxElements
 */

session_start();
$_classPath="../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");
PHPShopObj::loadClass("sort");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("text");

require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");


class PHPShopSortAjax extends PHPShopSort{

    function display(){
         return PHPShopText::td($this->disp);
    }

}

$PHPShopSortAjax = new PHPShopSortAjax($_REQUEST['category']);

$_RESULT = array(
        'sort' =>$PHPShopSortAjax->display()
);
 
?>