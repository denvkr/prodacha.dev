<?php

function CID_Product_seourl_hook($obj, $row, $rout) {

    $catalog_name = $obj->PHPShopCategory->getName();
    $seo_name = $GLOBALS['seourl_pref'] . PHPShopString::toLatin($catalog_name);

    // Проверка уникальности SEO ссылки
    if ($rout == 'START') {
        $url = $obj->PHPShopNav->getName(true);
        $url_true = '/shop/CID_' . $obj->PHPShopNav->getId() . $seo_name;
        $url_pack = '/shop/CID_' . $obj->PHPShopNav->getId();
        $url_nav = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . $obj->PHPShopNav->getPage();
        $url_true_nav = '/shop/CID_' . $obj->PHPShopNav->getId() . '_' . $obj->PHPShopNav->getPage() . $seo_name;
        

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack and $url != $url_nav and $url != $url_true_nav) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->setError404();
            return true;
        }
        elseif($url == $url_pack){
            header( 'Location: '.$obj->getValue('dir.dir').$url_true_nav.'.html', true, 301 );
            return true;
        }
    }

    if ($rout == 'END') {
        $obj->set('nameLat', $seo_name);

        // SEO сортировка по характеристикам
        $vendorSelectDisp = '
<script>
function ReturnSortSeoUrl(v){
    var s,url="";
    if(v>0){
        s=document.getElementById(v).value;
        if(s!="") url="v["+v+"]="+s+"&";
    }
    return url;
}

function GetSortAll(){
    var url=ROOT_PATH+"/shop/CID_"+arguments[0]+"' . $seo_name . '.html?";
    var i=1;
    var c=arguments.length;
    for(i=1; i<c; i++)
        if(document.getElementById(arguments[i])) url=url+ReturnSortSeoUrl(arguments[i]);
    location.replace(url);
}
</script>
' . $obj->get('vendorSelectDisp');

        $vendorSelectDisp = str_replace('GetSortAll', 'GetSortAllSeo', $vendorSelectDisp);
        $obj->set('vendorSelectDisp', $vendorSelectDisp);

        // SEO хлебные крошки
        navigation_seourl($obj, $obj->PHPShopCategory->getName());
    }
}

/**
 * SEO Навигация списка каталогов
 */
function CID_Category_seourl_hook($obj, $dataArray, $rout) {

    $catalog_name = $obj->PHPShopCategory->getName();
    $seo_name = $GLOBALS['seourl_pref'] . PHPShopString::toLatin($catalog_name);

    // Проверка уникальности SEO ссылки
    if ($rout == 'START') {
        $url = $obj->PHPShopNav->getName(true);
        $url_true = '/shop/CID_' . $obj->PHPShopNav->getId() . $seo_name;
        $url_pack = '/shop/CID_' . $obj->PHPShopNav->getId();

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->setError404();
            return true;
        }
        elseif($url == $url_pack){
            header( 'Location: '.$obj->getValue('dir.dir').$url_true.'.html', true, 301 );
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
        $obj->set('catalogList', $disp);

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

    if (is_array($obj->navigation_array))
        $arrayPath = array_reverse($obj->navigation_array);

    if (is_array($arrayPath)) {
        foreach ($arrayPath as $v) {
            $dis.= $spliter . PHPShopText::a('/' . $obj->PHPShopNav->getPath() . '/CID_' . $v['id'] . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($v['name']) . '.html', $v['name']);
        }
    }

    $dis = $home . $dis . $spliter . PHPShopText::b($name);
    $obj->set('breadCrumbs', $dis);
}

/**
 * SEO ссылки для дополнительной навигации
 */
function other_cat_navigation_seourl_hook($obj, $parent, $rout) {
    if ($rout == 'END') {
        $dataArray = array();
        $dis = null;

        // Использование глобального кэша
        foreach ($GLOBALS['Cache'][$GLOBALS['SysValue']['base']['categories']] as $val) {
            if ($val['parent_to'] == $parent)
                $dataArray[] = $val;
        }

        if (count($dataArray) > 1) {
            foreach ($dataArray as $row) {

                if ($row['id'] == $obj->category)
                    $class = 'activ_catalog';
                else
                    $class = null;

                $dis.=PHPShopText::a('/' . $GLOBALS['SysValue']['nav']['path'] . '/CID_' . $row['id'] . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']) . '.html', $row['name'], false, false, false, false, $class);
                $dis.=' | ';
            }
        }

        $obj->set('DispCatNav', $dis);
        return true;
    }
}

/**
 * Проверка уникальности SEO имени товара
 */
function UID_seourl_hook($obj, $row, $rout) {
    if ($rout == 'END') {
        $url = $obj->PHPShopNav->getName(true);
        $url_true = '/shop/UID_' . $obj->PHPShopNav->getId() . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['name']);
        $url_pack = '/shop/UID_' . $obj->PHPShopNav->getId();

        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->set('breadCrumbs', null);
            $obj->set('odnotipDisp', null);
            $obj->setError404();
        }
        elseif($url == $url_pack){
            header( 'Location: '.$obj->getValue('dir.dir').$url_true.'.html', true, 301 );
            return true;
        }

        // SEO хлебные крошки
        navigation_seourl($obj, $row['name']);
    }
}

$addHandler = array(
    'UID' => 'UID_seourl_hook',
    'other_cat_navigation' => 'other_cat_navigation_seourl_hook',
    'CID_Category' => 'CID_Category_seourl_hook',
    'CID_Product' => 'CID_Product_seourl_hook'
);
?>
