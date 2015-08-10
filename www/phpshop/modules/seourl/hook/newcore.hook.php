<?php

/**
 * Добавление SEO ссылки к новостям в списке
 */
function index_seourl_hook($obj,$row,$rout) {
    if($rout == 'MIDDLE')
    $obj->set('nameLat',$GLOBALS['seourl_pref'].PHPShopString::toLatin($row['zag']));
}

/**
 * Проверка уникальности SEO новости
 */
function ID_seourl_hook($obj, $row, $rout) {
    if ($rout == 'END') {
        $url = $obj->PHPShopNav->getName(true);

        $url_true = '/news/ID_' . $obj->PHPShopNav->getId() . $GLOBALS['seourl_pref'] . PHPShopString::toLatin($row['zag']);
        $url_pack = '/news/ID_' . $obj->PHPShopNav->getId();

        
        // Если ссылка не сходится
        if ($url != $url_true and $url != $url_pack) {
            $obj->ListInfoItems = parseTemplateReturn($obj->getValue('templates.error_page_forma'));
            $obj->set('newsZag', __('Ошибка 404'));
            $obj->setError404();
        }
        elseif($url == $url_pack){
            header( 'Location: '.$url_true.'.html', true, 301 );
            return true;
        }
    }
}


$addHandler = array(
    'ID' => 'ID_seourl_hook',
    'index'=>'index_seourl_hook'
);
?>
