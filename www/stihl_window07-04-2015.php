<?
	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);

	$item1=$SysValue['lang']['stihl_window_string1'];
	$item2=$SysValue['lang']['stihl_window_string2'];
	$item3=$SysValue['lang']['stihl_window_string3'];
	$item4=$SysValue['lang']['stihl_window_string4'];
	$item5=$SysValue['lang']['stihl_window_string5'];
	$item6=$SysValue['lang']['stihl_window_string6'];
	
	$mas['item1'] = WinToUtf8($item1);
	$mas['item2'] = WinToUtf8($item2);
	$mas['item3'] = WinToUtf8($item3);
	$mas['item4'] = WinToUtf8($item4);
	$mas['item5'] = WinToUtf8($item5);
	$mas['item6'] = WinToUtf8($item6);
		
	if ( $_POST['reg_info']=='m' ) {
		
		$stihl_window_moscow_string1=$SysValue['lang']['stihl_window_moscow_string1'];
		$stihl_window_moscow_phone_string1=$SysValue['lang']['stihl_window_moscow_phone_string1'];
		$stihl_window_moscow_string2=$SysValue['lang']['stihl_window_moscow_string2'];
		$stihl_window_moscow_phone_string2=$SysValue['lang']['stihl_window_moscow_phone_string2'];

		$mas['item7'] = WinToUtf8($stihl_window_moscow_string1);
		$mas['item8'] = WinToUtf8($stihl_window_moscow_phone_string1);
		$mas['item9'] = WinToUtf8($stihl_window_moscow_string2);
		$mas['item10'] = WinToUtf8($stihl_window_moscow_phone_string2);
		
	} elseif ( $_POST['reg_info']=='sp' ) {
		
		$stihl_window_spb_string1=$SysValue['lang']['stihl_window_spb_string1'];
		$stihl_window_spb_phone_string1=$SysValue['lang']['stihl_window_spb_phone_string1'];

		$mas['item7'] = WinToUtf8($stihl_window_spb_string1);
		$mas['item8'] = WinToUtf8($stihl_window_spb_phone_string1);
		
	} elseif ( $_POST['reg_info']=='chb' ) {
		
		$stihl_window_chb_string1=$SysValue['lang']['stihl_window_chb_string1'];
		$stihl_window_chb_phone_string1=$SysValue['lang']['stihl_window_chb_phone_string1'];

		$mas['item7'] = WinToUtf8($stihl_window_chb_string1);
		$mas['item8'] = WinToUtf8($stihl_window_chb_phone_string1);
			
	} elseif ( $_POST['reg_info']=='other' ) {
		
		$stihl_window_other_string1=$SysValue['lang']['stihl_window_other_string1'];
		$stihl_window_other_phone_string1=$SysValue['lang']['stihl_window_other_phone_string1'];

		$mas['item7'] = WinToUtf8($stihl_window_other_string1);
		$mas['item8'] = WinToUtf8($stihl_window_other_phone_string1);
			
	}
	
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
