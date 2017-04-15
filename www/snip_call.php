<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
Error_Reporting(E_ALL & ~E_NOTICE);

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
//echo '-1'.var_dump(!isset($_POST['yo_question']) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour'])).'</br>';
//echo '0'.var_dump(isset($_POST['yo_question']) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour'])).'</br>';
//echo '1'.var_dump(empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour'])).'</br>';
//echo '2'.var_dump(!empty($_POST['yo_phone']) && empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour'])).'</br>';
//echo '3'.var_dump(!empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour'])).'</br>';
//echo '4'.var_dump((empty($_POST['yo_question']) || !empty($_POST['yo_question'])) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour'])).'</br>';
//echo 'yo_name '.$_POST['yo_name'].'yo_phone '.$_POST['yo_phone'].'cv3 '.$_POST['cv3'];

if (isset($_POST['email']))
{

				//Error_Reporting(E_ALL & ~E_NOTICE);
				
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
					
					 $subject="Вопрос с сайта ".$_SERVER['HTTP_HOST']." ";	
					 
					 // Отсылаем письмо администратору
					 if ($_COOKIE['sincity']=="sp") {
					 	$mail_to=$GLOBALS['SysValue']['mail']['spb_mail'];
					 } else if ($_COOKIE['sincity']=="chb") {
					 	$mail_to=$GLOBALS['SysValue']['mail']['chb_mail'];
					 } else if ($_COOKIE['sincity']=="kur") {
					 	$mail_to=$GLOBALS['SysValue']['mail']['kur_mail'];
					 } else {
					 	$mail_to=$GLOBALS['SysValue']['mail']['msc_mail'];
					 }				
					//$mail_to="denvkr@yandex.ru,zakaz@prodacha.ru";

				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];

				if ($_POST['cv']=="nohspamcode" && 
				!empty($_POST['textArea_ask_question']) && 
				!empty($_POST['email']) && 
				strstr($_POST['textArea_ask_question'],'<a href')===false &&
				strstr($_POST['textArea_ask_question'],'url=')===false &&
				$ip!='46.161.9.32') //проверко+антиспам ок
				{
					
					if  (mail($mail_to,$subject,$msg,$mailheaders)) 
					{					
						header("HTTP/1.1 301 Moved Permanently");
						header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."");	
						exit();
					}
					
					//echo $msg;
				}	
				
}

