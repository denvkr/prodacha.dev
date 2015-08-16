<?

				/*$host="localhost";          
				$user_db="root";           
				$pass_db="";       
				$dbase="prodacha";
				$host="u301639.mysql.masterhost.ru";          
				$user_db="u301639_second";           
				$pass_db="peREneSti-AB6E";       
				$dbase="u301639";
				*/
				$host=$GLOBALS['SysValue']['connect']['host'];          
				$user_db=$GLOBALS['SysValue']['connect']['user_db'];           
				$pass_db=$GLOBALS['SysValue']['connect']['pass_db'];       
				$dbase=$GLOBALS['SysValue']['connect']['dbase']; 

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
				
							
				//из строки берем айди каталога 
				$url=$_SERVER['REQUEST_URI'];

				$s1=strpos($url,"CID_")+4;				
				
				$s3=strpos($url,"_",$s1);				//2 страница ++
			
				if ($s3!==false)
					$s2=$s3;
				else
					$s2=strpos($url,".html");
				
				$len=$s2-$s1;			
				
				$id=substr($url,$s1,$len);
				
				$usl=" category='".$id."' ";
				//
				$us_ = $id;
				
				/*
				//+ смотрим для варианта, когда у него есть КАК БЭ дети - на самом деле это братья. Обычно это раздел ВСЕ товары из разных категорий 

				$all=array('5','13','37','17',$id);
				
				if (in_array($id,$all))
				{
					$sql02="SELECT * FROM phpshop_categories WHERE id='".$id."'";
					$res02=mysql_query($sql02);
				
					if (mysql_num_rows($res02))
					{
						$row02=mysql_fetch_assoc($res02);
					
						$sql2="SELECT * FROM phpshop_categories WHERE parent_to='".$row02['parent_to']."'";
						$res2=mysql_query($sql2);				
						while($row2=mysql_fetch_assoc($res2))
							{
								$usl.=" OR category='".$row2['id']."' ";
								$us_ .=  "_".$row2['id'];
							}
					}				
				}*/
				//$usl.=" OR category='". $id ."' ";

$tools = array();		
$sql0_2="SELECT * FROM phpshop_products WHERE ".$usl." ";
//echo $sql0_2.'<br />';
$res0_2=mysql_query($sql0_2);	

