<?php
/**
 * Очистка памяти корзины при удалении товара из заказа
 */
function id_delete_visyalcart_hook($obj,$row){
        if(PHPShopSecurity::true_search($_COOKIE['visualcart_memory'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
        $PHPShopOrm->delete(array('memory'=>"='".$_COOKIE['visualcart_memory']."'"));
    }
}

$addHandler=array
        (
        'id_delete'=>'id_delete_visyalcart_hook'
);

?>