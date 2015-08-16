<?php

/**
 * Очистка памяти корзины после оформления
 */
function send_to_order_visyalcart_hook($obj,$row,$rout) {

    if($rout == 'END' and PHPShopSecurity::true_search($_COOKIE['visualcart_memory'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
        $PHPShopOrm->delete(array('memory'=>"='".$_COOKIE['visualcart_memory']."'"));
    }

}

$addHandler=array
        (
        'send_to_order'=>'send_to_order_visyalcart_hook'
);

?>