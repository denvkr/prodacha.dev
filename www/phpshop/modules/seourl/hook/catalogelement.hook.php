<?php

/**
 * SEO ссылки для таблицы категорий
 */
function template_cat_table_seourl_hook($obj, $val) {
    return PHPShopText::a('/shop/CID_' . $val['id'] . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($val['name']) . '.html', $val['name'], $val['name']) . ' | ';
}

/**
 * SEO ссылки для элемента навигации каталога
 */
function leftCatal_seourl_hook($obj, $row, $rout) {
    if ($rout == 'END') {
        $obj->set('nameLat', $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']));
    }
}

/**
 * SEO ссылки для элемента навигации подкаталога
 */
function subcatalog_seourl_hook($obj, $row) {
        $obj->set('nameLat', $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']));

}

$addHandler = array
    (
    'template_cat_table' => 'template_cat_table_seourl_hook',
    'leftCatalTable' => 'leftCatal_seourl_hook',
    'leftCatal' => 'leftCatal_seourl_hook',
    'subcatalog'=>'subcatalog_seourl_hook'
);
?>