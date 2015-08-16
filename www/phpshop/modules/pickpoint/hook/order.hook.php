<?php

/**
 * Добавление кнопки быстрого заказа
 */
function order_pickpoint_hook($obj,$row,$rout) {

    if($rout =='END') {

        // Форма личной информации по заказу
        $cart_min=$obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if($cart_min <= $obj->PHPShopCart->getSum(false)) {
            $obj->set('orderContent',parseTemplateReturn('phpshop/modules/pickpoint/templates/main_order_forma.tpl',true));
        }
        else {
            $obj->set('orderContent',$obj->message($obj->lang('cart_minimum').' '.$cart_min,$obj->lang('bad_order_mesage_2')));
        }

    }
}

$addHandler=array
        (
        'order'=>'order_pickpoint_hook'
);
?>