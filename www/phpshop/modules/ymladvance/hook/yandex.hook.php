<?php

function setProducts_ymladvance_hook($obj, $xml) {


    // Гарантия производителя
    if (!empty($_SESSION['module']['ymladvance']['warranty_enabled'])) {
        $warranty = '
   <manufacturer_warranty>true</manufacturer_warranty>
</offer>
';

        $xml = str_replace('</offer>', $warranty, $xml);
    }

    return $xml;
}

function PHPShopYml_ymladvance_hook($obj) {

    // Настройки модуля
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['ymladvance']['ymladvance_system']);
    $option = $PHPShopOrm->select();


    if (!empty($vendor['sort1_name']))
        $obj->vendor = true;

    $vendor = unserialize($option['vendor']);

    $vendor_array[$vendor['sort1_tag']] = $vendor['sort1_name'];
    $vendor_array[$vendor['sort2_tag']] = $vendor['sort2_name'];
    $vendor_array[$vendor['sort3_tag']] = $vendor['sort3_name'];
    $vendor_array[$vendor['sort4_tag']] = $vendor['sort4_name'];

    if (!empty($vendor['sort1_name'])) {
        $obj->vendor = true;

        $obj->vendor_name = $vendor_array;
    }

    if (!empty($option['warranty_enabled']))
        $_SESSION['module']['ymladvance']['warranty_enabled'] = 1;
}

$addHandler = array
    (
    'setProducts' => 'setProducts_ymladvance_hook',
    'PHPShopYml' => 'PHPShopYml_ymladvance_hook'
);
?>