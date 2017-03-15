<?php
function service_page_mod($obj,$row,$rout) {
	
	if ($GLOBALS['SysValue']['nav']['nav']=='service.html') {
		
		//добавление данных во вкладку гарантия и сервис
		$sub_sql2="";
		
		//если есть производитель
		if ( $obj->get('vendorName')!='' ) {
			$sub_sql1="brand='".$obj->get('vendorName')."'";
			$sub_sql2=" and brand='".$obj->get('vendorName')."'";
			//echo $sub_sql2;
		} else {
			$sub_sql1='1=1';
			$sub_sql2='';
		}
		
		//Есть ли у него сервисные центры в принципе
		$sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where ".$sub_sql1;
		$res_tab6=mysql_query($sql_tab6);
		//если нет производителя и нет сервисных центров тогда выводим специальный текст
		if (mysql_num_rows($res_tab6)==0) {
			$tab6_html=PHPShopText::div($GLOBALS['SysValue']['lang']['warranty_tab_string7'],$align = "left");
			$obj->set('warrantyInfo',$tab6_html);
			return true;
		}
		
		$tab6_html=$GLOBALS['SysValue']['lang']['warranty_tab_string1'].PHPShopText::nbsp(1);
		
		$tab6_html.='<select id="select_vendor_brand" name="select_vendor_brand" size="1" onchange="check_varranty_firm_city(\'service.hook\');">';
		
		//формируем условие для выборки в зависимости от города
		$sub_sql=$obj->select_depend_region($_COOKIE['sincity']);
		
		//получаем бренд для данного города
		$sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		$res_tab6=mysql_query($sql_tab6);
		
		//если для города нет сервисных центров то выводим все сервисные центры для данного бренда
		if (mysql_num_rows($res_tab6)==0) {
			//$sub_sql[0]=" where brand='".$obj->get('vendorName')."'";
			$sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty'];
			$res_tab6=mysql_query($sql_tab6);
		}
		
		$cnt=0;
		while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
			$tab6_html.='<option value="b'.$cnt.'">'.$row_tab6['brand'].'</option>';
			$brand=$row_tab6['brand'];
		}
		$tab6_html.='</select>';

		//определяем есть ли среди других сервисов данного производителя сервисы в москве
		//$service_existing_region_desicion=$obj->check_service_existing_by_region($_COOKIE['sincity'],$brand);
		
		$tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
		//признак того что нет сервиса в москве
		//$no_service_in_moscow=false;
		//выводим данные для города
		$tab6_html.='<select id="select_vendor_city" name="select_vendor_city" size="1" onchange="check_varranty_firm_city(\'service.hook\');">';
		//if ($_COOKIE['sincity']=='other'){
		//if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
		$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		//} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
		//	$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		//} elseif ($_COOKIE['sincity']!='other' && $service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==0) {
		//	$no_service_in_moscow=true;
		//	$sql_tab6='';
		//}
		//} else {
		//	$sql_tab6="SELECT distinct city FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		//}
		//if ($no_service_in_moscow==true) {
		//	$tab6_html.='<option selected value="c0">Выберите город</option>';
		//}
		
		if ((mysqli_errno)){
			$res_tab6=mysql_query($sql_tab6);
			$cnt=0;
			while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
				//если выбран другой регион то пытаемся выделить г.Москва
				if ( ($row_tab6['city']=='Москва' && $_COOKIE['sincity']=='m') || ($row_tab6['city']=='Чебоксары' && $_COOKIE['sincity']=='chb') || ($row_tab6['city']=='Санкт-Петербург' && $_COOKIE['sincity']=='sp') || ($_COOKIE['sincity']=='other' && $row_tab6['city']=='Москва')) {
					$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';
				} else {
					$tab6_html.='<option value="c'.$cnt.'">'.$row_tab6['city'].'</option>';
				}
				
		
			}
		}
		$tab6_html.='</select>'.'<br />';
		
		//if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
		$sql_tab6="SELECT distinct brand_description FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		//} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
		//	$sql_tab6="SELECT distinct brand_description FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		//} elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
		//	$sql_tab6='';
		//}
		
		if ((mysqli_errno)){
			$res_tab6=mysql_query($sql_tab6);
			$row_tab6=mysql_fetch_assoc($res_tab6);
			//$tab6_html.=$row_tab6['brand_description'];
		}
		
		
		//$tab6_html.='<br />'.$GLOBALS['SysValue']['lang']['warranty_tab_string3'].PHPShopText::nbsp(1);
		//$tab6_html.=$brand;
		
		//$tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string4'].PHPShopText::nbsp(1);
		
		//if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
		$sql_tab6="SELECT distinct varranty_time FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		//} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
		//	$sql_tab6="SELECT distinct varranty_time FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		//} elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
		//	$sql_tab6='';
		//}
		
		if ((mysqli_errno)){
			$res_tab6=mysql_query($sql_tab6);
			$row_tab6=mysql_fetch_assoc($res_tab6);
			//$tab6_html.=$row_tab6['varranty_time'];
		}
		
		//$tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string5'].'<br />';
		$tab6_html.='<div id="servicess_description">'.$GLOBALS['SysValue']['lang']['warranty_tab_string6'].PHPShopText::nbsp(1).':<br /></div>';
		
		$tab6_html.='<table id="select_varranty_firm" name="select_varranty_firm" col="3">';
		
		//if ($service_existing_region_desicion['service_existing_in_current_region']==1) {
		$sql_tab6="SELECT service_org_name,address,phone FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
		//} elseif ($_COOKIE['sincity']=='other' || ($service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && $service_existing_region_desicion['service_existing_in_moscow']==1)) {
		//	$sql_tab6="SELECT service_org_name,address,phone FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where brand='".$obj->get('vendorName')."'";
		//} elseif ($_COOKIE['sincity']!='other' && $no_service_in_moscow==true) {
		//	$sql_tab6='';
		//}
		
		if ((mysqli_errno)){
			$res_tab6=mysql_query($sql_tab6);
			$cnt=0;
			while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
				$tab6_html.='<tr>';
				$tab6_html.='<td>'.$row_tab6['service_org_name'].'</td><td>'.$row_tab6['phone'].'</td><td>'.$row_tab6['address'].'</td>';
				$tab6_html.='</tr>';
			}
		}
		
		$tab6_html.='</table>';
		
		if ($rout == 'END') {
			//$tab6_html='test';
			$obj->set('pageContent', $tab6_html,true);		
		}
	
	}
	//return true;
}
$addHandler=array
(
		'index'=>'service_page_mod'
);
?>