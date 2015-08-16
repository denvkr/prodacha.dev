<?php
/**
* Форматирование цены
*/
function price_format_hook($obj,$row) {
 
     $price=number_format(PHPShopProductFunction::GetPriceValuta($row['id'], array($row['price'], $row['price2'], $row['price3'], $row['price4'], $row['price5']), $row['baseinputvaluta']),2,'.',' ');
     return $price;
}
 
 
 
$addHandler=array
        (
        'price'=>'price_format_hook',
);
 
?>