<?
/**
 * серверная часть ajax кнопки "уточнить"
 * @author denvkr
 * @tutorial https://trello.com/c/dy3WB3Rz/49--
 * @version 1.0
 */
	$SysValue = parse_ini_file('../phpshop/inc/config.ini', 1);
	$item1=$SysValue['lang']['ask_product_availability_item1'];
	$item2=$SysValue['lang']['fast_order_window_item2'];
	$item3=$SysValue['lang']['fast_order_window_item3'];
	$item4=$SysValue['lang']['fast_order_window_item4'];
	$item5=$SysValue['lang']['fast_order_window_item5'];
	$item6=$SysValue['lang']['fast_order_window_item5'];
	$item7=$SysValue['lang']['ask_product_availability_item2'];
	
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
	
		
	//из строки берем айди каталога
	$url=implode($_POST);
	//$url='http://phpshop.dev/shop/UID_373.html';
	$s1=strpos($url,"UID_")+4;
	
	$s3=strpos($url,"_",$s1);
		
	if ($s3!==false)
		$s2=$s3;
	else
		$s2=strpos($url,".html");
	
	$len=$s2-$s1;
	
	$id=substr($url,$s1,$len);
	
	$usl=" id='".$id."' ";
	
	$us_ = $id;
	//echo $id;
	$sql0_2="set names utf8;";
	$res0_2=mysql_query($sql0_2);
	$sql0_2="SELECT name,price,baseinputvaluta FROM ".$SysValue['base']['products']." WHERE ".$usl." and enabled='1'";
	//echo $sql0_2;
	$res0_2=mysql_query($sql0_2);
	while($row0_2=mysql_fetch_assoc($res0_2)){
		$tovar_name=$row0_2['name'];
		$tovar_price=$row0_2['price'];
		$tovar_basevaluta=$row0_2['baseinputvaluta'];
	}				
	if ($tovar_basevaluta==6) {
		$tovar_basevaluta='руб.';
	}
	
	//$tovar_basevaluta='руб.';
	
	$mas['item1'] = WinToUtf8($item1);
	$mas['item2'] = WinToUtf8($item2);		
	$mas['item3'] = WinToUtf8($item3);
	$mas['item4'] = WinToUtf8($item4);
	$mas['item5'] = WinToUtf8($item5);
	$mas['item6'] = $tovar_name;
	$mas['item7'] = $tovar_price;
	$mas['item8'] = WinToUtf8($tovar_basevaluta);
	$mas['item9'] = WinToUtf8($item6);	
	if (isset($_COOKIE['sincity'])) {
		switch ($_COOKIE['sincity']) {
			case 'm':$mas['item9']=WinToUtf8('Москва');
				break;
			case 'sp':$mas['item9']=WinToUtf8('Санкт-Петербург');
				break;
			case 'chb':$mas['item9']=WinToUtf8('Чебоксары');
				break;				
			case 'other':$mas['item9']=WinToUtf8('другой регион');
				break;				
		}
	} else {
			$mas['item9']=WinToUtf8('Москва');
	}
	$mas['item10'] = WinToUtf8($item7);	
	$mas['ok'] = 1;
	//print_r($mas);
	echo json_encode($mas);
	
	function WinToUtf8($data)
	{
		if (is_array($data))
		{
			$d = array();
			foreach ($data as $k => &$v) $d[WinToUtf8($k)] = WinToUtf8($v);
			return $d;
		}
		if (is_string($data))
		{
			if (function_exists('iconv')) return iconv('cp1251', 'utf-8//IGNORE//TRANSLIT', $data);
			if (! function_exists('cp1259_to_utf8')) include_once 'cp1259_to_utf8.php';
			return WinToUtf8($data);
		}
		if (is_scalar($data) or is_null($data)) return $data;
		#throw warning, if the $data is resource or object:
		trigger_error('An array, scalar or null type expected, ' . gettype($data) . ' given!', E_USER_WARNING);
		return $data;
	}	
?>
