<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * ����� ������������� � ������� ��������  ������
 **/
function checkStore_add_sorttable_hook($obj, $row) {

    //if (empty($obj->category)) {
    //    $obj->PHPShopCategory = new PHPShopCategory($row['category']);
    //}
    //$obj->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');	


	// ������ ����
    $obj->set('productPrice2',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price2'],$row['baseinputvaluta']));
 
    // ���� ����� ��������� ����
    $obj->set('productPrice3',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price3'],$row['baseinputvaluta']));
    $obj->set('productPrice4',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price4'],$row['baseinputvaluta']));
    $obj->set('productPrice5',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price5'],$row['baseinputvaluta']));
	
    //���������� ������ �� ������� �������� � ������

    $sub_sql2="";
    //���� ���� �������������
    if ( $obj->get('vendorName')!='' ) {
    	$sub_sql1="brand='".$obj->get('vendorName')."'";
    	$sub_sql2=" and brand='".$obj->get('vendorName')."'";
    	//echo $sub_sql2;
    }
    //���� �� � ���� ��������� ������ � ��������
    $sql_tab6="SELECT brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." where ".$sub_sql1;
    $res_tab6=mysql_query($sql_tab6);
    //���� ��� ������������� � ��� ��������� ������� ����� ������� ����������� �����
    if (mysql_num_rows($res_tab6)==0 || $obj->get('vendorName')=='') {
    	$tab6_html=PHPShopText::div($GLOBALS['SysValue']['lang']['warranty_tab_string7'],$align = "left");
    	//$tab6_html.=$GLOBALS['SysValue']['nav']['nav'];
    	$obj->set('warrantyInfo',$tab6_html);

    	//���������� ������ �� ������� ��������
    	if ($GLOBALS['SysValue']['nav']['nav']=='UID'){
    		display_tovar_delivery_hook($obj,$row);
    		display_tovar_samovyvoz_hook($obj, $row);
    	}
   	
    	return true;
    }
    
    $tab6_html=$GLOBALS['SysValue']['lang']['warranty_tab_string1'].PHPShopText::nbsp(1);
    
    $tab6_html.='<select id="select_vendor_brand" name="select_vendor_brand" size="1">';

    //��������� ������� ��� ������� � ����������� �� ������
    $sub_sql=$obj->select_depend_region($_COOKIE['sincity']);

    //�������� ����� ��� ������� ������
    $sql_tab6="SELECT distinct brand FROM ".$GLOBALS['SysValue']['base']['service_and_varranty']." ".$sub_sql[0].$sub_sql2;
    $res_tab6=mysql_query($sql_tab6);
    
    //���� ��� ������ ��� ��������� ������� �� ������� ��� ��������� ������ ��� ������� ������
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

    //���������� ���� �� ����� ������ �������� ������� ������������� ������� � ������
    $service_existing_region_desicion=$obj->check_service_existing_by_region($_COOKIE['sincity'],$brand);
    
    $tab6_html.=PHPShopText::nbsp(1).$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
	//������� ���� ��� ��� ������� � ������
    $no_service_in_moscow=false;
    //������� ������ ��� ������
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
    	//$tab6_html.='<option selected value="c0">�������� �����</option>';
    }

    if ((mysqli_errno)){
    	$res_tab6=mysql_query($sql_tab6);
    	$cnt=0;
	    while ($row_tab6=mysql_fetch_assoc($res_tab6)) {
	    	//���� ������ ������ ������ �� �������� �������� �.������
			if ( ($row_tab6['city']=='������' && $_COOKIE['sincity']=='m') || ($row_tab6['city']=='���������' && $_COOKIE['sincity']=='chb') || ($row_tab6['city']=='�����-���������' && $_COOKIE['sincity']=='sp') || ($_COOKIE['sincity']=='other' && $row_tab6['city']=='������') ) {
				$tab6_html.='<option selected value="c'.$cnt.'">'.$row_tab6['city'].'</option>';   		
	    	} else if ($no_service_in_moscow==false && $service_existing_region_desicion['service_existing_in_current_region']==0 && $service_existing_region_desicion['service_existing_in_few_region']==1 && ($_COOKIE['sincity']=='chb' || $_COOKIE['sincity']=='sp') && $row_tab6['city']=='������') {
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
	    if ($brand!='AL-KO (��������)'){
	    	$tab6_html.='<b>'.$row_tab6['varranty_time'].'</b>';
	    }
	     
    }
    if ($brand!='AL-KO (��������)'){
    	$tab6_html.=PHPShopText::nbsp(1).'<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string5'].'</b><br />';
    }  else {
    	$tab6_html.=':<br /><pre style="font: 12px/1.4 Arial,Helvetica,sans-serif;">1. 36 ������� �� ��� ������� �� ����������, ������������� �������� ������������� � ��� ���� �������.
2. 60 ������� �� ��� ������� �� ��� ������������������ ������� ����� �PowerLine�
3. 12 ������� �� ��� ������� �� ����-��������, �������� (������ ������� �������������), ������������ 
    (������ ������� ����������), ����������� ���������� ������� (�����������), ���������� � �������������
    ��������, ���������� � ������������� ������������, ������������, ������������� � ���������� ����, 
    ���������, ������� ��������, ���������������� �������������, �������������, ���������.
4. 24 ������ �� ��� ������� �� ��� �������������� �������, ������������� � ���������� �������������.</pre><br />';  	
    }      
        $tab6_html.='<b>'.$GLOBALS['SysValue']['lang']['warranty_tab_string6'].PHPShopText::nbsp(1).$brand.':</b><br />';
    
    $tab6_html.='<!--noindex--><table id="select_varranty_firm" name="select_varranty_firm" cellpadding="5" cellspacing="10" col="3">';
    $tab6_html.='<thead><tr><th style="text-align:left">������������</th><th style="text-align:left">�������</th><th style="text-align:left">�����</th></tr></thead><tbody>';
    
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

    //���������� ������ �� ������� ��������    
    if ($GLOBALS['SysValue']['nav']['nav']=='UID'){
    	display_tovar_delivery_hook($obj,$row); 
    	display_tovar_samovyvoz_hook($obj, $row);
    }

    return true;
}
 
/**
 * ����� tab � ��������� �� �������� ���������� ������
 **/
function display_tovar_delivery_hook($obj, $row) {
	$city='������';
	$dostavka5='';
	$tovar=$row['name'];
	
	//���� ������ ��� ����������� ��������
	$price=0;
	// ���� ����� �� ������
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
		// ���� ���� ����� ����
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
	// ����� ��� �����
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
	//����� tab ��������
	$tab7_html=$GLOBALS['SysValue']['lang']['delivery_tab_string8'];
	//������� ��� ����� � select � �������
	if ($price>=1000){
		//$tab7_html.=$GLOBALS['SysValue']['lang']['warranty_tab_string2'].PHPShopText::nbsp(1);
		//�������� �����
		//$tab7_html.='<select id="select_delivery_city" name="select_delivery_city" size="1">';
	}
	
	switch ($_COOKIE['sincity']) {
		case 'm': $city='�� ������ � ���������� �������';//$city='������,���������� �������';
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
			//�������� �����������
			if ($price<1000) {
				//return true;
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			}
			break;
		case 'sp':  $city='�� �����-���������� � ������������� �������';//$city='�����-���������,������������� �������';
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
			//�������� ����������� 		
			if ($price<1000) {
				//return true;
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			}		
			break;
		case 'chb': //return true; 
			// ��� �������� �������� ���
			//$city='���������';
			$city='�� ����������';
			if ($price<1000) {
				$sub_sql1=" where enabled='-1'";
				$dostavka5=$GLOBALS['SysValue']['lang']['delivery_tab_string6'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string7'];
			} else {	
				$sub_sql1='where id in (43) and enabled=\'1\' order by city asc';
			}
			break;
		case 'other':
			//$city='������';
			$city='��� ������ ��������';
			// ��� "������� �������" �������� ���
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
	//select � �������
	if ($price>=1000){
		//$tab7_html.='<option value="a0">'.$city.'</option>';
		//$tab7_html.='</select>';
	}
	//��������� � ��������� ����� ��������
	$tab7_html.=PHPShopText::nbsp(1).$city.'</b></div>';	
	
	//������ ��� ��������� �������� � ���������� �������� ���
	if ($price>=1000 && $_COOKIE['sincity']!='chb'){
		$tab7_html.=PHPShopText::br(1).'</br>'.$GLOBALS['SysValue']['lang']['delivery_tab_string1'].$tovar.$GLOBALS['SysValue']['lang']['delivery_tab_string2'];
	}	

	//�������� ������ �� �������� ��� ������ � ���������� �������� ���

	$sql_tab7="SELECT distinct city,price FROM ".$GLOBALS['SysValue']['base']['delivery']." ".$sub_sql1;
	$res_tab7=mysql_query($sql_tab7);
	//$tab7_html.=$sql_tab7;
	if ($_COOKIE['sincity']!='chb'){
		if ($price>=1000){
			$tab7_html.='<!--noindex--><table id="select_delivery_rules" name="select_delivery_rules" cellpadding="5" cellspacing="10" col="3">';
			$tab7_html.='<thead><tr><th style="text-align:left">�������</th><th style="text-align:left">��������� ��������</th><th></th></tr></thead><tbody>';
		}
		
		$cnt=0;
		$dostavka_price='0';
		while ($row_tab7=mysql_fetch_assoc($res_tab7)) {
			$tab7_html.='<tr>';
			if ($price>=10000 && $row_tab7['city']=='������ � �������� ����') {
				$dostavka_price='���������';
			} else {
				$dostavka_price=$row_tab7['price'];
				$city=$row_tab7['city'];
			}
			switch ($row_tab7['city']) {
				case '������ � �������� ����':
					$city='- ������ � �������� ����';
					break;									
				case '������ �� ��������� ���� �� 10 ��.':
					//dev '������ �� ��������� ���� �� 10 ��. �� 20000 ���.'
					$city='- ������ �� ��������� ���� �� 10 ��.';
					break;
				case '������ �� ��������� ���� �� 11 �� 20 ��.':
					//dev '������ �� ��������� ���� �� 11 �� 20 ��. �� 20000 ���.'
					$city='- ������ �� ��������� ���� �� 11 �� 20 ��.';
					break;
				case '������ �� ��������� ���� �� 21 �� 30 ��.':
					//dev '������ �� ��������� ���� �� 21 �� 30 ��. �� 20000 ���.'
					$city='- ������ �� ��������� ���� �� 21 �� 30 ��.';
					break;
				case '������ �� ��������� ���� �� 31 �� 40 ��.':
					//dev '������ �� ��������� ���� �� 31 �� 40 ��. �� 20000 ���.'
					$city='- ������ �� ��������� ���� �� 31 �� 40 ��.';
					break;
				case '������ �� ��������� ���� �� 41 �� 50 ��.':
					//dev '������ �� ��������� ���� �� 41 �� 50 ��. �� 20000 ���.'
					$city='- ������ �� ��������� ���� �� 41 �� 50 ��.';
					break;
				case '������ �� ��������� ���� �� 10 ��.':
					//dev '������ �� ��������� ���� �� 10 ��. �� 3000 �� 9999 ���.'
					$city='- ������ �� ��������� ���� �� 10 ��.';
					break;
				case '������ �� ��������� ���� �� 10 ��.':
					//dev '������ �� ��������� ���� �� 10 ��. �� 10000 ���.'
					$city='- ������ �� ��������� ���� �� 10 ��.';
					break;
				case '������ �� ��������� ���� �� 11 �� 20 ��.':
					//dev '������ �� ��������� ���� �� 11 �� 20 ��. �� 3000 �� 9999 ���.'
					$city='- ������ �� ��������� ���� �� 11 �� 20 ��.';
					break;
				case '������ �� ��������� ���� �� 11 �� 20 ��.':
					//dev '������ �� ��������� ���� �� 11 �� 20 ��. �� 10000 ���.'
					$city='- ������ �� ��������� ���� �� 11 �� 20 ��.';
					break;
				case '������ �� ��������� ���� �� 51 �� 60 ��.':
					//dev '������ �� ��������� ���� �� 51 �� 60 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 51 �� 60 ��.';
					break;
				case '������ �� ��������� ���� �� 61 �� 70 ��.':
					//dev '������ �� ��������� ���� �� 61 �� 70 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 61 �� 70 ��.';
					break;
				case '������ �� ��������� ���� �� 71 �� 80 ��.':
					//dev '������ �� ��������� ���� �� 71 �� 80 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 71 �� 80 ��.';
					break;
				case '������ �� ��������� ���� �� 81 �� 90 ��.':
					//dev '������ �� ��������� ���� �� 81 �� 90 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 81 �� 90 ��.';
					break;
				case '������ �� ��������� ���� �� 91 �� 100 ��.':
					//dev '������ �� ��������� ���� �� 91 �� 100 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 91 �� 100 ��.';
					break;
				case '������ �� ��������� ���� �� 10 ��.';
					//dev '������ �� ��������� ���� �� 10 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 10 ��.';
					break;
				case '������ �� ��������� ���� �� 21 �� 30 ��.':
					//dev '������ �� ��������� ���� �� 21 �� 30 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 21 �� 30 ��.';
					break;
				case '������ �� ��������� ���� �� 31 �� 40 ��.':
					//dev '������ �� ��������� ���� �� 31 �� 40 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 31 �� 40 ��.';
					break;
				case '������ �� ��������� ���� �� 41 �� 50 ��.':
					//dev '������ �� ��������� ���� �� 41 �� 50 ��. �� 50000 ���.'
					$city='- ������ �� ��������� ���� �� 41 �� 50 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 10 ��.':
					//dev '�����-��������� �� ��������� ��� �� 10 ��. �� 20000 ���.'
					$city='- �� ��������� ��� �� 10 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 11 �� 20 ��.':
					//dev '�����-��������� �� ��������� ��� �� 11 �� 20 ��. �� 20000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 11 �� 20 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 21 �� 30 ��.':
					//dev '�����-��������� �� ��������� ��� �� 21 �� 30 ��. �� 20000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 21 �� 30 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 31 �� 40 ��.':
					//dev '�����-��������� �� ��������� ��� �� 31 �� 40 ��. �� 20000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 31 �� 40 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 41 �� 50 ��.':
					//dev '�����-��������� �� ��������� ��� �� 41 �� 50 ��. �� 20000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 41 �� 50 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 10 ��.':
					//dev '�����-��������� �� ��������� ��� �� 10 ��. �� 3000 �� 9999 ���.'
					$city='- �����-��������� �� ��������� ��� �� 10 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 10 ��.':
					//dev '�����-��������� �� ��������� ��� �� 10 ��. �� 10000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 10 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 11 �� 20 ��.':
					//dev '�����-��������� �� ��������� ��� �� 11 �� 20 ��. �� 3000 �� 9999 ���.'
					$city='- �����-��������� �� ��������� ��� �� 11 �� 20 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 11 �� 20 ��.':
					//dev '�����-��������� �� ��������� ��� �� 11 �� 20 ��. �� 10000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 11 �� 20 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 51 �� 60 ��.':
					//dev '�����-��������� �� ��������� ��� �� 51 �� 60 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 51 �� 60 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 61 �� 70 ��.':
					//dev '�����-��������� �� ��������� ��� �� 61 �� 70 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 61 �� 70 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 71 �� 80 ��.':
					//dev '�����-��������� �� ��������� ��� �� 71 �� 80 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 71 �� 80 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 81 �� 90 ��.':
					//dev '�����-��������� �� ��������� ��� �� 81 �� 90 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 81 �� 90 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 91 �� 100 ��.':
					//dev '�����-��������� �� ��������� ��� �� 91 �� 100 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 91 �� 100 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 10 ��.':
					//dev '�����-��������� �� ��������� ��� �� 10 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 10 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 21 �� 30 ��.':
					//dev '�����-��������� �� ��������� ��� �� 21 �� 30 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 21 �� 30 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 31 �� 40 ��.':
					//dev '�����-��������� �� ��������� ��� �� 31 �� 40 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 31 �� 40 ��.';
					break;
				case '�����-��������� �� ��������� ��� �� 41 �� 50 ��.':
					//dev '�����-��������� �� ��������� ��� �� 41 �� 50 ��. �� 50000 ���.'
					$city='- �����-��������� �� ��������� ��� �� 41 �� 50 ��.';
					break;
				case '�������� � �������� � ������������ �������� (��� �������� �� ������)';
					$city='- �������� � �������� � ������������ ��������<br />��� �������� �� ������';
					break;
				case '�����-��������� � �������� ���';
					$city='- �����-��������� � �������� ���';
					break;
			}
			if ($dostavka_price=='���������') {
				$tab7_html.='<td>'.$city.'</td><td>'.$dostavka_price.'</td>';
			} else {
				if ($city=='- �������� � �������� � ������������ ��������<br />��� �������� �� ������') {
					$tab7_html.='<td><!--/noindex-->'.$city.'<!--noindex--></td><td><!--/noindex-->'.$dostavka_price.' ���.<!--noindex--></td><td><a href="#edost"><u>������ ��������� ����� ������������ ��������</u></a></td>';
				} else	$tab7_html.='<td>'.$city.'</td><td>'.$dostavka_price.' ���.</td><td></td>';
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
    // � ���������� �������� ���
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
	
	//������ �������� ��������
	$edostout='<a name="edost"></a><p><b style="font-size:18px;">�������� �� ������ ����� ������������ �������� � ����� ������</b></p>';
	$edostout.='<form name="calc" method="post" onSubmit="return false;">';
	$edostout.='<table id="calc1" width="700" cellpadding="5" cellspacing="0" border="0">';
	
	$edostout.='<tr height="20">';
	$edostout.='<td width="100" align="center"></td>';
	$edostout.='<td width="200" align="center">�����:</td>';
	$edostout.='<td width="300" align="center">������:</td>';
	$edostout.='<td width="300" align="left">������:</td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="25">';
	$edostout.='<td align="right">����:</td>';
	$edostout.='<td align="right"><input type="text" id="edost_to_city" name="edost_to_city" size="35" maxlength="80"  data-html="true" data-container="body" class="tooltip" role="tooltip" data-toggle="tooltip" data-content="������� ������� �������� ������" value=""></td>';
	$edostout.='<td align="center"><span id="ToReg">-</span></td>';
	$edostout.='<td><input type="text" id="edost_zip" name="edost_zip" size="6" maxlength="6" title="��� �������� ������ ������"></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">������:</td>';
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_zip1" name="edost_zip1" size="6" maxlength="6"></td>';
	$edostout.='<td style="visibility:hidden;"><p id="Err1" style="color:red"></p></td>';
	$edostout.='<td style="visibility:hidden;""></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="35">';
	$edostout.='<td align="right"></td>';
	$edostout.='<td><input class="ui-button-text-only" type="submit" name="B_Calc" value="����������" onclick="cr1();" style="color:black;"></td>';
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">���:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_weight" name="edost_weight" size="5" maxlength="5" value="'.$row['weight'].'"> ��.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><p id="Err1" style="color:red"></p></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">������:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_strah" name="edost_strah" size="10" maxlength="12" value="'.$price.'"> ���.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">�����:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_lenght" name="edost_lenght" size="10" maxlength="10" value="'.$row['length'].'"> ��.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">������:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_width" name="edost_width" size="10" maxlength="10" value="'.$row['width'].'"> ��.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='<tr height="0">';
	$edostout.='<td style="visibility:hidden;" align="right">������:</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"><input type="text" id="edost_height" name="edost_height" size="10" maxlength="10" value="'.$row['height'].'"> ��.</td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>'; //style="visibility:hidden;"
	$edostout.='<td style="visibility:hidden;"></td>';	//style="visibility:hidden;"
	$edostout.='</tr>';
	
	$edostout.='</table>';
	
	$edostout.='</form>';
	
	$edostout.='<span id="rz" name="rz"></span>';
	
	$obj->set('edostInfo',$edostout,true);
	
}

/**
 * ����� tab � ��������� ���������� �� ���������� ������
 **/
function display_tovar_samovyvoz_hook($obj, $row) {
	$tovar=$row['name'];
	//���� ������ ��� ����������� ��������
	$price=0;
	// ���� ����� �� ������
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
		// ���� ���� ����� ����
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
	// ����� ��� �����
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
	
	//�������� �����

	switch ($_COOKIE['sincity']) {
		case 'm': $city='������,���������� �������';
			$sub_sql1='where id in (10,13) and enabled=\'1\' order by city asc';
			break;
		case 'sp':  $city='�����-���������,������������� �������';
			$sub_sql1='where id in (14) and enabled=\'1\' order by city asc';
			break;
		case 'chb':  $city='���������';
			$sub_sql1='where id in (43) and enabled=\'1\' order by city asc';		
		break;
		case 'other': return true;
			$city='������';
			if ($price>=3000) {
				$sub_sql1='where id in (11) and enabled=\'1\' order by city asc';			
			} else {
				$sub_sql1='where id in (41) and enabled=\'1\' order by city asc';			
			}
			break;
	}
	
	$tab7_1_html.=$GLOBALS['SysValue']['lang']['samovyvoz_tab_string2'].$tovar.$GLOBALS['SysValue']['lang']['samovyvoz_tab_string3'];

	//�������� ������ �� �������� ��� ������
	
	$sql_tab7="SELECT distinct city,price FROM ".$GLOBALS['SysValue']['base']['delivery']." ".$sub_sql1;
	$res_tab7=mysql_query($sql_tab7);
	$tab7_1_html.='<table id="select_delivery_rules" name="select_delivery_rules" cellpadding="5" cellspacing="10" col="2">';
	$tab7_1_html.='<thead><tr><td style="text-align:left">�����:</td></tr></thead><tbody>';
	
	while ($row_tab7=mysql_fetch_assoc($res_tab7)) {
		$tab7_1_html.='<tr>';
		$tab7_1_html.='<td>- '.preg_replace('/[\(\)]/','',str_replace('���������','',$row_tab7['city'])).'</td>';
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