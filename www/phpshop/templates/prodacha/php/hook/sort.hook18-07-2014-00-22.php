<?php
 
/**
 * Вывод характеристик в кратком описании  товара
 **/
function checkStore_add_sorttable_hook($obj, $row) {
    if (empty($obj->category)) {
        $obj->PHPShopCategory = new PHPShopCategory($row['category']);
    }
    $obj->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');	


	// Вторая цена
    $obj->set('productPrice2',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price2'],$row['baseinputvaluta']));
 
    // Если нужны остальные цены
    $obj->set('productPrice3',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price3'],$row['baseinputvaluta']));
    $obj->set('productPrice4',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price4'],$row['baseinputvaluta']));
    $obj->set('productPrice5',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price5'],$row['baseinputvaluta']));

    //добавление данных во вкладку гарантия и сервис
    $sub_sql2="";
    //если есть производитель
    if ( $obj->get('vendorName')!='' ) {
    	$sub_sql1="brand='".$obj->get('vendorName')."'";
    	$sub_sql2=" and brand='".$obj->get('vendorName')."'";
    	//echo $sub_sql2;
    }
    //Есть ли у него сервисные центры в принципе
    $sql_tab6="SELECT brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where ".$sub_sql1;
    $res_tab6=mysql_query($sql_tab6);
    //если нет производителя и нет сервисных центров тогда выводим специальный текст
    if (mysql_num_rows($res_tab6)==0 || $obj->get('vendorName')=='') {
    	$tab6_html=PHPShopText::div($GLOBALS['SysValue']['lang']['warranty_tab_string7'],$align = "left");
    	$obj->set('warrantyInfo',$tab6_html);
    	return true;
    }
    
    $tab6_html=$GLOBALS['SysValue']['lang']['warranty_tab_string1'].PHPShopText::nbsp(1);
    
    $tab6_html.='<select id="select_vendor_brand" name="select_vendor_brand" size="1">';
    
    $sub_sql=$obj->select_depend_region($_COOKIE['sincity']);
    //получаем бренд для данного города
    $sql_tab6="SELECT brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    
    //если для города нет сервисных центров то выводим все сервисные центры для данного бренда
    if (mysql_num_rows($res_tab6)==0) {
    	$sub_sql[0]=" where brand='".$obj->get('vendorName')."'";
    	$sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0];
    	$res_tab6=mysql_query($sql_tab6);
    }
    
    $cnt=0;
    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
    	$tab6_html.='<option value="b'.$cnt.'">'.$row_tab6['brand'].'</option>';
    	$brand=$row_tab6['brand'];
    }
    $tab6_html.='</select>';
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
	//выводим города где есть представительства СЦ
    $tab6_html.='<select id="select_vendor_city" name="select_vendor_city" size="1" onchange="check_varranty_firm_city();">';
    if ($_COOKIE['sincity']=='other'){
    	$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    } else {
    	$sql_tab6="SELECT city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    }
    
    $res_tab6=mysql_query($sql_tab6);
    $cnt=0;
    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
    	//если выбран другой регион то пытаемся выделить г.Москва
    	if ( $_COOKIE['sincity']=='other' && $row_tab6['city']=='Москва' ) {
			$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';   		
    	} else {
    		$tab6_html.='<option value="c'.$cnt.'">'.$row_tab6['city'].'</option>';
    	}

    }
    $tab6_html.='</select>'.'<br />';
    
    $sql_tab6="SELECT distinct brand_description FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    $row_tab6=mysql_fetch_assoc($res_tab6);
    //$tab6_html.=$row_tab6['brand_description'];
    
    $tab6_html.='<br />'.$GLOBALS['SysValue']['lang']['warranty_tab_string3'].PHPShopText::nbsp(1);
    $tab6_html.=$brand;
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string4'].PHPShopText::nbsp(1);
    $sql_tab6="SELECT varranty_time FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    $row_tab6=mysql_fetch_assoc($res_tab6);
    $tab6_html.=$row_tab6['varranty_time'];
    
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string5'].'<br />';
    $tab6_html.=$GLOBALS['SysValue']['lang']['warranty_tab_string6'].PHPShopText::nbsp(1).$brand.':<br />';
    
    $tab6_html.='<table id="select_varranty_firm" name="select_varranty_firm" col="3">';
    $sql_tab6="SELECT service_org_name,address,phone FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    $cnt=0;
    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
    	$tab6_html.='<tr>';
    	$tab6_html.='<td>'.$row_tab6['service_org_name'].'</td><td>'.$row_tab6['phone'].'</td><td>'.$row_tab6['address'].'</td>';
    	$tab6_html.='</tr>';
    }
    $tab6_html.='</table>';
    
    $obj->set('warrantyInfo',$tab6_html);
   
    return true;
}
 
 
$addHandler=array
        (
        'checkStore'=>'checkStore_add_sorttable_hook'
);


 
?>
