<?php

require_once "../../../../phpshop/lib/Subsys/JsHttpRequest/Php.php";
// Создаем главный объект библиотеки.
// Указываем кодировку страницы (обязательно!).
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");


require("../../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
//////////////////////////////////////////////////////////
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../../enter_to_admin.php");

// Языки
$GetSystems = GetSystems();
$option = unserialize($GetSystems['admoption']);
$Lang = $option['lang'];
require("../../language/" . $Lang . "/language.php");
require("log_save.php");
log_clear();
/////////////////////////////////////////////////////////////////
// проверяем права
if (!CheckedRules($UserStatus["upload"], 0) == 1) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">недостаточно прав</span>";
    $GLOBALS['_RESULT']['susses'] = "error";
    log_write("недостаточно прав");
    exit();
}
//$upload_type = "script";
$ftp_host = base64_decode($_REQUEST['ftp_host']);
$ftp_login = base64_decode($_REQUEST['ftp_login']);
$ftp_password = base64_decode($_REQUEST['ftp_password']);
$ftp_password = substr($ftp_password, 6, strlen($ftp_password));
$ftp_folder = $_REQUEST['ftp_folder'];


// проверяем сделан ли дамп базы
if (!file_exists("../../dumper/backup/upload_dump.sql.gz")) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не создан backup базы данных! Выполните Резервное копирование базы данных!</span><br>==============<br>";
    $GLOBALS['_RESULT']['susses'] = "error_reload";
    log_write("Не создан backup базы данных! Выполните Резервное копирование базы данных!");
    exit();
}


if (!$ftp_stream = ftp_connect($ftp_host)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:1 - не удаётся соедиться с сервером</span>";
    log_write("не удаётся соедиться с сервером");
    exit();
}

if (!ftp_login($ftp_stream, $ftp_login, $ftp_password)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:2 - ошибка авторизации</span>";
    log_write("ошибка авторизации");
    exit();
}

if (!ftp_pasv($ftp_stream, true)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">невозможно установить пассивный режим</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error";
    log_write("невозможно установить пассивный режим");
    exit();
}

if (!ftp_chdir($ftp_stream, $ftp_folder)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:4 - не удаётся найти указанную версию обновления</span>";
    log_write("не удаётся найти указанную версию обновления");
    exit();
}


if (!ftp_get($ftp_stream, '../../../../backup/temp/upd_conf.txt', 'upd_conf.txt', FTP_BINARY)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:3 - ошибка загрузки файла конфигураций обновления</span>";
    log_write("ошибка загрузки файла конфигураций обновления");
    exit();
}

if (ftp_get($ftp_stream, '../../../../backup/temp/base_update.sql.gz', 'base_update.sql.gz', FTP_BINARY)) {
    $temp = "Загружен файл обновления базы данных...";
    log_write("Загружен файл обновления базы данных...");
}


// обновляем конфиг, если скачали config_update.txt
if (ftp_get($ftp_stream, '../../../../backup/temp/config_update.txt', 'config_update.txt', FTP_BINARY)) {
    $temp .= "<br>Загружен файл обновления config.ini ...";
    log_write("Загружен файл обновления config.ini");
    /*
      if (!$ftp_stream = ftp_connect($SysValue['user_ftp']['host'])){
      $GLOBALS['_RESULT']['stat'] = "не удаётся соедиться с вашим FTP сервером";
      $GLOBALS['_RESULT']['susses']=  "connect_error";
      exit();
      }

      if (!ftp_login($ftp_stream,$SysValue['user_ftp']['login'],$SysValue['user_ftp']['password'])){
      $GLOBALS['_RESULT']['stat'] = "ошибка авторизации на вашем FTP сервере";
      $GLOBALS['_RESULT']['susses']=  "connect_error";
      exit();
      }

      if (!ftp_pasv($ftp_stream,true)){
      $GLOBALS['_RESULT']['stat'] = "невозможно установить пассивный режим на вашем FTP сервере";
      $GLOBALS['_RESULT']['susses']=  "connect_error";
      exit();
      }
     */
}




$temp = " $temp  Загрузка файла конфигураций обновления выполена! ";
log_write("Загрузка файла конфигураций обновления выполена!");
$GLOBALS['_RESULT'] = array(
    'stat' => $temp,
    'susses' => "susses",
);
?>