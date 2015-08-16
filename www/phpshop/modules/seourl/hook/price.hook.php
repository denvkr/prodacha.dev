<?php
/**
 * Добавление SEO ссылки к товарам
 */
function price_seo_hook($obj,$row){
    $pref='_';
    return '/shop/UID_'.$row['id'].$pref.PHPShopString::toLatin($row['name']).'.html';
}

$addHandler=array
        (
        'seourl'=>'price_seo_hook'
);
?>