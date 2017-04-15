<?
        //Включение окна скидок
        $discount_enabled=true;
        
        //функция только для москвы
        if ($_COOKIE['sincity']!=='m' || !$discount_enabled){
            $mas['ok']=-1;
            echo json_encode($mas);
            exit();            
        }

	$SysValue = parse_ini_file('../phpshop/inc/config.ini', 1);

	$item1=$SysValue['lang']['discount_window_item1'];
	$item2=$SysValue['lang']['discount_window_item2'];
	$item3=$SysValue['lang']['discount_window_item3'];
	$item4=$SysValue['lang']['discount_window_item4'];
	$item5=$SysValue['lang']['discount_window_item5'];
	$item6=$SysValue['lang']['discount_window_item5'];

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
	
        session_start();
		
	//из строки берем id товара
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
        
	$sql0_2="SET names utf8;";
	$res0_2=mysql_query($sql0_2);
        
        //отдельно проверяем если товар не на складе
	$sql0_2="SELECT sklad FROM ".$SysValue['base']['products']." WHERE ".$usl." and enabled='1'";
	$res0_2=mysql_query($sql0_2);
        while($row0_2=mysql_fetch_assoc($res0_2)){
                if ( !empty($row0_2['sklad']) ) {
                    $mas['ok']=-1;
                    echo json_encode($mas);
                    exit();
                }
        }
        
        //в случае если такая сессия уже открыта и такой товар выбран и это не перезагрука страницы
	$sql0_2="SELECT se,product_id FROM ".$SysValue['base']['discount_jurnal']." WHERE se='".md5(session_id())."' AND product_id=".$id.";";
	$res0_2=mysql_query($sql0_2);
        $row0_2=mysql_fetch_assoc($res0_2);
	if ($row0_2['se']==md5(session_id()) && $row0_2['product_id']==$id && (bool)$_POST['redirect']){
            $mas['ok']=0;
            echo json_encode($mas);
            exit();
        }
        
        //отдельно рассматриваем чтобы была цена со скидкой price5
	$sql0_2="SELECT name,price,price2,price3,price4,price5,baseinputvaluta,sklad FROM ".$SysValue['base']['products']." WHERE ".$usl." and enabled='1'";
	$res0_2=mysql_query($sql0_2);
        
	while($row0_2=mysql_fetch_assoc($res0_2)){
                if ( $row0_2['price5']==0 ) {
                    $mas['ok']=-1;
                    echo json_encode($mas);
                    exit();
                }
		$tovar_name=$row0_2['name'];
		if ( ($_COOKIE['sincity']=="sp") AND ($row0_2['price2']!=0) ) {
			$price=$row0_2['price2'];
		} else if( ($_COOKIE['sincity']=="chb") AND ($row0_2['price3']!=0) ) {
			$price=$row0_2['price3'];
                } else if( ($_COOKIE['sincity']=="kur") AND ($row0_2['price4']!=0) ) {
                        $price=$row0_2['price4'];
		}
		else {
			$price=intval($row0_2['price']);
		}
                $discount_price=intval($row0_2['price5']);
		$tovar_basevaluta=$row0_2['baseinputvaluta'];
	}				
	if ($tovar_basevaluta==6) {
		$tovar_basevaluta='руб.';
	}
	
	$mas['item1'] = WinToUtf8($item1);
	$mas['item2'] = WinToUtf8($item2);		
	$mas['item3'] = WinToUtf8($item3);
	$mas['item4'] = WinToUtf8($item4);
	$mas['item5'] = WinToUtf8($item5);	
	$mas['item6'] = $tovar_name;
	$mas['item7'] = $price;
	$mas['item8'] = WinToUtf8($tovar_basevaluta);
	if (isset($_COOKIE['sincity'])) {
            switch ($_COOKIE['sincity']) {
                    case 'm':$mas['item9']=WinToUtf8('Москва');
                            break;
                    case 'sp':$mas['item9']=WinToUtf8('Санкт-Петербург');
                            break;
                    case 'chb':$mas['item9']=WinToUtf8('Чебоксары');
                            break;
                    case 'kur':$mas['item9']=WinToUtf8('Курск');
                            break;	
                    case 'other':$mas['item9']=WinToUtf8('другой регион');
                            break;				
            }
	} else {
            $mas['item9']=WinToUtf8('Москва');
	}
	$mas['item10']=$id;
	$sql0_2="set names utf8;";
	$res0_2=mysql_query($sql0_2);
	$sql0_2="SELECT max(id) as id FROM ".$GLOBALS['SysValue']['base']['discount_jurnal'];
	$res0_2=mysql_query($sql0_2);
	$row0_2=mysql_fetch_assoc($res0_2);
	$mas['item11']=$row0_2['id'];
	$mas['item12'] = $discount_price;
	$mas['item13'] = $price-$discount_price;
        $mas['item14'] = md5(session_id());
	$mas['ok'] = 1;
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

