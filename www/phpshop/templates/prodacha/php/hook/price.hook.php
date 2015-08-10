<?php

/**
 * Добавление поля кол-во товара в корзине для прайс-лсита
 * @param array $obj объект
 * @param array $data массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function product_pricecore_hook($obj,$data,$rout) {
    if($rout == 'END') {
        $dis=null;
        // Добавляем в дизайн ячейки с товарами
        if(is_array($data))
            foreach($data as $row) {
                $name=PHPShopText::a($obj->seourl($row),$row['name']);
                $idn='n'.$row['id'];
                $cart=PHPShopText::a('javascript:AddToCartNum('.$row['id'].',\''.$idn.'\')',PHPShopText::img('images/shop/basket_put.gif',false,'absMiddle'),$obj->lang('product_sale')); //'absMiddle'

                $num=PHPShopText::setInputText(null,"$idn",1,30);
                $dis.=$obj->tr('#ffffff',$name,$obj->price($row).' '.$obj->currency(),$num,$cart);
            }
    return $dis;
    }
}


$addHandler=array
        (
        'product'=>'product_pricecore_hook',
);
?>