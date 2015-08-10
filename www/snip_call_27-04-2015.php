<?php
//Error_Reporting(E_ALL & ~E_NOTICE);

$_classPath = "phpshop/";
include_once($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("nav");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

//include_once($_classPath . "core/oneclick.core.php");



// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// SQL
$PHPShopOrm = new PHPShopOrm();


//$PHPShopOneclick=new PHPShopOneclick();

//$PHPShopSystem = new PHPShopCore();

//echo 'yo_name '.$_POST['yo_name'].'yo_phone '.$_POST['yo_phone'].'cv3 '.$_POST['cv3'];
if (isset($_POST['email']))
{

				Error_Reporting(E_ALL & ~E_NOTICE);
				
				if ($_POST['textArea_ask_question']=='Вопрос') {
					header("HTTP/1.1 301 Moved Permanently");
					header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."");
					exit();
				}

				$m=date("m");if($m=='01') $m1="января";if($m=='02') $m1="февраля";if($m=='03') $m1="марта";if($m=='04') $m1="апреля";if($m=='05') $m1="мая";if($m=='06') $m1="июня";if($m=='07') $m1="июля";if($m=='08') $m1="августа";if($m=='09') $m1="сентября";if($m=='10') $m1="октября";if($m=='11') $m1="ноября";if($m=='12') $m1="декабря";
				
				$date=date("d")." ".$m1." ".date("Y ")."года в ".date(" H:i:s");
				
				 $taim=date(" H:i:s, d m Y");
				 $ip=getenv("REMOTE_ADDR");
				 
				// print_r($_POST);
				 $msg="				 																		 
							Данные пользователя (ip ".$ip."), оставившего заявку ".$date." :	
							
							";						   						   						   
				
				//$_POST['input1']=urldecode($_POST['input1']);
				
				//$input1=iconv("utf-8","windows-1251",$_POST['input1']);//конуертируем 
										 
				$msg.="	
				
					Почта: ".$_POST['email']."	
					Текст послания:	".$_POST['textArea_ask_question']."			
					";
					
					 $subject="Вопрос с  сайта ".$_SERVER['HTTP_HOST']." ";		
					 
					 // Отсылаем письмо администратору
					 if ($_COOKIE['sincity']=="sp") {
					 	$mail_to=$GLOBALS['SysValue']['mail']['spb_mail'];
					 } else if ($_COOKIE['sincity']=="chb") {
					 	$mail_to=$GLOBALS['SysValue']['mail']['chb_mail'];
					 } else {
					 	$mail_to=$GLOBALS['SysValue']['mail']['msc_mail'];
					 }				
					//$mail_to="denvkr@yandex.ru,zakaz@prodacha.ru";

				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];			

				if ($_POST['cv']=="nohspamcode") //проверко+антиспам ок
				{
					
					if  (mail($mail_to,$subject,$msg,$mailheaders)) 
					{					
						header("HTTP/1.1 301 Moved Permanently");
						header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."");	
						exit();
					}
					
					//echo $msg;
				}	
				
}

