<?php



/**
 * ��������� ����� ������� � "������ ��������"
 * @param array $obj ������
 */
function nowBuy_hook($obj) {
    $obj->cell=3;
    $obj->limitpos=6;
	$obj->limitorders = 7;
}

/**
 * ��������� ����� ������� � "��������������� �� �������"
 * @param array $obj ������
 */
function specMain_hook($obj) {
    $obj->cell=3;
    $obj->limit=3;
}

/**
 * ��������� ����� ��������� � "������� ��������� �� �������"
 * @param array $obj ������
 */
function leftCatalTable_hook($obj) {

    // ��������� ����
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
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_9.html" title="����������">����������</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='60') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_60.html" title="�������������">�������������</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='77') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_77.html" title="������ ����">������ ����</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='211') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_211.html" title="������� ��������">������� ��������</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='228') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_228.html" title="������� STIHL">������� STIHL</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}
			if ($row['id']=='295') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_295.html" title="������� VIKING">������� VIKING</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}	
			if ($row['id']=='134') {
				$href_html='<ul>'.
				'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_134.html" title="������� KARCHER">������� KARCHER</a></span></li>'.
				'</ul>';
				$obj->set('hrefcatalogPodcatalog',$href_html);	
			}			
			if ($row['id']=='300') {
				$href_html='<ul>'.
						'<li class="" ><span class="inside_menu_head"><a href="/shop/CID_300.html" title="�����, ������">�����, ������</a></span></li>'.
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