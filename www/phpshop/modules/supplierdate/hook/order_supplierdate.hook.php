<?php

/**
 * Добавление выбора лица в заказе
 */
function order_mod_supplierdate_hook($obj,$row,$rout) {
    
    if($rout =='END') {

        // Форма заказа
        $cart_min=$obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if($cart_min <= $obj->PHPShopCart->getSum(false)) {
            //$obj->set('yandexorder',$button);

            $obj->set('orderContent',parseTemplateReturn('phpshop/modules/supplierdate/templates/main_order_forma.tpl',true));
        }
        else {

            $obj->set('orderContent',$obj->message($obj->lang('cart_minimum').' '.$cart_min,$obj->lang('bad_order_mesage_2')));
        }

    }
}

$addHandler=array
        (
        'order'=>'order_mod_supplierdate_hook'
);
?>