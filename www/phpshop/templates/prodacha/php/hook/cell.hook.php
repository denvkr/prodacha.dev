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

function add_same_tovar_box_hook($obj,$row,$rout){
    global $SysValue;
    if ($rout == 'END'){
        include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/same_box_config.php');
        //$cid_array=array(5=>array(48,49,50,51,52),186=>array(359,360,361),639=>array(40,41));
        //$cnt_analog_prod=6;
        if ($price_deviation){
            $percent=$row['price']*$price_deviation_percent;
            //echo '%='.$price_deviation_percent.'<br>';
            $price_height_border=round($row['price']+$percent);
            $price_low_border=round($row['price']-$percent);//$row['price']-$percent;
            $price_sql_where_part=array('price'=>' between '.$price_low_border.' and '.$price_height_border);
            //echo 'lowest price border='.$price_low_border.'<br>';
            //echo 'heighest price border='.$price_height_border.'<br>';
        } else {
            $price_sql_where_part=array();
        }
            
        $obj->set('end_card',$cnt_analog_prod);

        $parent_cat=$obj->PHPShopCategory->getValue('parent_to');
        $category=$row['category'];

        if ($logic21_22==true) {
            $cat_list=$obj->select(array('id'), array('parent_to'=>'='.$parent_cat), false, array('limit' => $cnt_analog_prod), __FUNCTION__, array('base' => $obj->getValue('base.categories'), 'cache' => 'true'));
            //$cat_list= mysql_query('select id from '.$SysValue['base']['categories']. ' where parent_to='.$parent_cat.' and id<>'.$category);
            foreach ($cat_list as $cat_list_item=>$cat_list_val){
                $cat_list2.=$cat_list_val['id'].',';
            }
            $cat_list2=substr($cat_list2,0,-1);
            //echo 'current cat='.$category.',parrent cat='.$parent_cat.'<br>';
            //echo '2.1 product list='.$cat_list2.'<br>';
            //получаем данные по продуктам

            $result1=$obj->select_native(array('id','uid','pic_small','name','price'),array_merge(array('id'=>'<>'.$row['id'],'sklad'=>'="0"','outdated'=>'="0"','category'=>' in ('.$cat_list2.')'),$price_sql_where_part) , array('order'=>'RAND()'), array('limit' => $cnt_analog_prod), __FUNCTION__, array('base' => $obj->getValue('base.products'), 'cache' => 'false'));
            //провер€ем если данных дл€ вывода недостаточно то используем дополнительные элементы
            //echo count($result1);
            $num_rows=$cnt_analog_prod-count($result1);
            //echo $num_rows.'<br>';
            //комплексный массив дл€ подстановки
            foreach ($cid_array as $cid_array_key=>$cid_array_value) {
                //echo $cid_array_key.',<br>';
                if ($cid_array_key==$parent_cat){
                    $add_cid=implode(',',$cid_array_value);
                }
            }
            if (!isset($add_cid)){
                $add_cid=$cid_array_def;
            }
            //var_dump($result1);

            //echo '2.2 additional CIDs='.$add_cid.'<br>';
            if ($num_rows>0){
                //мы должны вывести гарантированные элементы
                $result2=$obj->select_native(array('id','uid','pic_small','name','price'), array_merge(array('id'=>'<>'.$row['id'],'sklad'=>'="0"','outdated'=>'="0"','category'=>' in ('.$add_cid.')'),$price_sql_where_part), array('order'=>'RAND()'), array('limit' => $num_rows), __FUNCTION__, array('base' => $obj->getValue('base.products'), 'cache' => 'false'));
                //echo count($result2);
                if (!empty($result1)){
                    foreach ($result2 as $result2_item=>$result2_val){
                    $result1[]=($result2_val);
                    }
                } else {
                    $result1=$result2;
                }
            }
            //получаем данные по родительскому каталогу
            //$same_tovar_box=ParseTemplateReturn($this->getValue('templates.product_same_box'));
            $obj->set('same_tovar_box','<table cellspacing="0" cellpadding="0" border="0"><tbody><tr>',true);
            //foreach($result as $resultitem){
                //echo $result[0]['pic_small'].'<br>';
            //}
            //var_dump($result);
            for ($cnt=1;$cnt<=$cnt_analog_prod;$cnt++) {
                $obj->set('same_tovar_num',$cnt);
                $obj->set('productImgWidth',$SysValue['other']['productImgWidth']);
                $obj->set('productImg',$result1[$cnt-1]['pic_small']);
                $obj->set('productUid',$result1[$cnt-1]['id']);
                $obj->set('productPrice',$result1[$cnt-1]['price']);
                $obj->set('productName',$result1[$cnt-1]['name']);
                $obj->set('productValutaName',$SysValue['other']['productValutaName']);            
                $obj->set('same_tovar_box','<td>'.ParseTemplateReturn($obj->getValue('templates.product_same_box')).'</td>',true);
            }
            $obj->set('same_tovar_box','</tr></tbody></table>',true);
            
        } else {
            //комплексный массив дл€ подстановки
            foreach ($cid_array as $cid_array_key=>$cid_array_value) {
                //echo $cid_array_key.',<br>';
                if ($cid_array_key==$parent_cat){
                    $add_cid=implode(',',$cid_array_value);
                }
            }
            if (!isset($add_cid)){
                $add_cid=$cid_array_def;
            }
            //var_dump($result1);

            //echo '2.2 additional CIDs='.$add_cid.'<br>';
            //мы должны вывести гарантированные элементы
            $result2=$obj->select_native(array('id','uid','pic_small','name','price'), array_merge(array('id'=>'<>'.$row['id'],'sklad'=>'="0"','outdated'=>'="0"','category'=>' in ('.$add_cid.')'),$price_sql_where_part), array('order'=>'RAND()'), array('limit' => $num_rows), __FUNCTION__, array('base' => $obj->getValue('base.products'), 'cache' => 'false'));
            //echo count($result2);
            //провер€ем если данных дл€ вывода недостаточно то используем дополнительные элементы
            //echo count($result1);
            $num_rows=$cnt_analog_prod-count($result2);
            //echo '$num_rows='.$num_rows.'<br>';

            if ($num_rows>0){
                $cat_list=$obj->select(array('id'), array('parent_to'=>'='.$parent_cat), false, array('limit' => $cnt_analog_prod), __FUNCTION__, array('base' => $obj->getValue('base.categories'), 'cache' => 'true'));
                //$cat_list= mysql_query('select id from '.$SysValue['base']['categories']. ' where parent_to='.$parent_cat.' and id<>'.$category);
                foreach ($cat_list as $cat_list_item=>$cat_list_val){
                    $cat_list2.=$cat_list_val['id'].',';
                }
                $cat_list2=substr($cat_list2,0,-1);
                //echo 'current cat='.$category.',parrent cat='.$parent_cat.'<br>';
                //echo '2.1 product list='.$cat_list2.'<br>';
                //получаем данные по продуктам

                $result1=$obj->select_native(array('id','uid','pic_small','name','price'),array_merge(array('id'=>'<>'.$row['id'],'sklad'=>'="0"','outdated'=>'="0"','category'=>' in ('.$cat_list2.')'),$price_sql_where_part) , array('order'=>'RAND()'), array('limit' => $cnt_analog_prod), __FUNCTION__, array('base' => $obj->getValue('base.products'), 'cache' => 'false'));

                if (!empty($result2)){
                    foreach ($result1 as $result1_item=>$result1_val){
                    $result2[]=($result1_val);
                    }
                } else {
                    $result2=$result1;
                }
            }
            //получаем данные по родительскому каталогу
            //$same_tovar_box=ParseTemplateReturn($this->getValue('templates.product_same_box'));
            $obj->set('same_tovar_box','<table cellspacing="0" cellpadding="0" border="0"><tbody><tr>',true);
            //foreach($result as $resultitem){
                //echo $result[0]['pic_small'].'<br>';
            //}
            //var_dump($result);
            for ($cnt=1;$cnt<=$cnt_analog_prod;$cnt++) {
                $obj->set('same_tovar_num',$cnt);
                $obj->set('productImgWidth',$SysValue['other']['productImgWidth']);
                $obj->set('productImg',$result2[$cnt-1]['pic_small']);
                $obj->set('productUid',$result2[$cnt-1]['id']);
                $obj->set('productPrice',$result2[$cnt-1]['price']);
                $obj->set('productName',$result2[$cnt-1]['name']);
                $obj->set('productValutaName',$SysValue['other']['productValutaName']);            
                $obj->set('same_tovar_box','<td>'.ParseTemplateReturn($obj->getValue('templates.product_same_box')).'</td>',true);
            }
            $obj->set('same_tovar_box','</tr></tbody></table>',true);
            
        }
        
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
        'CID_Category'=>'cid_category_add_spec_hook',
        'UID'=>'add_same_tovar_box_hook'
);

?>