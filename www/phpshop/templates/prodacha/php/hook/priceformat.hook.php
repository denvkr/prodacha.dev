<?php
/**
* �������������� ����
*/
function price_format_hook($obj, $row, $newprice) {
 
    // ���� ���� ����� ����
    if (empty($newprice))
        $price = $row['price'];
    else
        $price = $row['price_n'];
 
    $price = number_format(PHPShopProductFunction::GetPriceValuta($row['id'], array($price, $row['price2'], $row['price3'], $row['price4'], $row['price5']), $row['baseinputvaluta']), 0, '.', ' ');
    return $price;
}
 
 
 
$addHandler=array
        (
        'price'=>'price_format_hook',
);
 
?>