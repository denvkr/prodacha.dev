<?php
session_start();

/**
 * Рейтинг товаров
 * @package PHPShopAjaxElementsDepricated
 */


$_classPath="../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("security");

// Подключаем библиотеку поддержки.
require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

$idc = $_REQUEST['idc'];
$idgood = $_REQUEST['idgood'];
$rate = $_REQUEST['rate'];
$enabled = $_REQUEST['enabled'];

$idc = PHPShopSecurity::CleanStr($idc);
$idgood = PHPShopSecurity::CleanStr($idgood);
$rate = PHPShopSecurity::CleanStr($rate);
$enabled = PHPShopSecurity::CleanStr($enabled);
$id_user=$_SESSION['UsersId'];
$userip=$_SERVER['REMOTE_ADDR'];

if ($id_user) {
	$result=1;	
	$sql='select id_vote from '.$SysValue['base']['table_name52'].' where ((id_charact='.$idc.') AND (id_good='.$idgood.') AND (id_user='.$id_user.'))';
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result);
	@$chars_amount=mysql_num_rows($result);

	if ($enabled) { //Если прислали енаблед, значит все голоса отданы и сейчас мы просто включим голоса
		$result=7;		
//		$sql='UPDATE '.$SysValue['base']['table_name52'].' SET enabled="1" WHERE ((id_charact='.$idc.') AND (id_good='.$idgood.') AND (id_user='.$id_user.'));';
//		$result=mysql_query($sql);
	} else {

		if ($chars_amount) { //Такая оценка уже есть, нужен update
			$sql='UPDATE '.$SysValue['base']['table_name52'].' SET userip="'.$userip.'", rate="'.$rate.'", date="'.mktime().'" WHERE ((id_charact='.$idc.') AND (id_good='.$idgood.') AND (id_user='.$id_user.'));';
			$result=mysql_query($sql);
			$result=2;		
		} else { //Оценки еще нет, нужен insert
			$sql='INSERT INTO '.$SysValue['base']['table_name52'].' (id_charact,id_good,id_user,userip,rate,date) VALUES ("'.$idc.'","'.$idgood.'","'.$id_user.'","'.$userip.'","'.$rate.'",'.mktime().');';
			$result=mysql_query($sql);

			//После вставки делаем проверку - надо ли включить все три
			//Выбираем категорию:
			$sql='select id_category from '.$SysValue['base']['table_name51'].' where (id_charact='.$idc.')';
			$result=mysql_query($sql);
			$row = mysql_fetch_array($result);

                        //Выбираем все характеристики:
			$sql='select id_charact from '.$SysValue['base']['table_name51'].' where (id_category='.$row['id_category'].')';
			$result=mysql_query($sql);
			@$chamount=mysql_num_rows($result); //Собираем количество характеристик

			while ($row = mysql_fetch_array($result)) {$ids.=','.$row['id_charact'];}
                        $ids=substr($ids,1); //Список id характеристик
			$sql="select id_vote from ".$SysValue['base']['table_name52']." where ((id_charact IN (".$ids.")) AND (id_good=".$idgood.") AND (id_user=".$id_user."))";
			$result=mysql_query($sql);
			@$voteamount=mysql_num_rows($result); //Собираем количество отголосованных характеристик			
			if ($voteamount==$chamount) {
				while ($row = mysql_fetch_array($result)) {
					$vote=$row['id_vote'];
					$sql2='UPDATE '.$SysValue['base']['table_name52'].' SET enabled="1" WHERE (id_vote='.$vote.');';
					$result2=mysql_query($sql2);

				}
			}
			$result=3;		
		}
	}

} else {
	$result=0;	
}

$result=print_r($result, true);

$_RESULT = array(
  'result' => $result
); 
?>