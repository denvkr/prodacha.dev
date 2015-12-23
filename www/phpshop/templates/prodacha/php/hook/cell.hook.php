<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/config_functions.php');

/**
 * »зменение формата решетки между товарами c <td> на <li>
 * @param array $obj объект
 * @param array $arg массив данных
 * @return string
 */
function setcell_hook($obj,$arg) {

    $li=null;
    $panel=array('panel_l','panel_r','panel_l','panel_r');

    foreach($arg as $key=>$val) {
        if(!empty($val)) {
            $li.='<li class="'.$panel[$key].'">'.$val.'</li>';
        }
    }

    return $li;
}

/**
 * »зменение формата решетки между товарами c <td> на <li>, компил€ци€ списка в <ul>
 * @return string
 */
function compile_hook($obj) {
    $ul='<ul>'.$obj->product_grid.'</ul>';
    $obj->product_grid=null;
    return $ul;
}

/**
 * »зменение сетки сопутствующих товаров, сетка товаров = 3
 */
function odnotip_hook($obj,$row,$rout) {
    if($rout=='START') {
        $obj->odnotip_setka_num=3;
        //$obj->template_odnotip='main_product_forma_3';
        $obj->line=true;
    }
}

/**
 * Изменение списка подкаталогов в каталоге с <li> на <div> + описание
 */
function cid_category_hook($obj,$dataArray,$rout) {

    $dis=null;
    if($rout=='END') {
        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $content=PHPShopText::a($obj->path.'/CID_'.$row['id'].'.html',$row['name']);
                $content.=PHPShopText::p($row['content']);
                $dis.=PHPShopText::div($content,$align="left",$style='float:left;padding:10px');
			}

        // переназначаем переменную списка категорий
        $obj->set('catalogList',$dis);

        // Cпецпредложения товаров
        cid_category_add_spec_hook($obj,$dataArray);
    }
}

/**
 * ƒобавление в список каталогов спецпредложени€ товаров в 3 ячейки, лимит 3
 * ¬ывод кастомизированных меню в случае переспама.
 */
function cid_category_add_spec_hook($obj,$row,$rout) {
    global $PHPShopProductIconElements;
    
      if($rout == 'END') {
        $ceo_custom_menu1=read_ceo_custom_menu($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-items_catalog-rename.txt');

            foreach ($ceo_custom_menu1 as $ceo_custom_menu1_item) {
                if (in_array($obj->PHPShopNav->getId(),$ceo_custom_menu1_item)) {
                    $catalogList_mod=str_replace($ceo_custom_menu1_item['str1'],$ceo_custom_menu1_item['str2'],$obj->get('catalogList'));
                    $obj->set('catalogList',$catalogList_mod);
                    $catalogList1_mod=str_replace($ceo_custom_menu1_item['str1'],$ceo_custom_menu1_item['str2'],$obj->get('catalogList1'));
                    $obj->set('catalogList1',$catalogList1_mod);
                    //$obj->set('display_custom_catalogList_hook_test',$ceo_custom_menu1[0]['id'].$ceo_custom_menu1[0]['name'].$ceo_custom_menu1[0]['value'],true);      
                }
                //echo $ceo_custom_menu1_item['id'].$ceo_custom_menu1_item['str1'].$ceo_custom_menu1_item['str2'];
            }
            //while (search_pos($obj->get('catalogList1'),'(',')')!==false){
            $retval=search_pos($obj->get('catalogList1'),'(',')');
            //echo $retval;
            //$catalogList1_mod=str_ireplace(search_pos($obj->get('catalogList1'),'(',')'),'',$obj->get('catalogList1'));
            $obj->set('catalogList1',$retval);                
            //}
        //}
        //echo 'test';
        //return true;
      } else {
        // случайный выбор каталога
        if(is_array($row))
            foreach($row as $val)
                $cat[]=$val['id'];
        $rand=rand(0,count($cat)-1);

        // используем элемент вывода спецпредложений
        $PHPShopProductIconElements->template='main_product_forma_3';
        $spec=$PHPShopProductIconElements->specMainIcon(false,$cat[$rand],3,3,true);
        $spec=PHPShopText::div(PHPShopText::p($spec),$align="left",$style='float:none;padding:10px');

        // добавляем в переменную списка категорий вывод спецпредложений
        $obj->set('catalogList',$spec,true);
      }
}

function search_pos($my_val_str,$border_start,$border_end){
    //while (search_pos($my_val_str,$border_start,$border_end)!==false){
    $s1=strpos($my_val_str,$border_start);
    if ($s1===false) {
        //echo $my_val_str;
        return $my_val_str;
    } else {
        $s2=strpos($my_val_str,$border_end);
        $len=$s2-$s1;
        $retval=substr($my_val_str,$s1,($len+1));         
        $catalogList1_mod=str_ireplace($retval,'',$my_val_str);
        return search_pos($catalogList1_mod,$border_start,$border_end);
    }
}
$addHandler=array
        (
        'odnotip'=>'odnotip_hook',
        '#setCell'=>'setcell_hook',
        '#compile'=>'compile_hook',
        'CID_Category'=>'cid_category_add_spec_hook'
);

?>