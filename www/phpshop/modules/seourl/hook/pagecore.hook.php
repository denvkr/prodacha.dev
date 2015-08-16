<?php

/**
 * SEO навигация для страницы
 */
function index_seourl_hook($obj, $row, $rout) {
    if ($rout == 'END')
        navigation_seourl($obj, $row['name']);
}

/**
 * SEO навигация для списка страниц 
 */
function ListPage_seourl_hook($obj, $row, $rout) {

    $catalog_name = $obj->category_name;
    $seo_name = $GLOBALS['seourl_pref'] . PHPShopString::toLatin($catalog_name);

    // Проверка уникальности SEO ссылки
    if ($rout == 'START') {
        $url = $obj->PHPShopNav->getName(true);
        $url_true = 'CID_' . $obj->PHPShopNav->getId() . $seo_name;
        $url_pack = 'CID_' . $obj->PHPShopNav->getId();

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->setError404();
            return true;
        }
        elseif($url == $url_pack){
            header( 'Location: '.$url_true.'.html', true, 301 );
            return true;
        }
    }

    if ($rout == 'END')
        navigation_seourl($obj, $obj->category_name);
}

/**
 * SEO Навигация списка каталогов
 */
function ListCategory_seourl_hook($obj, $dataArray, $rout) {

    $catalog_name = $obj->category_name;
    $seo_name = $GLOBALS['seourl_pref'] . PHPShopString::toLatin($catalog_name);

    // Проверка уникальности SEO ссылки
    if ($rout == 'START') {
        $url = $obj->PHPShopNav->getName(true);
        $url_true = 'CID_' . $obj->PHPShopNav->getId() . $seo_name;
        $url_pack = 'CID_' . $obj->PHPShopNav->getId();

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->setError404();
            return true;
        }
        elseif($url == $url_pack){
            header( 'Location: '.$url_true.'.html', true, 301 );
            return true;
        }
    }

    if ($rout == 'END') {
        $dis = null;
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], '/' . $obj->PHPShopNav->getPath() . '/CID_' . $row['id'] . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']) . '.html');
            }
        $disp = PHPShopText::ul($dis);
        $obj->set('pageContent', $disp);

        // SEO хлебные крошки
        navigation_seourl($obj, $obj->category_name);
    }
}

/**
 * SEO Навигация хлебных крошек
 */
function navigation_seourl($obj, $name) {
    $dis = null;

    // Шаблоны разделителя навигации
    $spliter = ParseTemplateReturn($obj->getValue('templates.breadcrumbs_splitter'));
    $home = ParseTemplateReturn($obj->getValue('templates.breadcrumbs_home'));

    // Если нет шаблона разделителей
    if (empty($spliter))
        $spliter = ' / ';
    if (empty($home))
        $home = PHPShopText::a('/', __('Главная'));

    $arrayPath = $obj->navigation_array;

    if (is_array($arrayPath)) {
        foreach ($arrayPath as $v) {
            $dis.= $spliter . PHPShopText::a('/' . $obj->PHPShopNav->getPath() . '/CID_' . $v['id'] . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($v['name']) . '.html', $v['name']);
        }
    }

    $dis = $home . $dis . $spliter . PHPShopText::b($name);
    $obj->set('breadCrumbs', $dis);
}

$addHandler = array(
    'ListCategory' => 'ListCategory_seourl_hook',
    'ListPage' => 'ListPage_seourl_hook',
    'index' => 'index_seourl_hook'
);
?>
