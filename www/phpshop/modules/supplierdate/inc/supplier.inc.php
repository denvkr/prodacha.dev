<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopSupplierArray extends PHPShopArray {
    function PHPShopSupplierArray() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['supplierdate']['supplierdate_system'];
        parent::PHPShopArray("enabled");
    }
}

$PHPShopSupplierArray = new PHPShopSupplierArray();
$GLOBALS['mod_supplier_option'] = $PHPShopSupplierArray->getArray();

?>