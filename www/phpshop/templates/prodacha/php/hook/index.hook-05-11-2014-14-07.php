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
		if ($rout == 'END') {
			if ($row['id']=='9') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_9.html" title="Генераторы">Генераторы</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='60') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_60.html" title="Снегоуборщики">Снегоуборщики</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='77') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_77.html" title="Цепные пилы">Цепные пилы</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='211') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_211.html" title="Садовые тракторы">Садовые тракторы</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='228') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_228.html" title="Техника STIHL">Техника STIHL</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='295') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_295.html" title="Техника VIKING">Техника VIKING</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}	
			if ($row['id']=='134') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_134.html" title="Техника KARCHER">Техника KARCHER</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}			
			if ($row['id']=='300') {
				$href_html='<ul>'.
						'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_300.html" title="Акции, скидки">Акции, скидки</a></span></li>'.
						'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);
			}
		}
		//echo $obj->isAction('leftCatal_hook');
		return true;
}

$addHandler=array
        (
        '#nowBuy'=>'nowBuy_hook',
        '#specMain'=>'specMain_hook',
        'leftCatalTable'=>'leftCatalTable_hook',
		'custommenuoutput'=>'leftCatal_hook',		
		'#subcatalog'=>'subcatalog_hook'
);

?>