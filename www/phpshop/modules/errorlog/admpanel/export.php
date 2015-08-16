<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("date");

$PHPShopBase=&new PHPShopBase($_classPath."inc/config.ini");
$PHPShopSystem=&new PHPShopSystem();

$PHPShopModules = new PHPShopModules('../../');

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.errorlog.errorlog_log"));
if(!empty($_GET['sortdate_start'])) $where=array('date'=>' < '.(PHPShopDate::GetUnixTime($_GET['sortdate_end'])+86400).' AND date > '.(PHPShopDate::GetUnixTime($_GET['sortdate_start'])-86400));
else $where=false;

$data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>100));
$content='Дата;IP;Ошибка;
 ';

if(is_array($data))
foreach($data as $value) $content.=PHPShopDate::dataV($value['date'],false).';'.$value['ip'].';'.$value['error'].';
';

$file = $_classPath.'admpanel/csv/error.csv';
PHPShopFile::write($file,$content);
header("Location: ".$file);
?>
