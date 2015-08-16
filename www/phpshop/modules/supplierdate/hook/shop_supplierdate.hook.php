<?php

/**
 * Добавление переменной вывода даты поставки в подробное описание
 */
function UID_mod_supplierdate_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        if ($GLOBALS['mod_supplier_option']['enabled'] == 1) {
            if (!empty($_SESSION['UsersId']) and $row['items'] < 1 and !empty($row['mod_supplier_date']))
                $obj->set('productSklad', 'срок пост. ' . $row['mod_supplier_date'] . ' раб. дней');
            elseif ($row['items'] < 1 and !empty($row['mod_supplier_date']))
                $obj->set('productSklad', 'срок пост. ' . $row['mod_supplier_date'] . ' раб. дней');
        }
    }
}

$addHandler = array
    (
    'UID' => 'UID_mod_supplierdate_hook'
);
?>