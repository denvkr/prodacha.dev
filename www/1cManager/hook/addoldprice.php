<?php
/**
 * ������ ���������� ��� ����� ������ ��� � ������� �� ������ ������ � ������������� ���� � ��������
 * ��� ��������� ������������� ���� � addoldprice.php
 * @author PHPShop Software
 * @version 1.0
 */
// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort'] = 19;
}


// �������������� ����������
function mod_update($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {
        return "price_n='". $CsvToArray[17] . "', ";
    }
    if (!empty($CsvToArray[18])) {
        return "items=". $CsvToArray[18] . ", ";
    }
}

// �������������� �������
function mod_insert($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {
        return "price_n='". $CsvToArray[17] . "', ";
    }
    if (!empty($CsvToArray[18])) {
        return "items=". $CsvToArray[18] . ", ";
    }
}

?>