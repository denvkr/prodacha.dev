<?
 session_start(); 
$SysValue = parse_ini_file('../phpshop/inc/config.ini', 1);
global $SysValue;
include_once('../phpshop/class/obj.class.php');
//include_once('../index0.php');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass('orm');
PHPShopObj::loadClass('cart');
PHPShopObj::loadClass('product');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('elements');

//инициализируем класс корзины order
//PHPShopObj::loadClass('order');

$PHPShopBase=new PHPShopBase('../phpshop/inc/config.ini',true);
//global $PHPShopBase;
$PHPShopSystem=new PHPShopSystem();
$PHPShopOrm=new PHPShopOrm();
$PHPShopValutaArray=new PHPShopValutaArray(6);

//получаем ссылку на класс
//$PHPShopOrder =PHPShopOrderFunction();
$PHPShopCart=new PHPShopCart(false);

/**
 * серверная часть ajax кнопки "купить"
 * @author denvkr
 * @tutorial https://trello.com/c/dy3WB3Rz/49--
 * @version 1.0
 */

	$tovar_price=0;
        $tovar_basevaluta='';
        $tovar_pic_small='';
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
	
	//echo $PHPShopOrder->getNum();
	//echo $PHPShopOrder->getTotal(true);        
	$usl=" id='".$_POST['xid']."' ";
	
	$sql0_2="set names utf8;";
	$res0_2=mysql_query($sql0_2);
        //выбираем товар из базы
	$sql0_2="SELECT name,price,price_n,price2,price3,baseinputvaluta,pic_small FROM ".$SysValue['base']['products']." WHERE ".$usl." and enabled='1'";
        //выбираем кол-во товаров из корзины
        //$sql0_3="SELECT  FROM ".$SysValue['base']['orders']." WHERE ".." and enabled='1'";
	//echo $sql0_2;
	$res0_2=mysql_query($sql0_2);
	while($row0_2=mysql_fetch_assoc($res0_2)){
		$tovar_name=$row0_2['name'];
            // Если нет новой цены
            if (empty($row['price_n'])) {
            	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            		$tovar_price=$row['price2'];
            	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$tovar_price=$row['price3'];
            	}
            	else {
            		$tovar_price=$row['price'];
            	}
            }
		$tovar_price=$row0_2['price'];
		$tovar_basevaluta=$row0_2['baseinputvaluta'];
                $tovar_pic_small=$row0_2['pic_small'];
	}				
	if ($tovar_basevaluta==6) {
		$tovar_basevaluta='руб.';
	}
	
	//$tovar_basevaluta='руб.';
	$mas['item1'] = '';
	$mas['item2'] = $tovar_name;
	$mas['item3'] = $tovar_price;
	$mas['item4'] = WinToUtf8($tovar_basevaluta);
	if (isset($_COOKIE['sincity'])) {
		switch ($_COOKIE['sincity']) {
			case 'm':$mas['item5']=WinToUtf8('Москва');
				break;
			case 'sp':$mas['item5']=WinToUtf8('Санкт-Петербург');
				break;
			case 'chb':$mas['item5']=WinToUtf8('Чебоксары');
				break;				
			case 'other':$mas['item5']=WinToUtf8('другой регион');
				break;				
		}
	} else {
			$mas['item5']=WinToUtf8('Москва');
	}
        $mas['item6'] = $tovar_pic_small;
        $mas['item7'] =$PHPShopCart->getNum();
        $mas['item8'] =$PHPShopCart->getSum();
	$mas['ok'] = 1;
        //echo $PHPShopCart->getNum();
        //var_dump($GLOBALS);
	//print_r($_SESSION['cart']);
	echo json_encode($mas);
	//var_dump($_GLOBALS['cart']);
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


