<?php
/**
 * Модуль расширения для учета старых цен к товарам на основе скидки и дополнительно поля в выгрузке
 * Для включения переименовать файл в addoldprice.php
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 19;
}


// Персонализация обновления
function mod_update($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {
        return "price_n='". $CsvToArray[17] . "', ";
    }
    if (!empty($CsvToArray[18])) {
        return "items=". $CsvToArray[18] . ", ";
    }
}

// Персонализация вставки
function mod_insert($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {
        return "price_n='". $CsvToArray[17] . "', ";
    }
    if (!empty($CsvToArray[18])) {
        return "items=". $CsvToArray[18] . ", ";
    }
}

?>