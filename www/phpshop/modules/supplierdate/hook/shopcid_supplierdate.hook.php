<?php

/**
 * Добавление переменной вывода даты поставки в краткое описание
 */
function checkStore_mod_supplierdate_hook($obj, $row) {
    if ($GLOBALS['mod_supplier_option']['enabled'] == 1) {
        if (!empty($_SESSION['UsersId'])  and $row['items']<1 and !empty($row['mod_supplier_date']))
            $obj->set('productSklad', $row['mod_supplier_date'].' раб. дней');
    }
    elseif($row['items']<1 and !empty($row['mod_supplier_date']))
        $obj->set('productSklad', $row['mod_supplier_date'].' раб. дней');
    
    return true;
}

$addHandler = array
    (
    'checkStore' => 'checkStore_mod_supplierdate_hook'
);
?>