<?php

/**
 * Запись заказа в базу партнера
 */
function send_to_order_partner_hook($obj,$row,$rout) {
    
    if($rout == 'MIDDLE') {

        $PHPShopPartnerOrder = new PHPShopPartnerOrder();
        if(PHPShopSecurity::true_param($_SESSION['partner_id'],$_SESSION['cart'])) {

            // Модуль включен
            if($PHPShopPartnerOrder->option['enabled'] == 1) {
                $PHPShopPartnerOrder->writeLog();
                $PHPShopPartnerOrder->checkLog();
            }
        }

    }
}

$addHandler=array
        (
        'send_to_order'=>'send_to_order_partner_hook'
);
?>