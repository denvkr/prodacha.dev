<?php
 
/**
 * ¬ывод характеристик в кратком описании  товара
 **/
function checkStore_add_sorttable_hook($obj, $row) {
    if (empty($obj->category)) {
        $obj->PHPShopCategory = new PHPShopCategory($row['category']);
    }
    $obj->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');	


	// ¬тора€ цена
    $obj->set('productPrice2',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price2'],$row['baseinputvaluta']));
 
    // ≈сли нужны остальные цены
    $obj->set('productPrice3',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price3'],$row['baseinputvaluta']));
    $obj->set('productPrice4',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price4'],$row['baseinputvaluta']));
    $obj->set('productPrice5',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price5'],$row['baseinputvaluta']));

    //добавление данных во вкладку гаранти€ и сервис
    $sub_sql2="";
    //если есть производитель
    if ( $obj->get('vendorName')!='' ) {
    	$sub_sql1="brand='".$obj->get('vendorName')."'";
    	$sub_sql2=" and brand='".$obj->get('vendorName')."'";
    	//echo $sub_sql2;
    }
    //≈сть ли у него сервисные центры в принципе
    $sql_tab6="SELECT brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where ".$sub_sql1;
    $res_tab6=mysql_query($sql_tab6);
    //если нет производител€ и нет сервисных центров тогда выводим специальный текст
    if (mysql_num_rows($res_tab6)==0 || $obj->get('vendorName')=='') {
    	$tab6_html=PHPShopText::div($GLOBALS['SysValue']['lang']['warranty_tab_string7'],$align = "left");
    	$obj->set('warrantyInfo',$tab6_html);
    	return true;
    }
    
    $tab6_html=$GLOBALS['SysValue']['lang']['warranty_tab_string1'].PHPShopText::nbsp(1);
    
    $tab6_html.='<select id="select_vendor_brand" name="select_vendor_brand" size="1">';

    //формируем условие дл€ выборки в зависимости от города
    $sub_sql=$obj->select_depend_region($_COOKIE['sincity']);

    //получаем бренд дл€ данного города
    $sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    
    //если дл€ города нет сервисных центров то выводим все сервисные центры дл€ данного бренда
    if (mysql_num_rows($res_tab6)==0) {
    	//$sub_sql[0]=" where brand='".$obj->get('vendorName')."'";
    	$sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
    	$res_tab6=mysql_query($sql_tab6);
    }
    
    $cnt=0;
    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
    	$tab6_html.='<option value="b'.$cnt.'">'.$row_tab6['brand'].'</option>';
    	$brand=$row_tab6['brand'];
    	$cnt++;
    }
    $tab6_html.='</select>';

    //определ€ем есть ли среди других сервисов данного производител€ сервисы в москве
    $service_existing_region_desicion=$obj->check_service_existing_by_region($_COOKIE['sincity'],$brand);
    
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
	//признак того что нет сервиса в москве
    $no_service_in_moscow=false;
    //выводим данные дл€ города
    $tab6_html.='<select id="select_vendor_city" name="select_vendor_city" size="1" onchange="check_varranty_firm_city(\'sort.hook\');">';
    //if ($_COOKIE['sincity']=='other'){
	if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
		$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		$no_service_in_moscow=false;
	} elseif (($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
		$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		$no_service_in_moscow=false;
	} elseif ($_COOKIE['sincity']!='other' && $service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==0) {
		//$no_service_in_moscow=true;
		//$sql_tab6='';
		$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		$no_service_in_moscow=true;
	}
    //} else {
    //	$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    //}
    if ($no_service_in_moscow==true) {
    	//$tab6_html.='<option selected value="c0">¬ыберите город</option>';
    }

    if ((mysqli_errno)){
    	$res_tab6=mysql_query($sql_tab6);
    	$cnt=0;
	    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
	    	//если выбран другой регион то пытаемс€ выделить г.ћосква
			if ( ($row_tab6['city']=='ћосква' && $_COOKIE['sincity']=='m') || ($row_tab6['city']=='„ебоксары' && $_COOKIE['sincity']=='chb') || ($row_tab6['city']=='—анкт-ѕетербург' && $_COOKIE['sincity']=='sp') || ($_COOKIE['sincity']=='other' && $row_tab6['city']=='ћосква') ) {
				$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';   		
	    	} else if ($no_service_in_moscow==false && $service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && ($_COOKIE['sincity']=='chb' || $_COOKIE['sincity']=='sp') && $row_tab6['city']=='ћосква') {
	    		$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';
	    	}  	else {
	    		$tab6_html.='<option value="c'.$cnt.'">'.$row_tab6['city'].'</option>';
	    	}
	    	$cnt++;
	    }
    }
    $tab6_html.='</select>'.'<br />';
    
    if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
    	$sql_tab6="SELECT distinct brand_description FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
	} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
    	$sql_tab6="SELECT distinct brand_description FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
    } elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
    	$sql_tab6='';    	
    }

    if ((mysqli_errno)){
    	$res_tab6=mysql_query($sql_tab6);
    	$row_tab6=mysql_fetch_assoc($res_tab6);
    	//$tab6_html.=$row_tab6['brand_description'];
    }
    
    
    $tab6_html.='<br />'.$GLOBALS['SysValue']['lang']['warranty_tab_string3'].PHPShopText::nbsp(1);
    $tab6_html.=$brand;

    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string4'].PHPShopText::nbsp(1);

    if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
    	$sql_tab6="SELECT distinct varranty_time FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
	} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
    	$sql_tab6="SELECT distinct varranty_time FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
   	} elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
    	$sql_tab6='';    	
    }
    
    if ((mysqli_errno)){
	    $res_tab6=mysql_query($sql_tab6);
	    $row_tab6=mysql_fetch_assoc($res_tab6);
	    $tab6_html.='<b>'.$row_tab6['varranty_time'].'</b>';
    }
        
    $tab6_html.=PHPShopText::nbsp(1).'<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string5'].'</b><br />';
    $tab6_html.='<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string6'].PHPShopText::nbsp(1).$brand.':</b><br />';
    
    $tab6_html.='<table id="select_varranty_firm" name="select_varranty_firm" cellpadding="5" cellspacing="10" col="3">';
    $tab6_html.='<thead><tr><th style="text-align:left">Ќаименование</th><th style="text-align:left">“елефон</th><th style="text-align:left">јдрес</th></tr></thead><tbody>';
    
    if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
    	$sql_tab6="SELECT service_org_name,address,phone FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
	} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
    	$sql_tab6="SELECT service_org_name,address,phone FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
   	} elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
    	$sql_tab6='';    	
    }
    if ((mysqli_errno)){
    	$res_tab6=mysql_query($sql_tab6);
    	$cnt=0;
    	while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
    		$tab6_html.='<tr>';
    		$tab6_html.='<td>'.$row_tab6['service_org_name'].'</td><td>'.$row_tab6['phone'].'</td><td>'.$row_tab6['address'].'</td>';
    		$tab6_html.='</tr>';
    	}
    }
    
    $tab6_html.='</tbody></table>';
    //$tab6_html.='$no_service_in_moscow'.$no_service_in_moscow.'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_current_region\']'.$service_existing_region_desicion['service_existing_in_current_region'].'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_few_region\']'.$service_existing_region_desicion['service_existing_in_few_region'].'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_moscow\']'.$service_existing_region_desicion['service_existing_in_moscow'].'<br />';
    $obj->set('warrantyInfo',$tab6_html);
   
    return true;
}
 
 
$addHandler=array
        (
        'checkStore'=>'checkStore_add_sorttable_hook'
);


 
?>
