<?php


// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort']=18;
}

// Персонализация обновления
function mod_update($CsvToArray, $class_name, $func_name) {
    return 'mod_supplier_date="' . $CsvToArray[17] . '", ';
}

// Персонализация вставки
function mod_insert($CsvToArray, $class_name, $func_name) {
    return 'mod_supplier_date="' . $CsvToArray[17] . '", ';
}

?>