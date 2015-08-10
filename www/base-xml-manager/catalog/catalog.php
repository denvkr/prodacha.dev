<?php

include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");

// Подключаем БД
$PHPShopBase=new PHPShopBase("../../phpshop/inc/config.ini");

// Пример запроса
/*
$_POST['sql_test']='<?xml version="1.0" encoding="windows-1251"?>
<phpshop><sql><from>table_name2</from>
<method>select</method>
<vars>name,id,items,price,ed_izm,pic_small,category,newtip,spec,baseinputvaluta,price2</vars>
<where>category=55 and enabled="1"</where>
<order>num</order><limit>1000</limit></sql></phpshop>';
$_POST['log_test']="root";
$_POST['pas_test']="cm9vdHJvb3Q=";
*/

class PHPShopHtmlCatalog extends PHPShopBaseXml {

    function PHPShopHtmlCatalog() {
        $this->debug=false;
        $this->true_method=array('select','option');
        $this->true_from=array('table_name','table_name2','table_name3','table_name24','');
        $this->log=$_POST['log'];
        $this->pas=$_POST['pas'];
        parent::PHPShopBaseXml();
    }

    function admin() {
        $PHPShopOrm=new PHPShopOrm($this->PHPShopBase->getParam('base.table_name19'));
        $PHPShopOrm->debug = $this->debug;
        $data=$PHPShopOrm->select(array('login,password'),array('enabled'=>"='1'"),false,10);
        $OkFlag=false;
        if(is_array($data)) {
            foreach($data as $v)
                if($_POST['log'] == $v['login'] and $_POST['pas'] == $v['password'])
                    $OkFlag=true;
        }
        return $OkFlag;
    }
}

new PHPShopHtmlCatalog();
?>