if (isset($_POST['yo_name']) && (isset($_POST['yo_phone']) || isset($_POST['yo_mail'])) )
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
	if (!isset($_POST['yo_question']) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour'])) {
		$zapros=" Время звонка c ".$_POST['yo_start_hour']." до ".$_POST['yo_end_hour'];
	}
	if (isset($_POST['yo_question']) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour'])) {
		$zapros=" Время звонка c ".$_POST['yo_start_hour']." до ".$_POST['yo_end_hour']." Вопрос: ".$_POST['yo_question'];
	}
	//echo 'zapros'.$zapros;
	$subject="Пользователь:".$_POST['yo_name'].$phonemail.$zapros." ";


	// Отсылаем письмо администратору
	if ($_COOKIE['sincity']=="sp") {
		$mail_to=$GLOBALS['SysValue']['mail']['spb_mail'];
	} else if ($_COOKIE['sincity']=="chb") {
		$mail_to=$GLOBALS['SysValue']['mail']['chb_mail'];
	} else if ($_COOKIE['sincity']=="kur") {
		$mail_to=$GLOBALS['SysValue']['mail']['kur_mail'];
	} else {
		$mail_to=$GLOBALS['SysValue']['mail']['msc_mail'];
	}
	
	$mail_to=$mail_to;//"denvkr@yandex.ru,".$mail_to;

	$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
	$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];
	
        //echo $mailheaders.'<br>'.$mail_to.'<br>'.$subject.'<br />'.$msg;
	if ($_POST['cv3']=="nohspamcode") //проверко+антиспам ок
	{
            $mailed=mail($mail_to,$subject,$msg,$mailheaders);
		if  ($mailed==true)                        
		{
			//пишем данные в базу данных/
			$PHPShopOrm->debug = false;
			/*
			if (empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
			if (!empty($_POST['yo_phone']) && empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
			if (!empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']))
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
				*/
			//$file = "ga_analytic.log";
				
			if (empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']) && $_POST['window_type']=='fast_order_window2') {
				$sql="SELECT auto_increment FROM information_schema.tables WHERE table_schema='".$GLOBALS['SysValue']['connect']['dbase']."' and table_name='".$GLOBALS['SysValue']['base']['oneclick_jurnal']."'";			
				$result = $PHPShopOrm->query($sql);
				$row=mysql_fetch_assoc($result);
				
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
				//echo $sql;			
				$result = $PHPShopOrm->query($sql);
				
				$ga_aim=send_to_order_hook($_POST,$PHPShopOrm,'buy1click',$row);
				//$fh=fopen($file,"a+");
				//fwrite ($fh,$ga_aim);
				//fclose($fh);				
				echo $ga_aim;
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true\"'>";

				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
				//exit();
			} else
			if (!empty($_POST['yo_phone']) && empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']) && $_POST['window_type']=='fast_order_window2') {
				$sql="SELECT auto_increment FROM information_schema.tables WHERE table_schema='".$GLOBALS['SysValue']['connect']['dbase']."' and table_name='".$GLOBALS['SysValue']['base']['oneclick_jurnal']."'";			
				$result = $PHPShopOrm->query($sql);
				$row=mysql_fetch_assoc($result);

				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
                //echo $sql;			
				$result = $PHPShopOrm->query($sql);
				
				$ga_aim=send_to_order_hook($_POST,$PHPShopOrm,'buy1click',$row);
				//$fh=fopen($file,"a+");
				//fwrite ($fh,$ga_aim);
				//fclose($fh);				
				echo $ga_aim;
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true\"'>";

				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
				//exit();
			} else
			if (!empty($_POST['yo_phone']) && !empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']) && $_POST['window_type']=='fast_order_window2') {
				$sql="SELECT auto_increment FROM information_schema.tables WHERE table_schema='".$GLOBALS['SysValue']['connect']['dbase']."' and table_name='".$GLOBALS['SysValue']['base']['oneclick_jurnal']."'";			
				$result = $PHPShopOrm->query($sql);
				$row=mysql_fetch_assoc($result);

				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['oneclick_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Email: '.trim($_POST['yo_mail']," \t\n\r\0\x0B").' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\',0,\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\')';
				//echo $sql;			
				$result = $PHPShopOrm->query($sql);

				$ga_aim=send_to_order_hook($_POST,$PHPShopOrm,'buy1click',$row);
				//$fh=fopen($file,"a+");
				//fwrite ($fh,$ga_aim);
				//fclose($fh);				
				echo $ga_aim;
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true\"'>";
				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
				//exit();
			} else	
			if ((empty($_POST['yo_question']) || !empty($_POST['yo_question'])) && isset($_POST['yo_start_hour']) && isset($_POST['yo_end_hour']) && $_POST['window_type']=='ask_reverse_call') {
				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['reversecall_jurnal'].' (`date`,`time_start`,`time_end`,`name`,`tel`,`message`,`status`,`ip`) VALUES (\''.time().'\','.$_POST['yo_start_hour'].','.$_POST['yo_end_hour'].',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['zakaz_info'].' Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").' Вопрос: '.trim($_POST['yo_question']," \t\n\r\0\x0B").'\',\'1\',\''.$ip.'\')';
				//echo $sql;			
				$result = $PHPShopOrm->query($sql);
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true\"'>";
				
				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
				//exit();                                
			} else
			if (!empty($_POST['yo_phone']) && empty($_POST['yo_mail']) && !isset($_POST['yo_start_hour']) && $_POST['window_type']=='discount_window2') {
				$sql="SELECT auto_increment FROM information_schema.tables WHERE table_schema='".$GLOBALS['SysValue']['connect']['dbase']."' and table_name='".$GLOBALS['SysValue']['base']['discount_jurnal']."'";			
				$result = $PHPShopOrm->query($sql);
				$row=mysql_fetch_assoc($result);

				$sql='INSERT INTO '.$GLOBALS['SysValue']['base']['discount_jurnal'].' (`date`,`name`,`tel`,`message`,`product_name`,`product_id`,`product_price`,`status`,`ip`,`se`) VALUES (\''.time().'\',\''.$_POST['yo_name'].'\',\''.$_POST['yo_phone'].'\',\''.$_POST['tovar_info_input'].'\',\''.$_POST['zakaz_info'].'. Пришел с '.trim($_POST['referer_info']," \t\n\r\0\x0B").' Регион: '.trim($_POST['region']," \t\n\r\0\x0B").'\','.$_POST['tovar_id'].',\''.$_POST['tovar_price'].'\',\'1\',\''.$ip.'\',\''.$_POST['se'].'\')';
				//echo $sql;
				$result = $PHPShopOrm->query($sql);
				$ga_aim=send_to_order_hook($_POST,$PHPShopOrm,'discount',$row);
				//$fh=fopen($file,"a+");
				//fwrite ($fh,$ga_aim);
				//fclose($fh);				
				echo $ga_aim;
				//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."&discount=true\"'>";

				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&discount=true");
				//exit();
			} else
			//echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />'
				echo "<meta http-equiv='refresh' content='0; url=\"/redirect.php?url=".$_SERVER['HTTP_REFERER']."\"'>";

			//header("HTTP/1.1 301 Moved Permanently");
			//header("Location:https://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']);
				
			//exit();
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
	Товар:              " . $insert['product_name_new']." / ID ".$insert['product_id_new']." / ".$insert['product_price_new']."
	Сообщение:          " . $insert['message_new'] . "
	Дата:               " . PHPShopDate::dataV($insert['date_new']) . "
	IP:                 " . $_SERVER['REMOTE_ADDR'] . "

	---------------

	С уважением,
	https://" . $_SERVER['SERVER_NAME'];

	//new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
}
function send_to_order_hook($obj,$_PHPShopOrm,$source,$orderId) {
	//echo 'index_hook_function';
	if ( empty($orderId['auto_increment']) )
		$orderId['auto_increment']=$source.time().rand ( 10,99 );
	switch ($source){
		case 'discount':
					$event='/virtualPage/buy-discount-send/';
					break;
		case 'buy1click':
					$event='/virtualPage/buy-fast-send/';
					break;
		default :
					$event='';
	}
	$ga_commerce="<script>
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
	ga('create', 'UA-29201270-1', 'auto');
	ga('require', 'displayfeatures');     
	ga('send', 'pageview', '".$event.$orderId['auto_increment']."/');
	ga('require', 'ecommerce', 'ecommerce.js');
	ga('ecommerce:addTransaction', {";
	//$gag_commerce="_gaq.push(['_addTrans',";
	if ( isset($obj['transact_id']) ) {
		$ga_commerce.="'id': '".$obj['transact_id']."',";
		//$gag_commerce.="'".$obj['tovar_info_input']."',";
		
	}
	else {
		$ga_commerce.="'id': '0',";
		//$gag_commerce.="'',";
	}
	if ( isset($obj['region']) ) {
		$ga_commerce.="'affiliation': '".$obj['region']."',";
		//$gag_commerce.="'".$obj['region']."',";
	}
	else {
		$ga_commerce.="'affiliation': 'Unknown',";
		//$gag_commerce.="'',";
	}
	if ( isset($obj['tovar_price_short']) )  {
		$ga_commerce.="'revenue': '".$obj['tovar_price_short']."'";
		//$gag_commerce.="'".$obj['tovar_price']."',";
	}
	else {
		$ga_commerce.="'revenue': '0'";
		//$gag_commerce.="'',";
	}
	//$gag_commerce.="'',";
	//$gag_commerce.="'',";
	//$gag_commerce.="'Russia',";
	$ga_commerce.="});";
	//$gag_commerce.="]);";
		

	$ga_commerce.="ga('ecommerce:addItem', {";

	if (isset($obj['transact_id'])) {
		$ga_commerce.="'id': '".$obj['transact_id']."',"; 		// Transaction ID. Required.
	}
	if (isset($obj['tovar_info_input'])) {
		$ga_commerce.="'name': '".$obj['tovar_info_input']."',";    // Product name. Required.
	}
	if (isset($obj['tovar_id'])) {
		$ga_commerce.="'sku': '".$obj['tovar_id']."',";       // SKU/code. Required.
	}
	//выясняем категорию товара
	$sql="select distinct name from ".$GLOBALS['SysValue']['base']['categories']." where id=(select category from ".$GLOBALS['SysValue']['base']['products']." where id=".$obj['tovar_id'].")";
	//echo $sql;
	$result = $_PHPShopOrm->query($sql);
	while ($row=mysql_fetch_assoc($result)){
		$cat_name=$row['name'];
	}
	mysql_free_result($result);
	if (isset($cat_name)) {
		$ga_commerce.="'category': '".$cat_name."',";         	// Category or variation.
	}	
	if ( isset($obj['tovar_price_short']) )  {
		$ga_commerce.="'price': '".$obj['tovar_price_short']."',";  // Unit price. Required.
	}
		
	$ga_commerce.="'quantity': '1'"; // Quantity (integer). Required.
	$ga_commerce.="});";
	//print_r($cart_item);
	//$gag_commerce.="_gaq.push(['_addItem',";
	//$gag_commerce.="'".$cart_item[id]."',";
	//$gag_commerce.="'".$cart_item[id]."',";
	//$gag_commerce.="'".$cart_item[name]."',";
	//$gag_commerce.="'',";
	//$gag_commerce.="'".$cart_item[price]."',";
	//$gag_commerce.="'".$cart_item[num]."',";
	//$gag_commerce.="]);";

	$ga_commerce.="ga('ecommerce:send');ga('ecommerce:clear');";
	//$gag_commerce.="_gaq.push(['_trackTrans']);";
	//$ga_commerce.=$gag_commerce;
	$ga_commerce.="console.log('/buy-fast/success');</script><script src='https://www.google-analytics.com/analytics.js'></script>";

	//$ga_commerce=print_r($obj->PHPShopCart);
	//$ga_commerce.=print_r($post);
	return $ga_commerce;

}

?>

<title>Заказ сохранен.</title>
</head>

<body>
</body>
</html>