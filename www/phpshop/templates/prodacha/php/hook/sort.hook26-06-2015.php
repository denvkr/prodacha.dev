<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * Вывод характеристик в кратком описании  товара
 **/
function checkStore_add_sorttable_hook($obj, $row) {

    //if (empty($obj->category)) {
    //    $obj->PHPShopCategory = new PHPShopCategory($row['category']);
    //}
    //$obj->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');	


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
    	//$tab6_html.=$GLOBALS['SysValue']['nav']['nav'];
    	$obj->set('warrantyInfo',$tab6_html);

    	//добавление данных во вкладку доставка
    	if ($GLOBALS['SysValue']['nav']['nav']=='UID'){
    		display_tovar_delivery_hook($obj,$row);
    		display_tovar_samovyvoz_hook($obj, $row);
    	}
   	
    	return true;
    }
    
    $tab6_html=$GLOBALS['SysValue']['lang']['warranty_tab_string1'].PHPShopText::nbsp(1);
    
    $tab6_html.='<select id="select_vendor_brand" name="select_vendor_brand" size="1">';

    //формируем условие для выборки в зависимости от города
    $sub_sql=$obj->select_depend_region($_COOKIE['sincity']);

    //получаем бренд для данного города
    $sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    
    //если для города нет сервисных центров то выводим все сервисные центры для данного бренда
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

    //определяем есть ли среди других сервисов данного производителя сервисы в москве
    $service_existing_region_desicion=$obj->check_service_existing_by_region($_COOKIE['sincity'],$brand);
    
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
	//признак того что нет сервиса в москве
    $no_service_in_moscow=false;
    //выводим данные для города
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
    	//$tab6_html.='<option selected value="c0">Выберите город</option>';
    }

    if ((mysqli_errno)){
    	$res_tab6=mysql_query($sql_tab6);
    	$cnt=0;
	    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
	    	//если выбран другой регион то пытаемся выделить г.Москва
			if ( ($row_tab6['city']=='Москва' && $_COOKIE['sincity']=='m') || ($row_tab6['city']=='Чебоксары' && $_COOKIE['sincity']=='chb') || ($row_tab6['city']=='Санкт-Петербург' && $_COOKIE['sincity']=='sp') || ($_COOKIE['sincity']=='other' && $row_tab6['city']=='Москва') ) {
				$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';   		
	    	} else if ($no_service_in_moscow==false && $service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && ($_COOKIE['sincity']=='chb' || $_COOKIE['sincity']=='sp') && $row_tab6['city']=='Москва') {
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
	    if ($brand!='AL-KO (Германия)'){
	    	$tab6_html.='<b>'.$row_tab6['varranty_time'].'</b>';
	    }
	     
    }
    if ($brand!='AL-KO (Германия)'){
    	$tab6_html.=PHPShopText::nbsp(1).'<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string5'].'</b><br />';
    }  else {
    	$tab6_html.=':<br /><pre style="font: 12px/1.4 Arial,Helvetica,sans-serif;">1. 36 месяцев со дня продажи на бензиновые, электрические колесные газонокосилки и все виды насосов.
2. 60 месяцев со дня продажи на всю специализированную технику серии «PowerLine»
3. 12 месяцев со дня продажи на мини-тракторы, триммеры (ручные косилки электрические), мототриммеры 
    (ручные косилки бензиновые), фронтальные бензиновые косилки (сенокосилки), бензиновые и электрические
    аэраторы, бензиновые и электрические культиваторы, измельчители, электрические и бензиновые пилы, 
    кусторезы, садовые пылесосы, тепловентиляторы электрические, бетономешалки, дровоколы.
4. 24 месяца со дня продажи на всю аккумуляторную технику, электрические и бензиновые снегоуборщики.</pre><br />';  	
    }      
        $tab6_html.='<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string6'].PHPShopText::nbsp(1).$brand.':</b><br />';
    
    $tab6_html.='<!--noindex--><table id="select_varranty_firm" name="select_varranty_firm" cellpadding="5" cellspacing="10" col="3">';
    $tab6_html.='<thead><tr><th style="text-align:left">Наименование</th><th style="text-align:left">Телефон</th><th style="text-align:left">Адрес</th></tr></thead><tbody>';
    
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
    
    $tab6_html.='</tbody></table><!--/noindex-->';
    //$tab6_html.='$no_service_in_moscow'.$no_service_in_moscow.'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_current_region\']'.$service_existing_region_desicion['service_existing_in_current_region'].'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_few_region\']'.$service_existing_region_desicion['service_existing_in_few_region'].'<br />';
    //$tab6_html.='$service_existing_region_desicion[\'service_existing_in_moscow\']'.$service_existing_region_desicion['service_existing_in_moscow'].'<br />';
    
    //$tab6_html='test';
    $obj->set('warrantyInfo',$tab6_html);

    //добавление данных во вкладку доставка    
    if ($GLOBALS['SysValue']['nav']['nav']=='UID'){
    	display_tovar_delivery_hook($obj,$row); 
    	display_tovar_samovyvoz_hook($obj, $row);
    }

    return true;
}
 