if (!empty($_POST['yo_name']) && (!empty($_POST['yo_phone']) || !empty($_POST['yo_mail'])) )
{

	$m=date("m");if($m=='01') $m1="января";if($m=='02') $m1="февраля";if($m=='03') $m1="марта";if($m=='04') $m1="апреля";if($m=='05') $m1="мая";if($m=='06') $m1="июня";if($m=='07') $m1="июля";if($m=='08') $m1="августа";if($m=='09') $m1="сентября";if($m=='10') $m1="октября";if($m=='11') $m1="ноября";if($m=='12') $m1="декабря";

	$date=date("d")." ".$m1." ".date("Y ")."года в ".date(" H:i:s");

	$taim=date(" H:i:s, d m Y");
	$ip=getenv("REMOTE_ADDR");
		
	// print_r($_POST);
	$msg=$_POST['zakaz_info']."
		Данные пользователя (ip ".$ip."), оставившего заявку ".$date." :
		
	";

	//$_POST['input1']=urldecode($_POST['input1']);

	//$input1=iconv("utf-8","windows-1251",$_POST['input1']);//конуертируем
	if (!empty($_POST['yo_phone']) && empty($_POST['yo_mail'])) $phonemail=" Телефон:          ".$_POST['yo_phone'];
	if (empty($_POST['yo_phone']) && !empty($_POST['yo_mail'])) $phonemail=" Email:          ".$_POST['yo_mail'];
	if (!empty($_POST['yo_phone']) && !empty($_POST['yo_mail'])) $phonemail=" Телефон:          ".$_POST['yo_phone']." Email:          ".$_POST['yo_mail'];
	if (!empty($_POST['tovar_info_input'])) {
		$tovarinfo="
		Заказ:            ".$_POST['tovar_info_input'];
	}
	$msg.="
	Имя пользователя: ".$_POST['yo_name'].$tovarinfo."
	Пришел с          ".$_POST['referer_info'].
	$phonemail."
	Регион:           ".$_POST['region'];

	if (!empty($_POST['tovar_info_input'])) {
		$zapros=" Заказал: ".$_POST['tovar_info_input'];
	}
	if (empty($_POST['yo_question']) && !empty($_POST['yo_start_hour']) && !empty($_POST['yo_end_hour'])) {
		$zapros=" Время звонка c ".$_POST['yo_start_hour']." до ".$_POST['yo_end_hour'];
	}
	if (!empty($_POST['yo_question']) && !empty($_POST['yo_start_hour']) && !empty($_POST['yo_end_hour'])) {
		$zapros=" Время звонка c ".$_POST['yo_start_hour']." до ".$_POST['yo_end_hour']." Вопрос: ".$_POST['yo_question'];
	}

	$subject="Пользователь:".$_POST['yo_name'].$phonemail.$zapros." ";


	// Отсылаем письмо администратору
	if ($_COOKIE['sincity']=="sp") {
		$mail_to=$GLOBALS['SysValue']['mail']['spb_mail'];
	} else if ($_COOKIE['sincity']=="chb") {
		$mail_to=$GLOBALS['SysValue']['mail']['chb_mail'];
	} else {
		$mail_to=$GLOBALS['SysValue']['mail']['msc_mail'];
	}
	
	//$mail_to="denvkr@yandex.ru,zakaz@prodacha.ru";

	$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
	$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];

	if ($_POST['cv3']=="nohspamcode") //проверко+антиспам ок
	{

		if  (mail($mail_to,$subject,$msg,$mailheaders))
		{
			//пишем данные в базу данных/
			$PHPShopOrm->debug = false;
			if (empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
			if (!empty($_POST['yo_phone']) && empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
			if (!empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
			//echo $sql;
			//пишем данные в базу данных/
			$PHPShopOrm->debug = false;
			if (empty($_POST['yo_question']) || !empty($_POST['yo_question']) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['reversecall_jurnal'].' (`date`,`time_start`,`time_end`,`name`,`tel`,`message`,`status`,`ip`) VALUES (\''.time().'\','.$_POST['yo_start_hour'].','.$_POST['yo_end_hour'].',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['zakaz_info'].' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").' Вопрос: '.trim($_POST['yo_question']," \t\n\r\0\x0B").'\',\'1\',\''.$ip.'\')';
				
			$result = $PHPShopOrm->query($sql);

			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
				
			exit();
		}

	}

}

/**
 * Запись в базу
 */
function write() {

	$PHPShopProduct = new PHPShopProduct(intval($_POST['oneclick_mod_product_id']));

	// Подключаем библиотеку отправки почты
	//PHPShopObj::loadClass("mail");
	$insert=array();
	$insert['name_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2);
	$insert['tel_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2);
	$insert['date_new'] = time();
	$insert['message_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2);
	$insert['ip_new'] = $_SERVER['REMOTE_ADDR'];
	$insert['product_name_new'] = $PHPShopProduct->getName();
	$insert['product_id_new'] = intval($_POST['oneclick_mod_product_id']);
	$insert['product_price_new'] = PHPShopProductFunction::GetPriceValuta(intval($_POST['oneclick_mod_product_id']),$PHPShopProduct->getPrice(),$PHPShopProduct->getParam('baseinputvaluta'),true).' '.$this->PHPShopSystem->getDefaultValutaCode();

	// Запись в базу
	$this->PHPShopOrm->insert($insert);

	$zag = $this->PHPShopSystem->getValue('name') . " - Быстрый заказ - " . PHPShopDate::dataV($date);
	$message = "
	Доброго времени!
	---------------

	С сайта " . $this->PHPShopSystem->getValue('name') . " пришел быстрый заказ

	Данные о пользователе:
	----------------------

	Имя:                " . $insert['name_new'] . "
	Телефон:            " . $insert['tel_new'] . "
	Товар:              ".$insert['product_name_new']." / ID ".$insert['product_id_new']." / ".$insert['product_price_new']."
	Сообщение:          " . $insert['message_new'] . "
	Дата:               " . PHPShopDate::dataV($insert['date_new']) . "
	IP:                 " . $_SERVER['REMOTE_ADDR'] . "

	---------------

	С уважением,
	http://" . $_SERVER['SERVER_NAME'];

	//new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
}
?>