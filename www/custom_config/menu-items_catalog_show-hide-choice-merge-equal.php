<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/config_functions.php');

//include_once('phpshop/class/base.class.php');
//PHPShopObj::loadClass('base');
//PHPShopObj::loadClass('system');
//$PHPShopBase = new PHPShopBase('phpshop/inc/config.ini',false);
/*
				$host="localhost";          
				$user_db="u301639_test";           
				$pass_db="-o97iCiAlLop.";       
				$dbase="u301639_test"; 
*/
				/*
				$host="u301639.mysql.masterhost.ru";          
				$user_db="u301639_test";           
				$pass_db="-o97iCiAlLop.";       
				$dbase="u301639_test";
				*/
/*
				$host=$GLOBALS['SysValue']['connect']['host'];          
				$user_db=$GLOBALS['SysValue']['connect']['user_db'];           
				$pass_db=$GLOBALS['SysValue']['connect']['pass_db'];       
				$dbase=$GLOBALS['SysValue']['connect']['dbase']; 
*/
				$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
				
				$host=$SysValue['connect']['host'];
				$user_db=$SysValue['connect']['user_db'];
				$pass_db=$SysValue['connect']['pass_db'];
				$dbase=$SysValue['connect']['dbase'];				

				$dbcnx=mysql_connect($host,$user_db,$pass_db);
				
				if (!$dbcnx) 
				{
				  echo("<p> error MySql </p>");
				   exit;
				}
																
				if (!@mysql_select_db($dbase,$dbcnx)) 
				{
				   echo("<p> error DB MySql </p>");	
				   exit;
				}
				
							
				//�� ������ ����� ���� �������� 
				$url=$_SERVER['REQUEST_URI'];

				//$url='http://phpshop.dev/shop/CID_102.html';
				$s1=strpos($url,"CID_")+4;				
				
				$s3=strpos($url,"_",$s1);				//2 �������� ++
			
				if ($s3!==false)
					$s2=$s3;
				else
					$s2=strpos($url,".html");
				
				$len=$s2-$s1;			
				
				$id=substr($url,$s1,$len);
				
				$usl=" category='".$id."' ";

				$us_ = $id;
				

$tools = array();		
$sql0_2="SELECT * FROM ".$SysValue['base']['products']." WHERE ".$usl." and vendor!='' and enabled='1'";

$res0_2=mysql_query($sql0_2);	

