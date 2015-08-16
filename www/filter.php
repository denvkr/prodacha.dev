<?
/*
include_once('phpshop/class/obj.class.php');
PHPShopObj::loadClass('base');
PHPShopObj::loadClass('system');
$PHPShopBase = new PHPShopBase("phpshop/inc/config.ini",false);
*/
/*
$host="localhost";          
$user_db="root";           
$pass_db="";       
$dbase="prodacha";
*/
/*
				$host="localhost";          
				$user_db="u301639_test";           
				$pass_db="-o97iCiAlLop.";       
				$dbase="u301639_test"; 
*/
				//$host=$GLOBALS['SysValue']['connect']['host'];          
				//$user_db=$GLOBALS['SysValue']['connect']['user_db'];           
				//$pass_db=$GLOBALS['SysValue']['connect']['pass_db'];       
				//$dbase=$GLOBALS['SysValue']['connect']['dbase'];
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
$s_1 = '';
if(isset($_POST['colorcheck'])){
	//$usl = '';
	//foreach(explode("_",$_GET['category']) as $value){
		//$usl.=" OR category='".$value."' ";
	//}
	
	foreach($_POST['colorcheck'] as $key_1 => $value_1){
		$s_2 = '';
		foreach($value_1 as $key_2 => $value_2){
			if($s_2){
				$s_2 .= " OR vendor LIKE '%$value_2%'";
			}else{
				$s_2 .= " vendor LIKE '%$value_2%'";
			}
		}
		$s_1 .= " AND ( $s_2 ) ";
	}
	
	$sql = "SELECT id FROM phpshop_products WHERE 1=1 ".$s_1." and vendor!='' and enabled='1'";
	//var_dump($sql);
	$res=mysql_query($sql);
	while($row = mysql_fetch_assoc($res)){
		$arr['mas'][] = array(
			'id' =>'tool_'.$row['id']
		);
	}

	echo json_encode($arr);
}


/*
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

				
					
				//+ смотрим для варианта, когда у него есть КАК БЭ дети - на самом деле это братья. Обычно это раздел ВСЕ товары из разных категорий 

				$all=array('5','13','37','17',$id);
				
				if (in_array($id,$all))
				{
					$sql02="SELECT * FROM phpshop_categories WHERE id='".$id."' ";
					$res02=mysql_query($sql02);
				
					if (mysql_num_rows($res02))
					{
						$row02=mysql_fetch_assoc($res02);
					
						$sql2="SELECT * FROM phpshop_categories WHERE parent_to='".$row02['parent_to']."' ";
						$res2=mysql_query($sql2);				
						while($row2=mysql_fetch_assoc($res2))
							{
								$usl.=" OR category='".$row2['id']."' ";
							}
					}				
				}
		

$tools = array();		
$sql0_2="SELECT * FROM phpshop_products WHERE ".$usl." ";
$res0_2=mysql_query($sql0_2);	

while($row0_2=mysql_fetch_assoc($res0_2))
{			

	foreach(unserialize($row0_2['vendor_array']) as $key => $row){
		

		$sql_p = "SELECT * FROM  `phpshop_sort_categories` WHERE id =  '" . $key . "'";
		$rest = mysql_query($sql_p);	
		$row_t = mysql_fetch_row($rest);

		$tools[$key] = array('id'=>$key, 'name'=>$row_t[1]);
		
	}
}


$option ='<div class="filter_" id="filter_">';
$step = 0;
foreach($tools as $tool){
	
	$option .='
	<div class="block_'.$step.'">
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
		$id0_2=substr($vendor_2,$s01_2,$len0_2); //айди харки продукта из таблицы phpshop_sort
		
		$sql1_2="SELECT * FROM phpshop_sort WHERE id='".$id0_2."' AND category='".$tool['id']."' ";					
		
		$res1_2=mysql_query($sql1_2);
		$row1_2=mysql_fetch_assoc($res1_2);
		
		$tovars_2[$id0_2].="tool_".$id_tovara_2.";"; // записываем айди всех товаров с данныой харкой										
		
		if (!in_array($id0_2,$ids_2))	//убираем одинаковые
		{
			$ids_2[]=$id0_2;				
			$names_2[$id0_2]=$row1_2['name'];
		}		
		
	}						
	//echo '<pre>';
	//var_dump($names_2);
	//echo '---------------------</pre>';
	foreach ($names_2 as $key_2 =>$value_2 )
	{
		if ($value_2!=""){ //$value_2="Не задан";
		$option.='<div class="check"><label><input type="checkbox" class="checkbox" name="colorcheck" classtoadd="'.$tovars_2[$key_2].'" checked=""> '.$value_2.' </label></div>';}
	}

	$option.="</div><hr/></div>";
	$step++;
}
$option.="</div>";
echo $option;*/
				
				
?>				