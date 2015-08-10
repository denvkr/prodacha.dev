<?php
function ordercartforma_hook($val,$option,$rout) {
    if($rout == 'START') {
        
        $PHPShopProduct = new PHPShopProduct($val['id']);
        PHPShopParser::set('cart_image',$PHPShopProduct->getParam('pic_small'));
        PHPShopParser::set('cart_id',$val['id']);
        PHPShopParser::set('cart_xid',$option['xid']);
        PHPShopParser::set('cart_name',$val['name']);
        PHPShopParser::set('cart_num',$val['num']);
        PHPShopParser::set('cart_price',$val['price']);

        return ParseTemplateReturn('./phpshop/templates/'.$_SESSION['skin'].'/order/product.tpl',true);
    }
}

$addHandler=array
        (
        'ordercartforma'=>'ordercartforma_hook'

);
?>