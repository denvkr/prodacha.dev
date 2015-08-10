<?php

require_once "../../../../phpshop/lib/Subsys/JsHttpRequest/Php.php";
// Создаем главный объект библиотеки.
// Указываем кодировку страницы (обязательно!).
$JsHttpRequest  = new Subsys_JsHttpRequest_Php("windows-1251");


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

$map1 = @parse_ini_file("../../../../backup/upd_black_list.txt", 1);



//$upload_type = "script";
$ftp_host = base64_decode($_REQUEST['ftp_host']);
$ftp_login = base64_decode($_REQUEST['ftp_login']);
$ftp_password = base64_decode($_REQUEST['ftp_password']);
$ftp_password = substr($ftp_password, 6, strlen($ftp_password));
$ftp_folder = $_REQUEST['ftp_folder'];




////// соединение с фтп сервера /////////////////////////////////////////
if (!$ftp_stream = ftp_connect($ftp_host)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:1 - не удаётся соедиться с сервером</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error";
    log_write("upload_modul_error:1 - не удаётся соедиться с сервером");
    exit();
}

if (!ftp_login($ftp_stream, $ftp_login, $ftp_password)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:2 - ошибка авторизации</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error";
    log_write("upload_modul_error:2 - ошибка авторизации");
    exit();
}

if (!ftp_pasv($ftp_stream, true)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">невозможно установить пассивный режим</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error";
    log_write("невозможно установить пассивный режим");
    exit();
}
////////////////////////////////////////////
////////////соединение с фтп пользователя /////////////////////////
if (!$ftp_stream_user = ftp_connect($SysValue['user_ftp']['host'])) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("upload_modul_error:1 - не удаётся открыть фтп соединение с сервером пользователя");
    exit();
}

if (!ftp_login($ftp_stream_user, $SysValue['user_ftp']['login'], $SysValue['user_ftp']['password'])) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">ошибка авторизации с сервером пользователя</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("upload_modul_error:2 - ошибка авторизации с сервером пользователя");
    exit();
}

if (!ftp_pasv($ftp_stream_user, true)) {
    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">невозможно установить пассивный режим</span>";
    $GLOBALS['_RESULT']['susses'] = "connect_error_user";
    log_write("невозможно установить пассивный режим");
    exit();
}
/////////////////////////////////////////////////////




$sql = "select * from " . $SysValue['base']['table_name46'] . " WHERE backup = '1' and backup_flag != '2' LIMIT 1";
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);

    //запрещаем копирование config.ini
    if ("/$row[dir]/$row[name]" != "/phpshop/inc/config.ini") {
        @chmod("../../../../" . $row['dir'], 0777);
        if (!ftp_chdir($ftp_stream, $ftp_folder . "/update/" . $row['dir'])) {
            $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:7 - некорректная папка $row[dir] на сервере<br>Ошибка карты обновления, обратитесь в техподдержку.</span><br><br>";
            $GLOBALS['_RESULT']['susses'] = "folder_error";
            log_write("некорректная папка $row[dir] на сервере. Ошибка карты обновления, обратитесь в техподдержку.");
            @chmod("../../../../" . $row['dir'], 0755);
            exit();
        }


        if (!empty($map1) && (stristr($map1[$row['dir']]['files'], $row[name]) != false)) {

            //if ($row['backup_flag'] == 0)
            $sql = "DELETE FROM " . $SysValue['base']['table_name46'] . " WHERE id = $row[id]";
            //else
            //	$sql = "UPDATE ".$SysValue['base']['table_name46']." SET backup_flag = '2' WHERE id = $row[id]";

            mysql_query($sql);

            @chmod("../../../../" . $row['dir'], 0755);

            $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">файл $row[dir]/$row[name] Запрещен для обновления пользователем...</span>";
            $GLOBALS['_RESULT']['susses'] = "copy";
            log_write("файл $row[dir]/$row[name] Запрещен для обновления пользователем...");
        } else {


            if (!ftp_get($ftp_stream, "../../../../backup/cache/$row[name]", $row['name'], FTP_BINARY)) {

                $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">upload_modul_error:6 - ошибка загрузки файла с сервера. <br> Файл либо не существует, <br>либо не проставлены права 777 на папку backup/cache.<br>Ошибка карты обновления, обратитесь в техподдержку.</span><br>";
                $GLOBALS['_RESULT']['susses'] = "load_error";
                log_write("ошибка загрузки файла с сервера. Файл либо не существует, либо не проставлены права 777 на папку backup/cache, либо ошибка карты обновления, обратитесь в техподдержку.");
                @chmod("../../../../" . $row['dir'], 0755);
                exit();
            } else {

                if ($row['backup_flag'] == 0)
                    $sql = "DELETE FROM " . $SysValue['base']['table_name46'] . " WHERE id = $row[id]";
                else
                    $sql = "UPDATE " . $SysValue['base']['table_name46'] . " SET backup_flag = '2' WHERE id = $row[id]";

                mysql_query($sql);

                @chmod("../../../../" . $row['dir'], 0755);

                if (!ftp_put($ftp_stream_user, $SysValue['user_ftp']['dir'] . "/$row[dir]/$row[name]", "../../../../backup/cache/$row[name]", FTP_BINARY)) {
                    $GLOBALS['_RESULT']['stat'] = "<span style=\"color:red\">Ошибка копирования файла $row[name] в папку $row[dir] из временной папки!</span><br>";
                    $GLOBALS['_RESULT']['susses'] = "load_error";
                    log_write("Ошибка копирования файла $row[name] в папку $row[dir] из временной папки!");
                    exit();
                }
                unlink("../../../../backup/cache/$row[name]");

                $GLOBALS['_RESULT']['stat'] = "файл загружен: $row[dir]/$row[name]";
                $GLOBALS['_RESULT']['susses'] = "copy";
                log_write("файл загружен: $row[dir]/$row[name]");
            }
        }
    } else {
        $sql = "UPDATE " . $SysValue['base']['table_name46'] . " SET backup_flag = '2' WHERE id = $row[id]";
        mysql_query($sql);
        @chmod("../../../../" . $row['dir'], 0755);
        $GLOBALS['_RESULT']['stat'] = "";
        $GLOBALS['_RESULT']['susses'] = "copy";
    }
} else {


    $GLOBALS['_RESULT']['stat'] = " обновление файлов завершено!";
    $GLOBALS['_RESULT']['susses'] = "continue";
    log_write("обновление файлов завершено!");
}
?>