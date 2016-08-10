<?php
/**
 * Модуль расширения для учета прикрепленных файлов к товарам
 * Для включения переименовать файл в addfiles.php
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// Добавление полного пути к файлам
function mod_addfiles_path($files){
    foreach($files as $f)
        $files_with_path[]=$GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Files/".$f;
    
    return $files_with_path;
}

// Персонализация обновления
function mod_update($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {

        if (strstr($CsvToArray[17], ','))
            $files = explode(',', $CsvToArray[17]);
        else
            $files[] = $CsvToArray[17];

        return "files='". serialize(mod_addfiles_path($files)) . "', ";
    }
}

// Персонализация вставки
function mod_insert($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {

        if (strstr($CsvToArray[17], ','))
            $files = explode(',', $CsvToArray[17]);
        else
            $files[] = $CsvToArray[17];

        return "files='". serialize(mod_addfiles_path($files)) . "', ";
    }
}

?>