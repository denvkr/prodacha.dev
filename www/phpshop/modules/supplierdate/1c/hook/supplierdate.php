<?php


// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort']=18;
}

// �������������� ����������
function mod_update($CsvToArray, $class_name, $func_name) {
    return 'mod_supplier_date="' . $CsvToArray[17] . '", ';
}

// �������������� �������
function mod_insert($CsvToArray, $class_name, $func_name) {
    return 'mod_supplier_date="' . $CsvToArray[17] . '", ';
}

?>