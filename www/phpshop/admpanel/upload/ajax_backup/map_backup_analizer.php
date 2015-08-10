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
log_clear();
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
//$GLOBALS['_RESULT']['stat'] = $row['folder'];
//exit();


	
// анализируем файл конфига апдейта
$map = @parse_ini_file("$folder/upd_conf.txt",1);
if(empty($map)){
	$GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:5 - не удаётся провести анализ конфига восстановления</span>";
	log_write("не удаётся провести анализ конфига восстановления");
	exit();
}


// формируем список папок, к которым нужно поменять права доступа. Если возможно меняем права автоматом.
$temp = "";

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


log_write("======= Карта папок ====");
foreach ($map as $k=>$v) {
	$temp .= upload_folder_create($k, $temp,$ftp_stream);
}


$temp = "======= Карта папок ====<br>$temp======================";


$GLOBALS['_RESULT'] = array(
'stat' => $temp,
'susses' => "dirList_backup"
);


// фукция генерации списка папок, на которые необходимо проставить права.


function upload_folder_create($str,$mask,$ftp_stream){
	global $SysValue;
	
	$str= str_replace(" ","",$str);
	if($str == "/") {
		if(ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['chmod']." ".$SysValue['user_ftp']['dir']."/$folder")) $temp1 = " - права проставлены автоматически!";
		
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
				if(ftp_site($ftp_stream,"CHMOD ".$SysValue['user_ftp']['chmod']." ".$SysValue['user_ftp']['dir']."/$folder")) $temp .= " - права проставлены автоматически!";
				$temp .= "<br>";
				log_write($temp);
			}
			return $temp;
			
		}
		
	}
	return $temp;

}

?>