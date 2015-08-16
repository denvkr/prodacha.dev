<?php


/**
 * Добавление SEO ссылки к товарам
 */
function product_grid_seourl_element_hook($obj,$row) {
    $obj->set('nameLat',$GLOBALS['seourl_pref'].PHPShopString::toLatin($row['name']));
}

$addHandler=array
        (
        'product_grid'=>'product_grid_seourl_element_hook'
);


?>