<?php
/*
 * PHPShop
 * Модуль для подключения платежной системы IntellectMoney
 *
Last Changed Rev: 13274
Last Changed Date: 2011-12-01 10:34:25 +0400 (Thu, 01 Dec 2011)
 */
?>
<?php

if(empty($GLOBALS['SysValue']))
{
	global $SysValue;
	
	// Парсим установочный файл
	$SysValue = parse_ini_file("../phpshop/inc/config.ini", 1);
	while(list($section, $array) = each($SysValue))
		while(list($key, $value) = each($array))
			$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))] = $value;
}

/**
 * Форматирование имени заказа
 * @package PHPShopCoreDepricated
 * @param int $uid номер заказа
 * @return string 
 */
function UpdateNumOrder($uid)
{
	$last_num = substr($uid, -2);
	$total = strlen($uid);
	$ferst_num = substr($uid, 0, ($total - 2));
	return $ferst_num."-".$last_num;
}

if($_REQUEST['LMI_PAYMENT_NO'])
{
	global $SysValue;
	
	// perform some action (change order state to paid)
	// Подключаем базу MySQL
	@mysql_connect($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db']) or 
	@die("".PHPSHOP_error(101, $SysValue['my']['error_tracer'])."");
	mysql_select_db($SysValue['connect']['dbase']) or 
	@die("".PHPSHOP_error(102, $SysValue['my']['error_tracer'])."");
	
	// берем описания из базы
	$sql = "select message,message_header from ".$SysValue['base']['table_name48']." where  path='intellectmoney' and enabled='1'";
	$result = mysql_query(@$sql);
	$row = mysql_fetch_array(@$result);
	$message = $row['message'];
	$message_header = $row['message_header'];
	
	$new_uid = UpdateNumOrder($_REQUEST['LMI_PAYMENT_NO']);
	$SysValue['other']['numOrder'] = $new_uid;

	// Сообщение пользователю об успешном платеже
	$SysValue['other']['mesageText'] = "<FONT style=\"font-size:14px;color:red\"><B>".$message_header."</B></FONT><BR>".$message;
	$SysValue['other']['orderMesage'] = ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
	$SysValue['other']['DispShop'] = ParseTemplateReturn($SysValue['templates']['order_forma_mesage_main']);
	
	// Очищаем корзину
	$_SESSION['cart'] = null;
	unset($_SESSION['cart']);
	
	// Подключаем шаблон 
	ParseTemplate($SysValue['templates']['shop']);
}
else
	exit(header("Location: /"));
	
?>