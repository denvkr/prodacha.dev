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



// загружаем название папки бекапа
$sql = "SELECT  folder FROM ".$SysValue['base']['table_name47']." WHERE date = (SELECT MAX(date) FROM ".$SysValue['base']['table_name47'].")";
$row = mysql_fetch_array(mysql_query($sql));
$folder = $row['folder'];

if (file_exists("$folder/sql/upload_backup.sql.gz")) {

$temp = "<span style=\"color:red\">Выполните ШАГ 3!</span><br> ";	
log_write("Проставьте необходимые права на нижеуказанные папки!");
if (!copy("$folder/sql/upload_backup.sql.gz","../../dumper/backup/upload_backup.sql.gz")) {
	$GLOBALS['_RESULT']['stat']=  "<span style=\"color:red\">Не возможно скопировать upload_backup.sql.gz из папки бекапа</span>";	
	$GLOBALS['_RESULT']['susses']=  "error";	
	log_write("Не возможно скопировать upload_backup.sql.gz из папки бекапа");
	exit();
}

}

// анализируем файл конфига апдейта
$map = @parse_ini_file("$folder/upd_conf.txt",1);
if(empty($map)){
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:5 - не удаётся провести анализ конфига обновлений</span>";
	log_write("не удаётся провести анализ конфига обновлений");
	exit();
}

// формируем список папок, к которым нужно поменять права доступа. Если возможно меняем права автоматом.
$temp1 = "";

////////////соединение с фтп пользователя /////////////////////////
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
/////////////////////////////////////////////////////


log_write("======= Карта папок====");
foreach ($map as $k=>$v) {
	$temp1 .= upload_folder_create($k, $temp,$ftp_stream);
}


$temp1 = "======= Карта папок ====<br>$temp1======================";


$GLOBALS['_RESULT'] = array(
'stat' => $temp,
'susses' => "dirList"
);

	$sql = "SELECT MAX(date)as date1 FROM ".$SysValue['base']['table_name47'];
	$row=mysql_fetch_array(mysql_query($sql));
	$sql = "UPDATE ".$SysValue['base']['table_name47']." SET backup_use = '1' WHERE date = '".$row['date1']."";
	mysql_query($sql);
	
	$GLOBALS['_RESULT']['stat']=  " <span style=\"color:green\">Копирование резервных файлов завершено!<br> $temp</span><br> $temp1 <br>";	
	$GLOBALS['_RESULT']['susses']=  "end_backup";	
	log_write("Копирование резервных файлов завершено!");
	
	
	
	
	
	
	
	
function upload_folder_create($str,$mask,$ftp_stream){
	global $SysValue;
	
	$str= str_replace(" ","",$str);
	if($str == "/") {
		if(ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['re_chmod']." ".$SysValue['user_ftp']['dir']."/$folder")) $temp1 = " - права проставлены автоматически!";
		log_write("/  - на корневую папку сайта $temp1");
		return "/  - на корневую папку сайта $temp1<br>";
	}
	$str = explode("/",$str);
	
	
	for ($i=count($str)-1;$i>=0;$i--){
		$folder = $str[0];
		for ($c=1;$c<=$i;$c++) 
		{
			$folder .= "/".$str[$c];		
			
		}
		if (is_dir("../../../../$folder")) {
			if(!stristr($mask,$folder."<br>")){
				$temp .= $folder;
				if(ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['re_chmod']." ".$SysValue['user_ftp']['dir']."/$folder")) $temp .= " - права проставлены автоматически!";
				$temp .= "<br>";
				log_write($temp);
			}
			return $temp;
			
		}
		
	}
	return $temp;

}
?>