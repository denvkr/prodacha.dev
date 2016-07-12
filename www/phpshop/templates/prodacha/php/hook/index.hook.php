<?php

/**
 * Изменение сетки товаров в "Сейчас покупают"
 * @param array $obj объект
 */
function nowBuy_hook($obj) {
    $obj->cell=3;
    $obj->limitpos=6;
	$obj->limitorders = 7;
}

/**
 * Изменение сетки товаров в "Спецпредложения на главной"
 * @param array $obj объект
 */
function specMain_hook($obj) {
    $obj->cell=3;
    $obj->limit=3;
}

/**
 * Изменение сетки категорий в "Таблице категорий на главной"
 * @param array $obj объект
 */
function leftCatalTable_hook($obj) {

    // Выключаем блок
    return true;
    
    $obj->cell=1;
}
/**
 * by Boris
 */
function specMainIcon_hook($obj) {
 
    $obj->cell=3; 
    $obj->limitspec=10;
 
}
 
function leftCatal_hook($obj,$row,$rout) {
    if ($rout == 'MIDDLE') {
        $ceo_custom_menu1=read_ceo_custom_menu($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-items_leftsubmenu_rename.txt');
        foreach ($ceo_custom_menu1 as $ceo_custom_menu1_item) {
            if (in_array($row['parent_to'],$ceo_custom_menu1_item)) {
                $catalogList_mod='';
                $catalogList_mod=str_replace($ceo_custom_menu1_item['str1'],$ceo_custom_menu1_item['str2'],$row['name']);
                $row['name']=$catalogList_mod;
                //if ($row['id']==210)
                //echo $ceo_custom_menu1_item['str1'].' '.$ceo_custom_menu1_item['str2'].' '.$catalogList_mod.'<br>';
            }
            //echo $ceo_custom_menu1_item['id'].$ceo_custom_menu1_item['str1'].$ceo_custom_menu1_item['str2'];
        }
        return $row['name'];            
    }
    if ($rout == 'END') {
            $href_html='';
            switch ($row['id']) {
                    case '9': 	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_9.html">Генераторы</a></li>'.
                                            '</ul>';
                                            break;
                    case '60': 	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_60.html">Снегоуборщики</a></li>'.
                                            '</ul>';
                                            break;
                    case '77':	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_77.html">Цепные пилы</a></li>'.
                                            '</ul>';
                                            break;
                    case '211':	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_211.html">Тракторы и райдеры</a></li>'.
                                            '</ul>';
                                            break;			
                    case '228':	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_228.html">Техника STIHL</a></li>'.
                                            '</ul>';
                                            break;				
                    case '295':	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_295.html">Техника VIKING</a></li>'.
                                            '</ul>';
                                            break;				
                    case '134':	$href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_134.html">Техника KARCHER</a></li>'.
                                            '</ul>';
                                            break;				
                    case '300': $href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_300.html">Акции, скидки</a></li>'.
                                            '</ul>';
                                            $obj->set('top_position','style="top: -85px !important;"');
                                            break;
                    case '472': $href_html='<ul>'.
                                            '<li class="inside_menu_head"><a href="/shop/CID_472.html">Техника по производителю</a></li>'.
                                            '</ul>';
                                            $obj->set('top_position','style="top: -85px !important; height:600px;"');
                                            break;
            }
            $obj->set('hrefcatalogPodcatalog',$href_html);
    return true;       
    }
}

function subcatalog_submehook($obj,$row,$rout) {
    if ($rout == 'MIDDLE') {
        $ceo_custom_menu1=read_ceo_custom_menu($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-items_leftsubmenu_rename.txt');
        foreach ($ceo_custom_menu1 as $ceo_custom_menu1_item) {
            if (in_array($row['parent_to'],$ceo_custom_menu1_item)) {
                $catalogList_mod=str_replace($ceo_custom_menu1_item['str1'],$ceo_custom_menu1_item['str2'],$row['name']);
                $row['name']=$catalogList_mod;
            }
            //echo $ceo_custom_menu1_item['id'].$ceo_custom_menu1_item['str1'].$ceo_custom_menu1_item['str2'];
        }
        return $row['name'];            
    }
    /*
	if ($rout == 'START') {
		if ($row['id']=='300') {
			$obj->set('top_position','style="top: -25px !important;"');
		}
	}	
	return true;
     */	
}

$addHandler=array
        (
        '#nowBuy'=>'nowBuy_hook',
        '#specMain'=>'specMain_hook',
        'leftCatalTable'=>'leftCatalTable_hook',
	'custommenuoutput'=>'leftCatal_hook',
        'subcatalog'=>'leftCatal_hook',
        'subcatalog2'=>'leftCatal_hook'
);

?>