while($row0_2=mysql_fetch_assoc($res0_2))
{			
	foreach(unserialize($row0_2['vendor_array']) as $key => $row){
		$sql_p = "SELECT * FROM ".$SysValue['base']['sort_categories']." psc
		WHERE psc.id =  '" . $key . "' AND psc.filtr = '1'";
		$rest = mysql_query($sql_p);	
		$row_t = mysql_fetch_row($rest);
		
		$sql_p_1 = "SELECT * FROM ".$SysValue['base']['categories']." pc
		WHERE pc.id =  '" . $id . "'";
		$rest_1 = mysql_query($sql_p_1);	
		$row_t_1 = mysql_fetch_row($rest_1);
		/*
		$dos = false;
		foreach(unserialize($row_t_1[27]) as $row_1){
			if($row_1 == $key){
				$dos = true;
			}
		}
		*/
		if ($id==135) {
				$myarray=array();
				$myarray=get_filter($id,'minimoika');
				$tools=$myarray;
		} else if ($id==144 || $id==148 || $id==149 || $id==150 || $id==151 || $id==152 || $id==153 ) {
			$myarray=array();
			$myarray=get_filter($id,'motobur');			
			$tools=$myarray;
		} else if ($id==173) {
			$myarray=array();
			$myarray=get_filter($id,'vibroplita');
			$tools=$myarray;
		} else if ($id==88) {
			$myarray=array();
			$myarray=get_filter($id,'vozduhoduv');
			$tools=$myarray;
		} else if ($id==126 || $id==127 || $id==128 || $id==129 || $id==130 || $id==131 || $id==132 || $id==137 || $id==139 || $id==140 || $id==180|| $id==183) {
			$myarray=array();
			$myarray=get_filter($id,'snegouborchik');
			$tools=$myarray;
		} else if ($id==96 || $id==97 || $id==98 || $id==99 || $id==121 || $id==122 || $id==123 || $id==143) {
			$myarray=array();
			$myarray=get_filter($id,'benzopila');
			$tools=$myarray;
		} else if ($id==112 || $id==113 || $id==114 || $id==115 || $id==116 || $id==117 || $id==124 || $id==138 || $id==170 || $id==178 || $id==185) {
			$myarray=array();
			$myarray=get_filter($id,'trimmer');
			$tools=$myarray;
		} else if ($id==100 || $id==101 || $id==102 || $id==103 || $id==104 || $id==105 || $id==106 || $id==108 || $id==109 || $id==110 || $id==111 || $id==168 || $id==171 || $id==179 || $id==193 || $id==194) {
			$myarray=array();
			$myarray=get_filter($id,'gazonokosilka');
			$tools=$myarray;
		} else if ($id==190 || $id==192 || $id==186) {
			$myarray=array();
			$myarray=get_filter($id,'benzogenerator');
			$tools=$myarray;
		} else if ($id==67 || $id==94) {
			$myarray=array();
			$myarray=get_filter($id,'parts_zubrem');
			$tools=$myarray;
		} else if ($id==56) {
			$myarray=array();
			$myarray=get_filter($id,'parts_kolesa');
			$tools=$myarray;
		} else if ($id==90) {
			$myarray=array();
			$myarray=get_filter($id,'parts_trosgaz');
			$tools=$myarray;
		} else if ($id==61) {
			$myarray=array();
			$myarray=get_filter($id,'parts_reduktor');
			$tools=$myarray;
		} else if ($id==91) {
			$myarray=array();
			$myarray=get_filter($id,'parts_prochee');
			$tools=$myarray;
		} else if ($id==49 || $id==50 || $id==51 || $id==52 || $id==53) {
			$myarray=array();
			$myarray=get_filter($id,'parts_dvigatel');
			$tools=$myarray;
		} else if ($id==74) {
			$myarray=array();
			$myarray=get_filter($id,'parts_navesnoe');
			$tools=$myarray;
		} else if ($id==20) {
			$myarray=array();
			$myarray=get_filter($id,'parts_szepka');
			$tools=$myarray;
		} else if ($id==17) {
			$myarray=array();
			$myarray=get_filter($id,'parts_okuchnik');
			$tools=$myarray;
		} else if ($id==22) {
			$myarray=array();
			$myarray=get_filter($id,'parts_kartofelecop');
			$tools=$myarray;
		} else if ($id==30) {
			$myarray=array();
			$myarray=get_filter($id,'parts_senokos');
			$tools=$myarray;
		} else if ($id==28) {
			$myarray=array();
			$myarray=get_filter($id,'parts_telegka_adapter');
			$tools=$myarray;
		} else if ($id==25) {
			$myarray=array();
			$myarray=get_filter($id,'parts_snegootval');
			$tools=$myarray;
		} else if ($id==24) {
			$myarray=array();
			$myarray=get_filter($id,'parts_schetka');
			$tools=$myarray;
		} else if ($id==19) {
			$myarray=array();
			$myarray=get_filter($id,'parts_schetka');
			$tools=$myarray;
		} else if ($id==15) {
			$myarray=array();
			$myarray=get_filter($id,'parts_plug');
			$tools=$myarray;
		} else if ($id==21) {
			$myarray=array();
			$myarray=get_filter($id,'parts_gruntozacep');
			$tools=$myarray;
		} else if ($id==23) {
			$myarray=array();
			$myarray=get_filter($id,'parts_pololnik');
			$tools=$myarray;
		} else if ($id==27) {
			$myarray=array();
			$myarray=get_filter($id,'parts_telezka');
			$tools=$myarray;
		} else if ($id==29) {
			$myarray=array();
			$myarray=get_filter($id,'parts_nasos');
			$tools=$myarray;
		} else if ($id==26) {
			$myarray=array();
			$myarray=get_filter($id,'parts_lopata');
			$tools=$myarray;
		} else if ($id==47 || $id==195) {
			$myarray=array();
			$myarray=get_filter($id,'electrocultivatory');
			$tools=$myarray;
		} else if ($id==45 || $id==46 || $id==71 || $id==83 || $id==84 || $id==207 || $id==240 || $id==205 || $id==210) {
			$myarray=array();
			$myarray=get_filter($id,'motokultivator');
			$tools=$myarray;
		} else if ($id==38 || $id==39 || $id==40 || $id==41 || $id==42 || $id==62 || $id==63 || $id==64 || $id==65 || $id==120 || $id==156 || $id==174 || $id==175 || $id==191 || $id==181) {
			$myarray=array();
			$myarray=get_filter($id,'motoblok');
			$tools=$myarray;
		} else if ($id==31) {
			$myarray=array();
			$myarray=get_filter($id,'motopompa');
			$tools=$myarray;
		} else if ($id==184) {
			$myarray=array();
			$myarray=get_filter($id,'electrosnsegouborchik');
			$tools=$myarray;
		}
	}
}

