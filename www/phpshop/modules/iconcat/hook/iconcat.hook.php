<?php

/**
 * »зменение списка подкаталогов в каталоге с <li> на <div> + описание
 */
function cid_category_iconcat_hook($obj,$dataArray,$rout) {
 
    $dis=null;
    if($rout=='END') {
        if(is_array($dataArray))
            foreach($dataArray as $row) {
            
                if(empty($row['icon'])) $row['icon']=$obj->no_photo;
 
                $obj->set('mod_iconcat_icon',$row['icon']);
                $obj->set('mod_iconcat_id',$row['id']);
                $obj->set('mod_iconcat_name',$row['name']);
                $obj->set('mod_iconcat_description',$row['icon_description']);
 
                $dis.=ParseTemplateReturn('./phpshop/modules/iconcat/templates/catalog_forma.tpl', true);
            }
 
        $dis.=PHPShopText::div(null,$align="left",$style='clear:both;');    
            
        // ѕереназначем переменную списка категорий
        $obj->set('catalogList',$dis);
    }
}
 


$addHandler=array
        (
        'CID_Category'=>'cid_category_iconcat_hook'
);

?>
