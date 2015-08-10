<?php
/**
 * Изменение сортировки товаров на склад
 * @author PHPShop Software
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter_skladsort_hook($obj) {
 
    $sort = null;
    $n = $obj->category;
 
    $v = $_REQUEST['v'];
    $s = PHPShopSecurity::TotalClean($_REQUEST['s'], 1);
    $f = PHPShopSecurity::TotalClean($_REQUEST['f'], 1);
 
    if ($obj->PHPShopNav->isPageAll())
        $p = PHPShopSecurity::TotalClean($p, 1);
 
    if ($obj->PHPShopCategory->getParam('num_row') > 0)
        $num_row = $obj->PHPShopCategory->getParam('num_row');
    else
        $num_row = $obj->PHPShopSystem->getValue('num_row');
 
    // Сортировка по характеристикам
    if (is_array($v)) {
        foreach ($v as $key => $value) {
            if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                $hash = $key . "-" . $value;
                $sort.=" and vendor REGEXP 'i" . $hash . "i' ";
            }
        }
    }
 
    // Сортировки товаров в каталоге по складу в убывающем порядке
    if (empty($f)){
        $order_direction = " desc";
        $order = array('order' => 'items' . $order_direction);
    }
 
 
    // Сортировка принудительная пользователем
    if ($s or $f) {
        switch ($f) {
            case(1): $order_direction = "";
                break;
            case(2): $order_direction = " desc";
                break;
            default: $order_direction = " desc";
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                break;
            case(2): $order = array('order' => 'price' . $order_direction);
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                break;
            default: $order = array('order' => 'uid' . $order_direction);
        }
    }
 
    // Учет добавочных категорий
    $catt = '(category=' . $n . ' OR dop_cat LIKE \'%#' . $n . '#%\')';
 
    // Преобзазуем массив уловия сортировки в строку
    foreach ($order as $key => $val)
        $string = $key . ' by ' . $val;
 
    // Все страницы
    if ($obj->PHPShopNav->isPageAll()) {
        $sql = " ($catt and enabled='1' and parent_enabled='0') " . $sort . " " . $string . ' limit ' . $obj->max_item;
    }
 
    // Поиск по цене
    elseif (isset($_POST['priceSearch'])) {
 
        $priceOT = TotalClean($_POST['priceOT'], 1);
        $priceDO = TotalClean($_POST['priceDO'], 1);
 
        $percent = $obj->PHPShopSystem->getValue('percent');
 
        // Бесконечность
        if ($priceDO == 0)
            $priceDO = 1000000000;
 
        if (empty($priceOT))
            $priceOT = 0;
 
        // Цена с учетом выбранной валюты
        $priceOT/=$obj->currency('kurs');
        $priceDO/=$obj->currency('kurs');
 
        $sql = "$catt and enabled='1' and parent_enabled='0' and price >= " .
                ($priceOT / (100 + $percent) * 100) . " AND price <= " . ($priceDO / (100 + $percent) * 100) . " " . $sort . $string . ' limit 0,' . $obj->max_item;
    }
    elseif (!empty($sort)) {
        return array('sql' => $catt . " and enabled='1' and parent_enabled='0' " . $sort . $string);
    } else {
        return array('sql' => $catt . " and enabled='1' and parent_enabled='0' " . $sort . $string);
    }
 
 
    // Возвращаем SQL 
    return $sql;
}
 
$addHandler = array
    (
    'query_filter' => 'query_filter_skladsort_hook'
);
?>