while($row0_2=mysql_fetch_assoc($res0_2))
{			
	//echo $row0_2['name'].' '.$row0_2['category'].'<br />';
	foreach(unserialize($row0_2['vendor_array']) as $key => $row){
		//echo $id.' '.$key.' '.$row.'<br />';
		$sql_p = "SELECT * FROM `phpshop_sort_categories` psc
		WHERE psc.id =  '" . $key . "' AND psc.filtr = '1'";
		$rest = mysql_query($sql_p);	
		$row_t = mysql_fetch_row($rest);
		
		$sql_p_1 = "SELECT * FROM `phpshop_categories` pc
		WHERE pc.id =  '" . $id . "'";
		$rest_1 = mysql_query($sql_p_1);	
		$row_t_1 = mysql_fetch_row($rest_1);
		
		$dos = false;
		foreach(unserialize($row_t_1[27]) as $row_1){
			//echo $row_1.' '.$key.' '.$row[0].' '.$row_t[1].'<br />';
			if($row_1 == $key){
				$dos = true;
			}
		}
		if ($id==135) {
		//if($row_t[1] && $dos){
			//if ($key==42) {
				$myarray=array();
				$myarray=get_filter($id,'minimoika');
				$tools=$myarray;
				//$filter_array=array('id'=>191,'name'=>'Производительность, л/ч');
				//$tools[$key] = array('id'=>$key, 'name'=>$row_t[1]);
				//$tools['191']=$filter_array;
				//$myarray=get_filter($key);
				//print_r($tools);
			//} else {
			//	$tools[$key] = array('id'=>$key, 'name'=>$row_t[1]);
			//}
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
		} else if ($id==47) {
			$myarray=array();
			$myarray=get_filter($id,'electrocultivatory');
			$tools=$myarray;
		} else if ($id==45 || $id==46 || $id==71 || $id==83 || $id==84 || $id==195) {
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
$step = 0;

$option_part='';
$filterconf=array();
$filterconf=read_filter_config('filtr_config.txt');
	
foreach($tools as $tool){

	if ($filterconf[0]!=0) {
		foreach ( $filterconf as $key => $value ) {
		   //echo $key.' '.$value['id'];
		  } 
		if ($value['id']=='1'){
		$option_part='<a href="javascript: void(0);" onclick="changeV('.$step.')" id="visible_all'.$step.'">Скрыть/развернуть фильтр: ' . $tool['name'] . '</a>
		<script type="text/javascript">
		changeVonLoad('.$step.');
		</script>';
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
		<div class="fmenu"><a href="javascript: void(0);" onclick="changeT('.$step.')" id="check_all'.$step.'">Отметить все</a> <a href="javascript: void(0);" onclick="changeF('.$step.')" id="uncheck_all'.$step.'">Снять все</a></div>
	</div>
	';

	$ids_2=array();
	$tovars_2=array();
	$i_2=0;
	$names_2 = array();
	$sql0_2="SELECT * FROM phpshop_products WHERE ".$usl."";
	$res0_2=mysql_query($sql0_2);
	$n_2=mysql_num_rows($res0_2);
	while($row0_2=mysql_fetch_assoc($res0_2))
	{				
		
		$id_tovara_2=$row0_2['id'];

		//у товара в его массиве хар-к находим тот показатель,  что отвечает за предмет сортировки
		$vendor_2=$row0_2['vendor'];
		
		$reg_2="i".$tool['id']."-";//ищем по этому коду наш параметр
		
		$s01_2=strpos($vendor_2,$reg_2)+strlen($reg_2);										
		$s02_2=strpos($vendor_2,"i",$s01_2); //конец искомого значения
		
		$len0_2=$s02_2-$s01_2;				
		$id0_2=substr($vendor_2,$s01_2,$len0_2); //айди хар-ки продукта из таблицы phpshop_sort
		
		$sql1_2="SELECT * FROM phpshop_sort WHERE id='".$id0_2."' AND category='".$tool['id']."' ";					
		
		$res1_2=mysql_query($sql1_2);
		$row1_2=mysql_fetch_assoc($res1_2);
		
		$tovars_2[$id0_2].="tool_".$id_tovara_2.";"; // записываем айди всех товаров с данной хар-кой										
		
		if (!in_array($id0_2,$ids_2))	//убираем одинаковые
		{
			$ids_2[]=$id0_2;				
			$names_2[$id0_2]=$row1_2['name'];
		}		
	}
	natcasesort($names_2);		
	
	//echo '<pre>';
	//var_dump($names_2);
	//echo '---------------------</pre>';
	foreach ($names_2 as $key_2 =>$value_2 )
	{
		if ($value_2!=""){ //$value_2="Не задан";
		$option.='<div class="check" name="check_id_' . $key . '"><label><input onclick="showM()" value="i'.$tool['id']."-".$key_2.'i" type="checkbox" class="checkbox" name="colorcheck['.$tool['id'].']['.$key_2.']" classtoadd="'.$tovars_2[$key_2].'" checked="checked"> '.$value_2.' </label></div>';}
	}

	$option.="</div><hr/></div>";
	$step++;
}
$option.="<input type=\"hidden\" name=\"category_\" value=\"".$us_."\"/></div>";
//if($tools){
	echo $option;
//}else{
//	echo '';
//}
//функция выбирает по заданному id значение текстового файла. проверяет надо ли активировать фильтр для этого id 
function get_filter($filter_category,$filter_name){
	switch ($filter_category) {
	    case 135:
			$filter_array=read_filter_info('filtr_active_minimoika.txt');
		break;
		case ($filter_category==144 || $filter_category==148 || $filter_category==149 || $filter_category==150 || $filter_category==151 || $filter_category==152 || $filter_category==153):
			$filter_array=read_filter_info('filtr_active_motobur.txt');
		break;
		case 173:
			$filter_array=read_filter_info('filtr_active_vibroplita.txt');
		break;		
		case 88:
			$filter_array=read_filter_info('filtr_active_vozduhoduv.txt');
		break;	
		case ($filter_category==126 || $filter_category==127 || $filter_category==128 || $filter_category==129 || $filter_category==130 || $filter_category==137 || $filter_category==131 || $filter_category==132 || $filter_category==139 || $filter_category==140 || $filter_category==180 || $filter_category==183):
			$filter_array=read_filter_info('filtr_active_snegouborchik.txt');
		break;
		case ($filter_category==96 || $filter_category==97 || $filter_category==98 || $filter_category==99 || $filter_category==121 || $filter_category==122 || $filter_category==123 || $filter_category==143):
			$filter_array=read_filter_info('filtr_active_benzopila.txt');
		break;		
		case ($filter_category==112 || $filter_category==113 || $filter_category==114 || $filter_category==115 || $filter_category==116 || $filter_category==117 || $filter_category==124 || $filter_category==138 || $filter_category==170 || $filter_category==178 || $filter_category==185):
			$filter_array=read_filter_info('filtr_active_trimmer.txt');
		break;	
		case ($filter_category==100 || $filter_category==101 || $filter_category==102 || $filter_category==103 || $filter_category==104 || $filter_category==105 || $filter_category==106 || $filter_category==108 || $filter_category==109 || $filter_category==110 || $filter_category==111 || $filter_category==168 || $filter_category==171 || $filter_category==179 || $filter_category==193 || $filter_category==194):
			$filter_array=read_filter_info('filtr_active_gazonokosilka.txt');
		break;
		case ($filter_category==190 || $filter_category==192 || $filter_category==186):
			$filter_array=read_filter_info('filtr_active_benzogenerator.txt');
		break;		
		case ($filter_category==67 ||$filter_category==94):
			$filter_array=read_filter_info('filtr_active_parts_zubrem.txt');
		break;		
		case ($filter_category==56):
			$filter_array=read_filter_info('filtr_active_parts_kolesa.txt');
		break;
		case ($filter_category==90):
			$filter_array=read_filter_info('filtr_active_parts_trosgaz.txt');
		break;
		case ($filter_category==61):
			$filter_array=read_filter_info('filtr_active_parts_reduktor.txt');
		break;
		case ($filter_category==91):
			$filter_array=read_filter_info('filtr_active_parts_prochee.txt');
		break;
		case ($filter_category==49 || $filter_category==50 || $filter_category==51 || $filter_category==52 || $filter_category==53):
			$filter_array=read_filter_info('filtr_active_parts_dvigatel.txt');
		break;
		case ($filter_category==74):
			$filter_array=read_filter_info('filtr_active_parts_navesnoe.txt');
		break;
		case ($filter_category==20):
			$filter_array=read_filter_info('filtr_active_parts_szepka.txt');
		break;
		case ($filter_category==17):
			$filter_array=read_filter_info('filtr_active_parts_okuchnik.txt');
		break;
		case ($filter_category==22):
			$filter_array=read_filter_info('filtr_active_parts_kartofelecop.txt');
		break;
		case ($filter_category==30):
			$filter_array=read_filter_info('filtr_active_parts_senokos.txt');
		break;
		case ($filter_category==28):
			$filter_array=read_filter_info('filtr_active_parts_telegka_adapter.txt');
		break;
		case ($filter_category==25):
			$filter_array=read_filter_info('filtr_active_parts_snegootval.txt');
		break;
		case ($filter_category==24):
			$filter_array=read_filter_info('filtr_active_parts_schetka.txt');
		break;
		case ($filter_category==19):
			$filter_array=read_filter_info('filtr_active_parts_freza.txt');
		break;
		case ($filter_category==15):
			$filter_array=read_filter_info('filtr_active_parts_plug.txt');
		break;
		case ($filter_category==21):
			$filter_array=read_filter_info('filtr_active_parts_gruntozacep.txt');
		break;
		case ($filter_category==23):
			$filter_array=read_filter_info('filtr_active_parts_pololnik.txt');
		break;
		case ($filter_category==27):
			$filter_array=read_filter_info('filtr_active_parts_telezka.txt');
		break;
		case ($filter_category==29):
			$filter_array=read_filter_info('filtr_active_parts_nasos.txt');
		break;
		case ($filter_category==26):
			$filter_array=read_filter_info('filtr_active_parts_lopata.txt');
		break;
		case ($filter_category==47):
			$filter_array=read_filter_info('filtr_active_electrokultivatory.txt');
		break;
		case ($filter_category==31):
			$filter_array=read_filter_info('filtr_active_motopompa.txt');
		break;
		case ($filter_category==184):
			$filter_array=read_filter_info('filtr_active_electrosnsegouborchik.txt');
		break;		
		case ($filter_category==45 || $filter_category==46 || $filter_category==71 || $filter_category==83 || $filter_category==84 || $filter_category==195):
			$filter_array=read_filter_info('filtr_active_motokultivator.txt');
		break;
		case ($filter_category==38 || $filter_category==39 || $filter_category==40 || $filter_category==41 || $filter_category==42 || $filter_category==62 || $filter_category==63 || $filter_category==64 || $filter_category==65 || $filter_category==120 || $filter_category==156 || $filter_category==174 || $filter_category==175 || $filter_category==191 || $filter_category==181):
			$filter_array=read_filter_info('filtr_active_motoblok.txt');
		break;		
	}
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}
}
function read_filter_info($filename){
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			if ($fitler_element[2]=='"1"') {
				$my_id=str_replace('"','',$fitler_element[0]);
				$my_name=str_replace('"','',$fitler_element[1]);
				$filter_array[$fitler_element[0]]=array('id'=>$my_id,'name'=>$my_name);
			}	
		}
		fclose($filter_file);
	}
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}			
}
function read_filter_config($filename){
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			if ($fitler_element[2]=='"1"') {
				$my_id=str_replace('"','',$fitler_element[0]);
				$my_name=str_replace('"','',$fitler_element[1]);
				$filter_array[$fitler_element[0]]=array('id'=>$my_id,'name'=>$my_name);
			}	
		}
		fclose($filter_file);
	}
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return array(0);
	}			
}

function is_array_empty($a) {
	foreach($a as $elm) {
		if (!empty($elm)) {
			return false;
		} else {
			return true;
		}
	}
}
?>				
