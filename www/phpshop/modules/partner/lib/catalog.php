<?php

$_classPath = "../../../../";
include($_classPath . "phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("modules");

// Подключаем БД
$PHPShopBase = new PHPShopBase($_classPath . "phpshop/inc/config.ini");

$PHPShopModules = new PHPShopModules('../../');

// Пример запроса
$_POST['sql_test'] = '<?xml version="1.0" encoding="windows-1251"?>
<phpshop><sql><from>table_name2</from>
<method>select</method>
<vars>name,id,items,price,ed_izm,pic_small,category,newtip,spec,baseinputvaluta,price2</vars>
<where>category=55 and enabled="1"</where>
<order>num</order><limit>1000</limit></sql></phpshop>';
$_POST['log_test'] = "root";
$_POST['pas_test'] = "cm9vdHJvb3Q=";
$_POST['key_test'] = "123456789";

class PHPShopHtmlCatalog extends PHPShopBaseXml {

    function PHPShopHtmlCatalog() {
        $this->debug = false;
        $this->true_method = array('select', 'option');
        $this->true_from = array('table_name', 'table_name2', 'table_name3', 'table_name24', 'table_name19', '');
        $this->log = $_POST['log'];
        $this->pas = $_POST['pas'];
        $this->key = $_POST['key'];
        $this->url = $_POST['url'];
        $this->system();
        parent::PHPShopBaseXml();
    }

    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_system']);
        $this->data = $PHPShopOrm->select();
    }

    function admin() {
        if ($this->data['key_enabled'] == 1) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_key']);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('url'), array('url_key' => "='" . $this->key . "'"), false, array('limit' => 1));
            if (is_array($data)) {
                if ($this->url == $data['url'])
                    return true;
            }
        }
        else
            return true;
    }

}

new PHPShopHtmlCatalog();
?>