$option ='<div class="filter_" id="filter_">';
//���-�� ������������� ��������� � ����� ������������
$step = 0;
//���������� �������� ������ ��������������
$har_mainarr_cnt=0;

$filterconfs=array();
$filterconfs=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-items_catalog_show-hide-choice-merge-equal.txt');


foreach( $tools as $tool ){

	$ids_2=array();
	$tovars_2=array();
	$i_2=0;
	$names_2 = array();
	$sql0_2="SELECT * FROM ".$SysValue['base']['products']." WHERE ".$usl." and vendor!='' and enabled='1'";
	//echo $sql0_2;
	$res0_2=mysql_query($sql0_2);
	$n_2=mysql_num_rows($res0_2);
	while($row0_2=mysql_fetch_assoc($res0_2))
	{				
		
		$id_tovara_2=$row0_2['id'];

		//� ������ � ��� ������� ���-� ������� ��� ����������,  ��� �������� �� ������� ����������
		$vendor_2=$row0_2['vendor'];
		$products=explode("i",$vendor_2);
		$id0_2=array();
		$cnt=0;
		foreach ( $products as $product ) {
			if (!empty($product)) {
				//echo $product.'<br />';
				$product_id_start_pos=0;
				$product_id_end_pos=strpos($product,'-');
				$product_id=substr($product,$product_id_start_pos,$product_id_end_pos);
				if ( $product_id==$tool['id'] ) {
					//echo $product_id.' '.$tool['id'].'<br />';
					$id0_2[$cnt]=substr($product,($product_id_end_pos+1),(strlen($product)-($product_id_end_pos+1)));
					//echo $id0_2[$cnt].'<br />';
					$cnt++;
				}
			}
		}
		//print_r($id0_2);
		$product_id='';
		foreach($id0_2 as $id0_2_item) {
			$product_id.=$id0_2_item.',';
		}
		//echo $product_id.'---';
		$product_id=substr($product_id,0,(strlen($product_id)-1));
		//echo $product_id1;
		//�������� �� phpshop_sort �������� �� �������������
		$sql1_2="SELECT * FROM ".$SysValue['base']['sort']." WHERE id in (".$product_id.") AND category='".$tool['id']."' ";
		//echo $sql1_2;
		$res1_2=mysql_query($sql1_2);
		$row1_2=mysql_fetch_assoc($res1_2);

		// ���������� ���� ���� ������� � ������ ���-���
		foreach($id0_2 as $id0_2_item) {
			$tovars_2[$id0_2_item]="tool_".$id_tovara_2.";";
			//echo $tovars_2[$id0_2_item].'<br />';
		}

		$cnt=0;
		
		//���������� ������ �� ����� ���������� �� ��������� �������������� ���� ���� �� ���������		
		foreach($id0_2 as $id0_2_item) {
				
				do {
					//echo $row1_2['name'].'-';
					$item_existing=check_array_item($row1_2['name'],$names_2);
					//echo $item_existing.'<br />';
					if (!$item_existing) {
						$names_2[$cnt][$row1_2['id']]=float($row1_2['name']);
						//echo $names_2[$cnt][$row1_2['id']].'<br />';
						$cnt++;
					}

				} while ( $row1_2=mysql_fetch_assoc($res1_2) );

		}
	}
	$har_mainarr_cnt=count($names_2);
	foreach ($names_2 as $name )
	{
		$har_arr_cnt=count($name);
	}	

	//foreach ($filterconfs as $filterconf) {
		//echo '--'.$filterconf['id'].' $cnt='.$har_arr_cnt.' $maincnt='.$har_mainarr_cnt.'--<br />';	
	//}

	//echo 'changeVonLoad('.$filterconf['id'].','.$step.');';
	//var_dump ($filterconfs["2"]);
	//echo intval(is_array($filterconfs['2']));
	if (is_array($filterconfs['2'])) {
		if ( $filterconfs['2']['id']==1 || ($filterconfs['2']['id']==2 && ($har_mainarr_cnt==0 || ($har_mainarr_cnt==1 && $har_arr_cnt==1))) ){
			$option_part='<div class="fmenu"><a href="javascript: void(0);" onclick="changeV('.$step.')" class="visible_all" id="visible_all'.$step.'">����������/������ ������: ' . $tool['name'] . '</a></div>';
			//<script type="text/javascript">
			//changeVonLoad('.$filterconfs['2']['id'].','.$step.');
			//</script>';
		} else {
			$option_part='';
		}
	}
	
	$option .='
	<div class="block_'.$step.'">'
	.$option_part.'
	<div class="colorcheck" id="id_' . $step . '">
	<div class="col-wrap">
	<div class="fname">' . $tool['name'] . ':</div>
	<div class="fmenu"><a href="javascript: void(0);" onclick="changeT('.$step.')" id="check_all'.$step.'">�������� ���</a> <a href="javascript: void(0);" onclick="changeF('.$step.')" id="uncheck_all'.$step.'">����� ���</a></div>
	</div>
	';
		
	//print_r($names_2);
	//echo '<br />';
	natcasesort($names_2);
	//print_r($names_2);
	//sort($names_2,SORT_STRING);
	//print_r($names_2);
	
	foreach ($names_2 as $name )
	{
		natcasesort($name);
		foreach ($name as $key_2 =>$value_2) {
			if ($value_2!=""){ //$value_2="�� �����";
				//echo $tool['id'].'<br />';
				//echo 'key2='.$key_2.' value2='.$value_2.'<br />';
				$option.='<div class="check" name="check_id_' . $key . '"><label><input onclick="showM();" value="i'.$tool['id']."-".$key_2.'i" type="checkbox" class="checkbox" name="colorcheck['.$tool['id'].']['.$key_2.']" classtoadd="'.$tovars_2[$key_2].'" checked="checked"> '.$value_2.' </label></div>';
			}
		}
	}

	$option.="</div><hr/></div>";
	$step++;
}
$option.='<input type="hidden" name="category_" value="'.$us_.'"/></div>';
	echo $option;