/**
 * Вывод tab с условиями по доставке выбранного товара
 **/
function display_tovar_delivery_hook($obj, $row) {
	$city='Москва';
	$dostavka5='';
	$tovar=$row['name'];
	
	//цена товара для подстановки доставки
	$price=0;
	// Если товар на складе
	if (empty($row['sklad'])) {
		if (empty($row['price_n'])) {
			if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
				$price=$row['price2'];
			} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
				$price=$row['price3'];
			}
			else {
				$price=$row['price'];
			}
		}
		// Если есть новая цена
		else {
			if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
				$price=$row['price2'];
			} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
				$price=$row['price3'];
			}
			else {
				$price=$row['price'];
			}
		}
	}	
	// Товар под заказ
	else {
		//$this->set('collaboration','lostandfound');
		if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
			$price=$row['price2'];
		} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
			$price=$row['price3'];
		}
		else {
			$price=intval($row['price']);
		}			
	}
	//Шапка tab доставка
	$tab7_html=$GLOBALS['SysValue']['lang']['delivery_tab_string8'];
	//надпись ваш город и select с городом
	if ($price>=1000){
		//$tab7_html.=$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
		//выбираем город
		//$tab7_html.='<select id="select_delivery_city" name="select_delivery_city" size="1">';
	}
	
	switch ($_COOKIE['sincity']) {
		case 'm': $city='по Москве и Московской области';//$city='Москва,Московская область';
			//$sub_sql1='where id in (3,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,42) and enabled=\'1\' order by city asc';
			if ($price>=1000 && $price<=4999) {
				$sub_sql1=" where (enabled='1' and id in ('41')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string11'].$city.$GLOBALS['SysValue']['lang']['delivery_tab_string12'].$GLOBALS['SysValue']['lang']['delivery_tab_string13'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=5000 && $price<=9999) {
				$sub_sql1=" where (enabled='1' and id in ('11','3','24','22')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].$GLOBALS['SysValue']['lang']['delivery_tab_string14'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=10000 && $price<=19999) {
				$sub_sql1=" where (enabled='1' and id in ('11','66','23','25')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].$GLOBALS['SysValue']['lang']['delivery_tab_string14'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=20000 && $price<=49999) {
				$sub_sql1=" where (enabled='1' and id in ('11','66','26','27','28','38','39','40')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].$GLOBALS['SysValue']['lang']['delivery_tab_string15'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=50000) {
				$sub_sql1=" where (enabled='1' and id in ('11','66','29','30','31','32','33','34','35','36','37','42')) order by city asc";
			}
			//доставка отсутствует
			if ($price<1000) {
				//return true;
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			}
			break;
		case 'sp':  $city='по Санкт-Петербургу и Ленинградской области';//$city='Санкт-Петербург,Ленинградская область';
			if ($price>=1000 && $price<=4999) {
				$sub_sql1=" where (enabled='1' and id in ('41')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string11'].$city.$GLOBALS['SysValue']['lang']['delivery_tab_string12'].$GLOBALS['SysValue']['lang']['delivery_tab_string13'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=5000 && $price<=9999) {
				$sub_sql1=" where (enabled='1' and id in ('11','44','45','47')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].$GLOBALS['SysValue']['lang']['delivery_tab_string14'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=10000 && $price<=19999) {
				$sub_sql1=" where (enabled='1' and id in ('11','64','46','48')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].$GLOBALS['SysValue']['lang']['delivery_tab_string14'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=20000 && $price<=49999) {
				$sub_sql1=" where (enabled='1' and id in ('11','64','49','50','51','61','62')) order by city asc";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string5'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			}
			if ($price>=50000) {
				$sub_sql1=" where (enabled='1' and id in ('11','64','52','53','54','55','56','57','58','59','60','63')) order by city asc";
			}
			//доставка отсутствует 		
			if ($price<1000) {
				//return true;
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			}		
			break;
		case 'chb': //return true; 
			// для чебоксар доставки нет
			//$city='Чебоксары';
			$city='по Чебоксарам';
			if ($price<1000) {
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			} else {	
				$sub_sql1='where id in (43) and enabled=\'1\' order by city asc';
			}
			break;
		case 'other':
			//$city='Другой';
			$city='для других регионов';
			// для "другого региона" доставки нет
			if ($price<1000) {
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'].PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
			} else {
				if ($price<3000){
					$sub_sql1='where id in (41) and enabled=\'1\' order by city asc';
				} else {
					$sub_sql1='where id in (11) and enabled=\'1\' order by city asc';
				}
			}
			break;
	}
	//select с городом
	if ($price>=1000){
		//$tab7_html.='<option value="a0">'.$city.'</option>';
		//$tab7_html.='</select>';
	}
	//добавляем в заголовок город доставки
	$tab7_html.=PHPShopText::nbsp(1).$city.'</b></div>';	
	
	//запись про стоимость доставки в чебоксарах доставки нет
	if ($price>=1000 && $_COOKIE['sincity']!='chb'){
		$tab7_html.=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string1'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string2'];
	}	

	//выбираем данные по доставке для города в чебоксарах доставки нет

	$sql_tab7="SELECT distinct city,price FROM ".$GLOBALS['SysValue']['base']['delivery']." ".$sub_sql1;
	$res_tab7=mysql_query($sql_tab7);
	//$tab7_html.=$sql_tab7;
	if ($_COOKIE['sincity']!='chb'){
		if ($price>=1000){
			$tab7_html.='<!--noindex--><table id="select_delivery_rules" name="select_delivery_rules" cellpadding="5" cellspacing="10" col="3">';
			$tab7_html.='<thead><tr><th style="text-align:left">Условия</th><th style="text-align:left">Стоимость доставки</th><th></th></tr></thead><tbody>';
		}
		
		$cnt=0;
		$dostavka_price='0';
		while ($row_tab7=mysql_fetch_assoc($res_tab7)) {
			$tab7_html.='<tr>';
			if ($price>=10000 && $row_tab7['city']=='Москва в пределах МКАД') {
				$dostavka_price='бесплатно';
			} else {
				$dostavka_price=$row_tab7['price'];
				$city=$row_tab7['city'];
			}
			switch ($row_tab7['city']) {
				case 'Москва в пределах МКАД':
					$city='- Москва в пределах МКАД';
					break;									
				case 'Москва за пределами МКАД до 10 км.':
					//dev 'Москва за пределами МКАД до 10 км. от 20000 руб.'
					$city='- Москва за пределами МКАД до 10 км.';
					break;
				case 'Москва за пределами МКАД от 11 до 20 км.':
					//dev 'Москва за пределами МКАД от 11 до 20 км. от 20000 руб.'
					$city='- Москва за пределами МКАД от 11 до 20 км.';
					break;
				case 'Москва за пределами МКАД от 21 до 30 км.':
					//dev 'Москва за пределами МКАД от 21 до 30 км. от 20000 руб.'
					$city='- Москва за пределами МКАД от 21 до 30 км.';
					break;
				case 'Москва за пределами МКАД от 31 до 40 км.':
					//dev 'Москва за пределами МКАД от 31 до 40 км. от 20000 руб.'
					$city='- Москва за пределами МКАД от 31 до 40 км.';
					break;
				case 'Москва за пределами МКАД от 41 до 50 км.':
					//dev 'Москва за пределами МКАД от 41 до 50 км. от 20000 руб.'
					$city='- Москва за пределами МКАД от 41 до 50 км.';
					break;
				case 'Москва за пределами МКАД до 10 км.':
					//dev 'Москва за пределами МКАД до 10 км. от 3000 до 9999 руб.'
					$city='- Москва за пределами МКАД до 10 км.';
					break;
				case 'Москва за пределами МКАД до 10 км.':
					//dev 'Москва за пределами МКАД до 10 км. от 10000 руб.'
					$city='- Москва за пределами МКАД до 10 км.';
					break;
				case 'Москва за пределами МКАД от 11 до 20 км.':
					//dev 'Москва за пределами МКАД от 11 до 20 км. от 3000 до 9999 руб.'
					$city='- Москва за пределами МКАД от 11 до 20 км.';
					break;
				case 'Москва за пределами МКАД от 11 до 20 км.':
					//dev 'Москва за пределами МКАД от 11 до 20 км. от 10000 руб.'
					$city='- Москва за пределами МКАД от 11 до 20 км.';
					break;
				case 'Москва за пределами МКАД от 51 до 60 км.':
					//dev 'Москва за пределами МКАД от 51 до 60 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 51 до 60 км.';
					break;
				case 'Москва за пределами МКАД от 61 до 70 км.':
					//dev 'Москва за пределами МКАД от 61 до 70 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 61 до 70 км.';
					break;
				case 'Москва за пределами МКАД от 71 до 80 км.':
					//dev 'Москва за пределами МКАД от 71 до 80 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 71 до 80 км.';
					break;
				case 'Москва за пределами МКАД от 81 до 90 км.':
					//dev 'Москва за пределами МКАД от 81 до 90 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 81 до 90 км.';
					break;
				case 'Москва за пределами МКАД от 91 до 100 км.':
					//dev 'Москва за пределами МКАД от 91 до 100 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 91 до 100 км.';
					break;
				case 'Москва за пределами МКАД до 10 км.';
					//dev 'Москва за пределами МКАД до 10 км. от 50000 руб.'
					$city='- Москва за пределами МКАД до 10 км.';
					break;
				case 'Москва за пределами МКАД от 21 до 30 км.':
					//dev 'Москва за пределами МКАД от 21 до 30 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 21 до 30 км.';
					break;
				case 'Москва за пределами МКАД от 31 до 40 км.':
					//dev 'Москва за пределами МКАД от 31 до 40 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 31 до 40 км.';
					break;
				case 'Москва за пределами МКАД от 41 до 50 км.':
					//dev 'Москва за пределами МКАД от 41 до 50 км. от 50000 руб.'
					$city='- Москва за пределами МКАД от 41 до 50 км.';
					break;
				case 'Санкт-Петербург за пределами КАД до 10 км.':
					//dev 'Санкт-Петербург за пределами КАД до 10 км. от 20000 руб.'
					$city='- За пределами КАД до 10 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 11 до 20 км.':
					//dev 'Санкт-Петербург за пределами КАД от 11 до 20 км. от 20000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 11 до 20 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 21 до 30 км.':
					//dev 'Санкт-Петербург за пределами КАД от 21 до 30 км. от 20000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 21 до 30 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 31 до 40 км.':
					//dev 'Санкт-Петербург за пределами КАД от 31 до 40 км. от 20000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 31 до 40 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 41 до 50 км.':
					//dev 'Санкт-Петербург за пределами КАД от 41 до 50 км. от 20000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 41 до 50 км.';
					break;
				case 'Санкт-Петербург за пределами КАД до 10 км.':
					//dev 'Санкт-Петербург за пределами КАД до 10 км. от 3000 до 9999 руб.'
					$city='- Санкт-Петербург за пределами КАД до 10 км.';
					break;
				case 'Санкт-Петербург за пределами КАД до 10 км.':
					//dev 'Санкт-Петербург за пределами КАД до 10 км. от 10000 руб.'
					$city='- Санкт-Петербург за пределами КАД до 10 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 11 до 20 км.':
					//dev 'Санкт-Петербург за пределами КАД от 11 до 20 км. от 3000 до 9999 руб.'
					$city='- Санкт-Петербург за пределами КАД от 11 до 20 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 11 до 20 км.':
					//dev 'Санкт-Петербург за пределами КАД от 11 до 20 км. от 10000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 11 до 20 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 51 до 60 км.':
					//dev 'Санкт-Петербург за пределами КАД от 51 до 60 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 51 до 60 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 61 до 70 км.':
					//dev 'Санкт-Петербург за пределами КАД от 61 до 70 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 61 до 70 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 71 до 80 км.':
					//dev 'Санкт-Петербург за пределами КАД от 71 до 80 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 71 до 80 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 81 до 90 км.':
					//dev 'Санкт-Петербург за пределами КАД от 81 до 90 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 81 до 90 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 91 до 100 км.':
					//dev 'Санкт-Петербург за пределами КАД от 91 до 100 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 91 до 100 км.';
					break;
				case 'Санкт-Петербург за пределами КАД до 10 км.':
					//dev 'Санкт-Петербург за пределами КАД до 10 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД до 10 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 21 до 30 км.':
					//dev 'Санкт-Петербург за пределами КАД от 21 до 30 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 21 до 30 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 31 до 40 км.':
					//dev 'Санкт-Петербург за пределами КАД от 31 до 40 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 31 до 40 км.';
					break;
				case 'Санкт-Петербург за пределами КАД от 41 до 50 км.':
					//dev 'Санкт-Петербург за пределами КАД от 41 до 50 км. от 50000 руб.'
					$city='- Санкт-Петербург за пределами КАД от 41 до 50 км.';
					break;
				case 'Доставка и отгрузка в транспортную компанию (для отправки по России)';
					$city='- Доставка и отгрузка в транспортную компанию<br />для отправки по России';
					break;
				case 'Санкт-Петербург в пределах КАД';
					$city='- Санкт-Петербург в пределах КАД';
					break;
			}
			if ($dostavka_price=='бесплатно') {
				$tab7_html.='<td>'.$city.'</td><td>'.$dostavka_price.'</td>';
			} else {
				if ($city=='- Доставка и отгрузка в транспортную компанию<br />для отправки по России') {
					$tab7_html.='<td><!--/noindex-->'.$city.'<!--noindex--></td><td><!--/noindex-->'.$dostavka_price.' руб.<!--noindex--></td><td><a href="#edost"><u>Расчет стоимости услуг транспортных компаний</u></a></td>';
				} else	$tab7_html.='<td>'.$city.'</td><td>'.$dostavka_price.' руб.</td><td></td>';
			}
			$tab7_html.='</tr>';
		}
		
		if ($price>=1000){
			$tab7_html.='</tbody></table><!--/noindex-->';
		}
	}
    
    //$tab7_html.=$dostavka5;
    //$tab7_html.=PHPShopText::br(1).'</br>'.PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
	if ($price<1000 && $_COOKIE['sincity']=='chb'){
		//$tab7_html.=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
		$tab7_html.=$GLOBALS['SysValue']['lang']['delivery_tab_string9'].$city.$GLOBALS['SysValue']['lang']['delivery_tab_string10'];
	} else if ( $price<1000 && ($_COOKIE['sincity']=='m' || $_COOKIE['sincity']=='sp') ) {
		$tab7_html.=$dostavka5;
		$tab7_html.=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string3'];
	} else if ($price<1000 && $_COOKIE['sincity']=='other'){
		$tab7_html.=$dostavka5;
		$tab7_html.=PHPShopText::br(1).'</br>'.PHPShopText::a('http://prodacha.ru/page/delivery.html',$GLOBALS['SysValue']['lang']['delivery_tab_string4']);
	}
    // в чебоксарах доставки нет
	if ($price>=1000 && $_COOKIE['sincity']!='chb') {
		$tab7_html.=$dostavka5;
		if ($_COOKIE['sincity']=='other'){
			$tab7_html.=PHPShopText::a('http://prodacha.ru/page/delivery.html',$GLOBALS['SysValue']['lang']['delivery_tab_string4']);
		} else {
			$tab7_html.=PHPShopText::br(1).'</br>'.PHPShopText::a('http://prodacha.ru/page/delivery.html',$GLOBALS['SysValue']['lang']['delivery_tab_string4']);
		}
	} else if ($price>=1000 && $_COOKIE['sincity']=='chb') {
		$tab7_html.=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string9'].$city.$GLOBALS['SysValue']['lang']['delivery_tab_string10'];
	}
    //$tab7_html.=$price;//$obj->get('productPrice');
	$obj->set('deliveryInfo',$tab7_html);
	
	//пример рассчета доставки
	$edostout='<a name="edost"></a><p><b style="font-size:18px;">Доставка по России через транспортные компании и Почту России</b></p>';
	$edostout.='<form name="calc" method="post" onSubmit="return false;">';
	$edostout.='<table id="calc1" width="700" cellpadding="5" cellspacing="0" border="0">';
	
	$edostout.='<tr height="20">';
	$edostout.='<td width="100" align="center"></td>';
	$edostout.='<td width="200" align="center">Город:</td>';
	$edostout.='<td width="300" align="center">Регион:</td>';
	$edostout.='<td width="300" align="left">Индекс:</td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="25">';
	$edostout.='<td align="right">Куда:</td>';
	$edostout.='<td align="right"><input type="text" id="edost_to_city" name="edost_to_city" size="35" maxlength="80"  data-html="true" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="Начните вводить название города" value=""></td>';
	$edostout.='<td align="center"><span id="ToReg">-</span></td>';
	$edostout.='<td><input type="text" id="edost_zip" name="edost_zip" size="6" maxlength="6" title="для отправки Почтой России"></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Индекс:</td>';
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_zip1" name="edost_zip1" size="6" maxlength="6"></td>';
	$edostout.='<td style="visibility:hidden;"><p id="Err1" style="color:red"></p></td>';
	$edostout.='<td style="visibility:hidden;""></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="35">';
	$edostout.='<td align="right"></td>';
	$edostout.='<td><input class="ui-button-text-only" type="submit" name="B_Calc" value="Рассчитать" onclick="cr1();" style="color:black;"></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Вес:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_weight" name="edost_weight" size="5" maxlength="5" value="'.$row['weight'].'"> кг.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><p id="Err1" style="color:red"></p></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Оценка:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_strah" name="edost_strah" size="10" maxlength="12" value="'.$price.'"> руб.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Длина:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_lenght" name="edost_lenght" size="10" maxlength="10" value="'.$row['length'].'"> см.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Ширина:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_width" name="edost_width" size="10" maxlength="10" value="'.$row['width'].'"> см.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">Высота:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_height" name="edost_height" size="10" maxlength="10" value="'.$row['height'].'"> см.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='</table>';
	
	$edostout.='</form>';
	
	$edostout.='<span id="rz" name="rz"></span>';
	
	$obj->set('edostInfo',$edostout,true);
	
}

