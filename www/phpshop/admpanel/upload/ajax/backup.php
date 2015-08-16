<?php
require_once "../../../../phpshop/lib/Subsys/JsHttpRequest/Php.php";
// Создаем главный объект библиотеки.
// Указываем кодировку страницы (обязательно!).
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");


require("../../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
//////////////////////////////////////////////////////////
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../../language/".$Lang."/language.php");
require("log_save.php");
/////////////////////////////////////////////////////////////////


// проверяем права
if(!CheckedRules($UserStatus["upload"],0) == 1) {
	$GLOBALS['_RESULT']['stat']=  "<span style=\"color:red\">недостаточно прав</span>";	
	$GLOBALS['_RESULT']['susses']=  "error";	
	log_write("недостаточно прав");
	exit();
}	


$dir = $_REQUEST['dir'];
chmod("$dir",0777);

$backup_code = time();

$sql = "select * from ".$SysValue['base']['table_name46']." WHERE backup = '0' LIMIT 1";
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0){
	$row = mysql_fetch_array($result);
	if (file_exists("../../../../".$row['dir']."/".$row['name'])){
		$backup_flag = ",backup_flag = '1'";
		if (!copy("../../../../".$row['dir']."/".$row['name'],"$dir/$row[dir]/$row[name]"))$flag = 1;		
		if ($flag){
			$GLOBALS['_RESULT']['stat']=  "<span style=\"color:red\">ошибка копирования: $row[dir]/$row[name] </span>";	
			log_write("ошибка копирования: $row[dir]/$row[name]");
		}
		else{
			$GLOBALS['_RESULT']['stat']=  "скопирован в резерв: $row[dir]/$row[name] ";	
			log_write("скопирован в резерв: $row[dir]/$row[name]");
		}
	}
	else {
		$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">файл в системе отсутствует: $row[dir]/$row[name] </span>";
		log_write("файл в системе отсутствует: $row[dir]/$row[name]");
	}
	

	$sql = "UPDATE ".$SysValue['base']['table_name46']." SET backup = '1' $backup_flag WHERE id = $row[id]";
	mysql_query($sql);

	

	$GLOBALS['_RESULT']['susses']=  "backup";
}
else{
	// делаем запись о бекапе
	$sql = "INSERT INTO ".$SysValue['base']['table_name47']." SET folder='".mysql_real_escape_string($dir)."', date = ".time();
	mysql_query($sql);
	// копируем конфиг в папку бекапа
	copy("../../../../backup/temp/upd_conf.txt","$dir/upd_conf.txt");
	//	if (!unlink("../../../../backup/temp/upd_conf.txt","$dir/upd_conf.txt")){
	//	$GLOBALS['_RESULT']['stat'] = "Не удаётся удалить /backup/temp/upd_conf.txt";
	//	exit();
	//}
	// восстанавливаем чмод папки
	chmod("$dir",0775);
	
	$GLOBALS['_RESULT']['stat']=  " резервирование закончено успешно";	
	$GLOBALS['_RESULT']['susses']=  "copy";	
	log_write("резервирование закончено успешно");
		
}




?>