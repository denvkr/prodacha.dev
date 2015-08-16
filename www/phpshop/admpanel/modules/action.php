<?php

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();

// Подключаем библиотеку поддержки.
require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest =new Subsys_JsHttpRequest_Php("windows-1251");


PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");


$_POST['moduleAction'] = @$_REQUEST['do'];
$_POST['moduleId'] = @$_REQUEST['xid'];


// Информация по модулю
function GetModuleInfo($name) {
    $path="../../modules/".$name."/install/module.xml";
    if(function_exists("xml_parser_create")) {
        if(@$db=readDatabase($path,"module"))
            return $db[0];
    }
}

if(!CheckedRules($UserStatus["module"],1))
    switch($_POST['moduleAction']) {
        case("off"):

            PHPShopObj::loadClass("modules");
            $PHPShopModules = new PHPShopModules($_classPath."modules/");
            $ModValue=$PHPShopModules->getModValue();

            // Удаление базы
            if(is_array($ModValue['base'][$_POST['moduleId']]))
                foreach($ModValue['base'][$_POST['moduleId']] as $val) mysql_query("DROP TABLE ".$val);

            // Удаление полей
            if(is_array($ModValue['field'][$_POST['moduleId']]))
                foreach($ModValue['field'][$_POST['moduleId']] as $key=>$val) mysql_query("ALTER TABLE `".$val."` DROP `".$key."` ");

            $sql="delete from ".$GLOBALS['SysValue']['base']['modules']." where path='".$_POST['moduleId']."'";
            $result=mysql_query($sql);
            break;

        case("on"):
        // Информация по модулю
            $Info = GetModuleInfo($_POST['moduleId']);

            if(empty($Info['trial'])) $date_end=0;
            else  $date_end=time()+2592000;

            if(empty($Info['key'])) $key=null;
            else $key=$Info['key'];

            $sql="INSERT INTO ".$GLOBALS['SysValue']['base']['modules']."  VALUES ('".$_POST['moduleId']."','".$Info ['name']."',".time().")";
            $result=mysql_query($sql);

            if(!empty($date_end))
            $sql="INSERT INTO ".$GLOBALS['SysValue']['base']['modules_key']."  VALUES ('".$_POST['moduleId']."',".$date_end.",'".$key."','".md5($_POST['moduleId'].$date_end.$_SERVER['SERVER_NAME'].$key)."')";
            $result=mysql_query($sql);

            // Устанавливаем БД модуля
            $modulePath="../../modules/".$_POST['moduleId']."/install/module.sql";
            if(file_exists($modulePath)) {
                $moduleSQLFile=file_get_contents($modulePath);
                $SQLArray=explode(";",$moduleSQLFile);
                foreach($SQLArray as $val) $result=mysql_query($val);
            }

            break;

    }


$_RESULT = array(
        "interfaces"=> 123
);

?>