//������� �������� �� ��������� id �������� ���������� �����. ��������� ���� �� ������������ ������ ��� ����� id 
function get_filter($filter_category,$filter_name){
	switch ($filter_category) {
	    case 135:
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-minimoika_catalog_show-hide-check.txt');
		break;
		case ($filter_category==144 || $filter_category==148 || $filter_category==149 || $filter_category==150 || $filter_category==151 || $filter_category==152 || $filter_category==153):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-motobur_catalog_show-hide-check.txt');
		break;
		case 173:
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-vibroplita_catalog_show-hide-check.txt');
		break;		
		case 88:
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-vozduhoduv_catalog_show-hide-check.txt');
		break;	
		case ($filter_category==126 || $filter_category==127 || $filter_category==128 || $filter_category==129 || $filter_category==130 || $filter_category==137 || $filter_category==131 || $filter_category==132 || $filter_category==139 || $filter_category==140 || $filter_category==180 || $filter_category==183):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-snegouborchik_catalog_show-hide-check.txt');
		break;
		case ($filter_category==96 || $filter_category==97 || $filter_category==98 || $filter_category==99 || $filter_category==121 || $filter_category==122 || $filter_category==123 || $filter_category==143):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-benzopila_catalog_show-hide-check.txt');
		break;		
		case ($filter_category==112 || $filter_category==113 || $filter_category==114 || $filter_category==115 || $filter_category==116 || $filter_category==117 || $filter_category==124 || $filter_category==138 || $filter_category==170 || $filter_category==178 || $filter_category==185):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-trimmer_catalog_show-hide-check.txt');
		break;	
		case ($filter_category==100 || $filter_category==101 || $filter_category==102 || $filter_category==103 || $filter_category==104 || $filter_category==105 || $filter_category==106 || $filter_category==108 || $filter_category==109 || $filter_category==110 || $filter_category==111 || $filter_category==168 || $filter_category==171 || $filter_category==179 || $filter_category==193 || $filter_category==194):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-gazonokosilka_catalog_show-hide-check.txt');
		break;
		case ($filter_category==190 || $filter_category==192 || $filter_category==186):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-benzogenerator_catalog_show-hide-check.txt');
		break;		
		case ($filter_category==67 ||$filter_category==94):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-zubrem_catalog_show-hide-check.txt');
		break;		
		case ($filter_category==56):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_kolesa_catalog_show-hide-check.txt');
		break;
		case ($filter_category==90):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_trosgaz_catalog_show-hide-check.txt');
		break;
		case ($filter_category==61):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_reduktor_catalog_show-hide-check.txt');
		break;
		case ($filter_category==91):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_prochee_catalog_show-hide-check.txt');
		break;
		case ($filter_category==49 || $filter_category==50 || $filter_category==51 || $filter_category==52 || $filter_category==53):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_dvigatel_catalog_show-hide-check.txt');
		break;
		case ($filter_category==74):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_navesnoe_catalog_show-hide-check.txt');
		break;
		case ($filter_category==20):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_szepka_catalog_show-hide-check.txt');
		break;
		case ($filter_category==17):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_okuchnik_catalog_show-hide-check.txt');
		break;
		case ($filter_category==22):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_kartofelecop_catalog_show-hide-check.txt');
		break;
		case ($filter_category==30):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_senokos_catalog_show-hide-check.txt');
		break;
		case ($filter_category==28):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_telegka_adapter_catalog_show-hide-check.txt');
		break;
		case ($filter_category==25):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_snegootval_catalog_show-hide-check.txt');
		break;
		case ($filter_category==24):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_schetka_catalog_show-hide-check.txt');
		break;
		case ($filter_category==19):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_freza_catalog_show-hide-check.txt');
		break;
		case ($filter_category==15):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_plug_catalog_show-hide-check.txt');
		break;
		case ($filter_category==21):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_gruntozacep_catalog_show-hide-check.txt');
		break;
		case ($filter_category==23):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_pololnik_catalog_show-hide-check.txt');
		break;
		case ($filter_category==27):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_telezka_catalog_show-hide-check.txt');
		break;
		case ($filter_category==29):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_nasos_catalog_show-hide-check.txt');
		break;
		case ($filter_category==26):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-parts_lopata_catalog_show-hide-check.txt');
		break;
		case ($filter_category==47 || $filter_category==195):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-electrokultivatory_catalog_show-hide-check.txt');
		break;
		case ($filter_category==31):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-motopompa_catalog_show-hide-check.txt');
		break;
		case ($filter_category==184):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-electrosnsegouborchik_catalog_show-hide-check.txt');
		break;		
		case ($filter_category==45 || $filter_category==46 || $filter_category==71 || $filter_category==83 || $filter_category==84 || $filter_category==207 || $filter_category==240 || $filter_category==205 || $filter_category==210):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-motokultivator_catalog_show-hide-check.txt');
		break;
		case ($filter_category==38 || $filter_category==39 || $filter_category==40 || $filter_category==41 || $filter_category==42 || $filter_category==62 || $filter_category==63 || $filter_category==64 || $filter_category==65 || $filter_category==120 || $filter_category==156 || $filter_category==174 || $filter_category==175 || $filter_category==191 || $filter_category==181):
			$filter_array=read_filter_info($_SERVER['DOCUMENT_ROOT'] . '/custom_config/filter-items-motoblok_catalog_show-hide-check.txt');
		break;		
	}
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}
}

