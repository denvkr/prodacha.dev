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
/////////////////////////////////////////////////////////////////
// проверяем права
if (!CheckedRules($UserStatus["upload"], 0) == 1) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">недостаточно прав</span>";
    $GLOBALS['_RESULT']['susses'] = "error";
    log_write("недостаточно прав");
    exit();
}


// проверяем сделан ли дамп базы
if (!file_exists("../../dumper/backup/upload_dump.sql.gz")) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не создан backup базы данных! Выполните Резервное копирование базы данных! (ШАГ 1)</span><br>==============<br>";
    $GLOBALS['_RESULT']['susses'] = "error_reload";
    log_write("Не создан backup базы данных! Выполните Резервное копирование базы данных! (ШАГ 1)");
    exit();
}
if (!copy("../../dumper/backup/upload_dump.sql.gz", "../../../../backup/temp/upload_backup.sql.gz")) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не удаётся скопировать phpshop/admpanel/dumper/backup/upload_backup.sql.gz</span>";
    log_write("Не удаётся скопировать phpshop/admpanel/dumper/backup/upload_backup.sql.gz");
    exit();
}
if (!unlink("../../dumper/backup/upload_dump.sql.gz")) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Не удаётся удалить phpshop/admpanel/dumper/backup/upload_backup.sql</span>";
    log_write("Не удаётся удалить phpshop/admpanel/dumper/backup/upload_backup.sql");
    exit();
}

// анализируем файл конфига апдейта
$map = @parse_ini_file("../../../../backup/temp/upd_conf.txt", 1);
if (empty($map)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:5 - не удаётся провести анализ конфига обновлений</span>";
    log_write("не удаётся провести анализ конфига обновлений");
    exit();
}


// формируем список папок, к которым нужно поменять права доступа. Если возможно меняем права автоматом.
$temp = "";

$ftp_stream = ftp_connect($SysValue['user_ftp']['host']);
ftp_login($ftp_stream, $SysValue['user_ftp']['login'], $SysValue['user_ftp']['password']);
ftp_pasv($ftp_stream, true);


if (!$ftp_stream = ftp_connect($SysValue['user_ftp']['host'])) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя");
    exit();
}

if (!ftp_login($ftp_stream, $SysValue['user_ftp']['login'], $SysValue['user_ftp']['password'])) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">ошибка авторизации с сервером пользователя</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("upload_modul_error:2 - ошибка авторизации с сервером пользователя");
    exit();
}

if (!ftp_pasv($ftp_stream, true)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">невозможно установить пассивный режим</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("невозможно установить пассивный режим");
    exit();
}

log_write("======= Карта папок ====");
foreach ($map as $k => $v) {
    $temp .= upload_folder_create($k, $temp, $ftp_stream);
}

$temp1 = "";
if (ftp_site($ftp_stream, "CHMOD " . $SysValue['user_ftp']['chmod'] . " " . $SysValue['user_ftp']['dir'] . "/phpshop/inc/config.ini"))
    $temp1 = " - права проставлены автоматически!";

log_write("/phpshop/inc/config.ini  - $temp1");
$temp .= "/phpshop/inc/config.ini  - $temp1<br>";


if (file_exists("../../../../backup/temp/base_update.sql.gz")) {

    $temp11 = "<span style=\"color:green\">Был скачан файл обновления базы данных!<br> Выполните ШАГ 2-1 Обновите базу данных (ШАГ 2-1)!<br> Проставьте необходимые права на нижеуказанные папки!</span><br>";
    log_write("Был скачан файл обновления базы данных! Выполните ШАГ 2-1 Обновите базу данных (ШАГ 2-1)! ");
    if (!copy("../../../../backup/temp/base_update.sql.gz", "../../dumper/backup/base_update.sql.gz")) {
        $GLOBALS['_RESULT']['stat'] = "Не возможно скопировать base_update.sql.gz";
        $GLOBALS['_RESULT']['susses'] = "error";
        log_write("Не возможно скопировать base_update.sql.gz");
        exit();
    }
    $flag1 = 1;
}



$temp = $temp11 . "======= Карта папок ====<br>$temp======================";

if ($flag1)
    $susses = "dirList_base";
else
    $susses = "dirList";

$GLOBALS['_RESULT'] = array(
    'stat' => $temp,
    'susses' => $susses
);

// фукция генерации списка папок, на которые необходимо проставить права.
/*
  function upload_folder_create($str,$mask){
  $str= str_replace(" ","",$str);
  if($str == "/") return "/  - на корневую папку сайта<br>";
  $str = explode("/",$str);
  for ($i=0;$i<count($str);$i++){
  $folder = $str[0];
  for ($c=1;$c<=$i;$c++)
  {
  $folder .= "/".$str[$c];

  }
  if (is_dir("../../../../$folder")) {
  if(!strstr($mask,$folder))	$temp .= $folder."<br>";
  }

  }
  return $temp;

  }
 */

function upload_folder_create($str, $mask, $ftp_stream) {
    global $SysValue;

    $str = str_replace(" ", "", $str);
    if ($str == "/") {
        if (ftp_site($ftp_stream, "CHMOD " . $SysValue['user_ftp']['chmod'] . " " . $SysValue['user_ftp']['dir'] . "/$folder"))
            $temp1 = " - права проставлены автоматически!";

        log_write("/  - на корневую папку сайта $temp1");
        return "/  - на корневую папку сайта $temp1<br><br>";
    }

    $str = explode("/", $str);


    for ($i = 0; $i < count($str); $i++) {
        $folder = $str[0];
        for ($c = 1; $c <= $i; $c++) {
            $folder .= "/" . $str[$c];
        }

        if (is_dir("../../../../$folder")) {
            if (!stristr($mask, $folder . " ") && $i == (count($str) - 1)) {
                $temp .= $folder;
                if (ftp_site($ftp_stream, "CHMOD " . $SysValue['user_ftp']['chmod'] . " " . $SysValue['user_ftp']['dir'] . "/$folder"))
                    $temp .= " - права проставлены автоматически!";
                $temp .= "<br>";
                log_write($temp);
            }
            //return $temp;
        }else {
            if (!stristr($mask, $folder . " ")) {
                $temp .= $folder;
                if (ftp_mkdir($ftp_stream, $SysValue['user_ftp']['dir'] . "/$folder")) {
                    $temp .= " - папка cоздана";
                    if (ftp_site($ftp_stream, "CHMOD " . $SysValue['user_ftp']['chmod'] . " " . $SysValue['user_ftp']['dir'] . "/$folder"))
                        $temp .= ", права проставлены автоматически!";
                }
                $temp .= "<br>";
                log_write($temp);
            }
            //return $temp;
        }
    }
    return $temp . "<br>";
}

?>