/**
 * Вывод tab с условиями самовывозу по выбранного товара
 **/
function display_tovar_samovyvoz_hook($obj, $row) {
	$tovar=$row['name'];
	//цена товара для подстановки доставки
	$price=0;
	// Если товар на складе
	if (empty($row['sklad'])) {
		if (empty($row['price_n'])) {
			if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
				$price=$row['price2'];
			} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
				$price=$row['price3'];
			}
			else {
				$price=$row['price'];
			}
		}
		// Если есть новая цена
		else {
			if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
				$price=$row['price2'];
			} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
				$price=$row['price3'];
			}
			else {
				$price=$row['price'];
			}
		}
	}
	// Товар под заказ
	else {
		//$this->set('collaboration','lostandfound');
		if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
			$price=$row['price2'];
		} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
			$price=$row['price3'];
		}
		else {
			$price=intval($row['price']);
		}
	}
	
	$tab7_1_html=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['samovyvoz_tab_string1'];
	//$tab7_1_html.=PHPShopText::br(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
	
	//выбираем город

	switch ($_COOKIE['sincity']) {
		case 'm': $city='Москва,Московская область';
			$sub_sql1='where id in (10,13) and enabled=\'1\' order by city asc';
			break;
		case 'sp':  $city='Санкт-Петербург,Ленинградская область';
			$sub_sql1='where id in (14) and enabled=\'1\' order by city asc';
			break;
		case 'chb':  $city='Чебоксары';
			$sub_sql1='where id in (43) and enabled=\'1\' order by city asc';		
		break;
		case 'other': return true;
			$city='Другой';
			if ($price>=3000) {
				$sub_sql1='where id in (11) and enabled=\'1\' order by city asc';			
			} else {
				$sub_sql1='where id in (41) and enabled=\'1\' order by city asc';			
			}
			break;
	}
	
	$tab7_1_html.=$GLOBALS['SysValue']['lang']['samovyvoz_tab_string2'].$tovar.$GLOBALS['SysValue']['lang']['samovyvoz_tab_string3'];

	//выбираем данные по доставке для города
	
	$sql_tab7="SELECT distinct city,price FROM ".$GLOBALS['SysValue']['base']['delivery']." ".$sub_sql1;
	$res_tab7=mysql_query($sql_tab7);
	$tab7_1_html.='<table id="select_delivery_rules" name="select_delivery_rules" cellpadding="5" cellspacing="10" col="2">';
	$tab7_1_html.='<thead><tr><td style="text-align:left">Адрес:</td></tr></thead><tbody>';
	
	while ($row_tab7=mysql_fetch_assoc($res_tab7)) {
		$tab7_1_html.='<tr>';
		$tab7_1_html.='<td>- '.preg_replace('/[\(\)]/','',str_replace('Самовывоз','',$row_tab7['city'])).'</td>';
		$tab7_1_html.='</tr>';
	}
	
	$tab7_1_html.='</tbody></table>';
	
	$tab7_1_html.=$GLOBALS['SysValue']['lang']['samovyvoz_tab_string4'];
	
	$obj->set('deliveryInfo',$tab7_1_html,true);	
}

	
$addHandler=array
(
        'checkStore'=>'checkStore_add_sorttable_hook'
);
?>