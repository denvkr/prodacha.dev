<?php
/*
 * PHPShop
 * ������ ��� ����������� ��������� ������� IntellectMoney
 *
Last Changed Rev: 13274
Last Changed Date: 2011-12-01 10:34:25 +0400 (Thu, 01 Dec 2011)
 */
?>
<?php

require_once('../../phpshop/lib/parser/parser.php');

if(empty($GLOBALS['SysValue']))
{
	global $SysValue;
	
	// ������ ������������ ����
	$SysValue = parse_ini_file("../../phpshop/inc/config.ini", 1);
	while(list($section, $array) = each($SysValue))
		while(list($key, $value) = each($array))
			$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))] = $value;
}

if(!empty($SysValue['templates']['shop']))
{
	global $SysValue;
	
	// ��������� ������������ �� ������
	$SysValue['other']['DispShop'] = ParseTemplateReturn('error/error_payment.tpl');
	
	// ���������� ������ 
	ParseTemplate($SysValue['templates']['shop']);
}
else
	exit(header("Location: /"));

?>