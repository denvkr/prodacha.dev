<?php

function map_seourl_hook($obj, $array) {
    $val = $array['val'];
    $dir = $array['dir'];
    $seo_name = $GLOBALS['seourl_pref'] . PHPShopString::toLatin($array['name']);

    switch ($dir) {
        case 'category':
            $link = '/shop/CID_' . $val . $seo_name . '.html';
            break;

        case 'category_page':
            $link = '/page/CID_' . $val . $seo_name . '.html';
            break;
    }

    return $link;
}

$addHandler = array
    (
    'seourl' => 'map_seourl_hook'
);
?>