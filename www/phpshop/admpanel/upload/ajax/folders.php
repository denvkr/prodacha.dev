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


if (($_SESSION['base_update'] != "susses")&&($_REQUEST['update_base'] == "yes")) {	
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">ШАГ 2-1 небыл выполнен!!!</span>";
	$GLOBALS['_RESULT']['susses']=  "error_upd";	
	log_write("ШАГ 2-1 небыл выполнен!!!");
	exit();
}
$_SESSION['base_update'] = "";

$map = @parse_ini_file("../../../../backup/temp/upd_conf.txt",1);
if(empty($map)){
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:5 - не удаётся провести анализ конфига обновлений</span>";
	log_write("не удаётся провести анализ конфига обновлений");
	exit();
}



/////// подключаемся в фтп пользователя
if (!$ftp_stream = ftp_connect($SysValue['user_ftp']['host'])) {
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя</span>";
	$GLOBALS['_RESULT']['susses']=  "connect_error_user";
	log_write("upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя");
	exit();
}

if (!ftp_login($ftp_stream,$SysValue['user_ftp']['login'],$SysValue['user_ftp']['password'])){
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">ошибка авторизации с сервером пользователя</span>";
	$GLOBALS['_RESULT']['susses']=  "connect_error_user";
	log_write("upload_modul_error:2 - ошибка авторизации с сервером пользователя");
	exit();
}

if (!ftp_pasv($ftp_stream,true)){
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">невозможно установить пассивный режим</span>";
	$GLOBALS['_RESULT']['susses']=  "connect_error_user";
	log_write("невозможно установить пассивный режим");
	exit();
}

// создаём папку бекапа
//$name = "../../../../backup/backups/".date("Y-m-d--h-i-s");	
	$name = "/backup/backups/".date("Y-m-d--h-i-s");		
	ftp_mkdir($ftp_stream,$SysValue['user_ftp']['dir']."$name");
	ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['chmod']." ".$SysValue['user_ftp']['dir']."$name");	
	ftp_mkdir($ftp_stream,$SysValue['user_ftp']['dir']."/$name/sql");
	ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['chmod']." ".$SysValue['user_ftp']['dir']."$name/sql");
	
	//mkdir($name,0777);
	//mkdir($name."/sql",0777);
	
	
// очищаем лист загрузки перед заполнением...
$sql = "TRUNCATE TABLE  ".$SysValue['base']['table_name46'];
mysql_query($sql);

$errors = "";
foreach ($map as $k=>$v) {
	$errors .= upload_folder_create($k,$name,$ftp_stream);
		
	if (isset($map[$k]['files'])){
		$tt = str_replace(" ","",$map[$k]['files']);
		if($tt!=""){			
			$tt = explode(";",$tt);
			foreach ($tt as $ttt => $t){
				$sql = "INSERT INTO ".$SysValue['base']['table_name46']." SET dir='".$k."', name = '".$t."' ";
				mysql_query($sql);
			}
		}
	}
	
}


if ($errors != ""){
	$GLOBALS['_RESULT']['stat'] = $errors;	
	exit();
}


	if (!file_exists("../../../../backup/temp/upload_backup.sql.gz")){
		$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не создан backup базы данных! Выполните Резервное копирование базы данных! </span>";
		log_write("Не создан backup базы данных! Выполните Резервное копирование базы данных! ");
		exit();
	}
	if (!copy("../../../../backup/temp/upload_backup.sql.gz","../../../..$name/sql/upload_backup.sql.gz")) {
		$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не удаётся скопировать phpshop/admpanel/dumper/backup/upload_backup.sql</span>";
		log_write("Не удаётся скопировать phpshop/admpanel/dumper/backup/upload_backup.sql");
		exit();		
	}
	if (!unlink("../../../../backup/temp/upload_backup.sql.gz")){
		$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не удаётся удалить /backup/temp/upload_backup.sql.gz</span>";
		log_write("Не удаётся удалить /backup/temp/upload_backup.sql.gz");
		exit();
	}
	

$temp =  " создание карты обновлений завершено<br> несуществующие папки дерева созданы";
log_write("создание карты обновлений завершено, несуществующие папки дерева созданы");

$GLOBALS['_RESULT'] = array(
'stat' => $temp,
'susses' => "gobackup",
'folder' => "../../../..".$name
);


function upload_folder_create($str,$name,$ftp_stream){
	global $SysValue;
	$temp = "";
	$str = explode("/",$str);	
	for ($i=0;$i<count($str);$i++){
		$folder = $str[0];
		for ($c=1;$c<=$i;$c++) $folder .= "/".$str[$c];
		
		//  создаём дерево в папке бекапа
		
		if (!ftp_mkdir($ftp_stream,$SysValue['user_ftp']['dir']."$name/$folder")) {
			$temp .= "<span style=\"color:red\">$folder - невозможно создать папку в папке бекапа.</span><br><br>";
			log_write("$folder - невозможно создать папку в папке бекапа.");
		}
		
		if (!ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['chmod']." ".$SysValue['user_ftp']['dir']."$name/$folder")) {
			$temp .= "<span style=\"color:red\">$folder - невозможно проставить права на папку в папке бекапа.</span><br><br>";
			log_write("$folder - невозможно проставить права на папку в папке бекапа.");
		}
		
		
		// если папки указанной в карте нет в системе - создаём ее.
		//if (!is_dir("../../../../$folder")) {
		//	if (!mkdir("../../../../$folder",0777)) {
		//		$temp .= "<span style=\"color:red\">$folder - невозможно создать папку в системе. Убедитесь что вы правильно проставили права доступа на папки!</span><br><br>";
		//		log_write("$folder - невозможно создать папку в системе. Убедитесь что вы правильно проставили права доступа на папки!");
		//	}
		//}
		
	}

}

?>