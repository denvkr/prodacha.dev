<?php

/**
 * Настройка модуля
 */
function pickpoin_option() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['pickpoint']['pickpoint_system']);
    return $PHPShopOrm->select();
}

/**
 * Поиск доставки по имени
 */
function search_pickpoin_delivery($city, $xid) {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
    $data = $PHPShopOrm->select(array('id'), array('city' => " REGEXP '" . $city . "'", 'id' => '=' . $xid,'is_folder'=>"!='1'"), false, array('limit' => 1));
    if (is_array($data))
        return $data['id'];
}

/**
 * Хук
 */
function delivery_hook($obj, $data) {
    
    $_RESULT=$data[0];
    $xid=$data[1];

    //print_r($_RESULT);
    $option = pickpoin_option();

    // ИД доставки pickpoin
    $title_id = search_pickpoin_delivery($option['city'], $xid);

    if (is_numeric($title_id))
        if ($xid == $title_id) {
            
         
            $button = '<a onclick="PickPoint.open(pickpoint_phpshop); return false" href="#">' . $option['name'] . '</a>';
            $hook['dellist'] = '<table collspan="0" rowspan="0"><tr><td>' . $_RESULT['dellist'] . '</td><td style="padding-left:10px">' . $button . '</td></tr></table>';
            $hook['delivery']=$_RESULT['delivery'];
            $hook['total']=$_RESULT['total'];
            
            return  $hook;
        }
}

$addHandler = array
    (
    'delivery' => 'delivery_hook'
);
?>
