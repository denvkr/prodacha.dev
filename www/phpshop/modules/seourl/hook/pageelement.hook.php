<?php


/**
 * SEO ссылки для элемента навигации подкаталога страниц
 */
function subcatalog_page_seourl_hook($obj, $row, $rout) {
    if($rout != 'START')
        $obj->set('nameLat', $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']));

}

$addHandler = array
    (
    'subcatalog'=>'subcatalog_page_seourl_hook',
    'pageCatal'=>'subcatalog_page_seourl_hook'
);
?>