function check_array_item($string,$array) {
	$rv=false;
	foreach ($array as $item1){
		foreach($item1 as $item2) {
			//echo $string.'-'.$item2.'-'.float($string).'-'.float($item2).'-'.$rv.'<br />';
			if (float($item2)==float($string)){
				$rv=true;
				//echo $string.'-'.$item2.'-'.float($string).'-'.float($item2).'-'.$rv.'<br />';				
			}
		}
	}
	return $rv;
}

function float($str, $set=FALSE)
{	//���� ������ ���������
	if (preg_match("/\s/", $str, $match) || preg_match("/^[0-9]*[\+]{1}[0-9]+$/", $str, $match)) {
		//echo '+++++';
		return $str;
	} elseif(preg_match("/([0-9\.,-]+)/", $str, $match)) {

		// Found number in $str, so set $str that number
		$str = $match[0];

		if(strstr($str, ',')) {
			// A comma exists, that makes it easy, cos we assume it separates the decimal part.
			$str = str_replace('.', '', $str);    // Erase thousand seps
			$str = str_replace(',', '.', $str);    // Convert , to . for floatval command

			return floatval($str);
		} else {
			// No comma exists, so we have to decide, how a single dot shall be treated
			if(preg_match("/^[0-9]*[\.]{1}[0-9-]+$/", $str) == TRUE && $set['single_dot_as_decimal'] == TRUE) {		
				// Treat single dot as decimal separator
				return floatval($str);
			} else {	
				// Else, treat all dots as thousand seps
				$str = str_replace('.', '', $str);    // Erase thousand seps
				return floatval($str);
			}
		}
	} else	{
		// No number found, return zero
		return 0;
	}
}
?>