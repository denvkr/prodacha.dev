<?php

// Настройка уровня оповещения отладчика
if (function_exists('error_reporting')) {
    error_reporting('E_ALL & ~E_NOTICE & ~E_DEPRECATED');
}

// Снимаем ограничения на выполнение
if ($SysValue['my']['time_limit_enabled'] == "true") {
    $is_safe_mode = @ini_get('safe_mode') == '1' ? 1 : 0;
    if (!$is_safe_mode)
        @set_time_limit(TIME_LIMIT);
}


// Тип оконного менеджера
$_COOKIE['winOpenType'] = 'default';

// класс проверки пользователя
class UserChek {

    var $logPHPSHOP;
    var $pasPHPSHOP;
    var $idPHPSHOP;
    var $statusPHPSHOP;
    var $mailPHPSHOP;
    var $OkFlag = 0;
    var $DIR = "";

    function ChekBase($table_name) {
        $sql = "select * from " . $table_name . " where enabled='1'";
        @$result = mysql_query(@$sql);
        while (@$row = mysql_fetch_array(@$result)) {
            if ($this->logPHPSHOP == $row['login']) {
                if ($this->pasPHPSHOP == $row['password']) {
                    $this->OkFlag = 1;
                    $this->idPHPSHOP = $row['id'];
                    $this->statusPHPSHOP = unserialize($row['status']);
                    $this->mailPHPSHOP = $row['mail'];
                }
            }
        }
    }

    function BadUser() {
        if ($this->OkFlag == 0) {
            header("Location: " . $this->DIR . "/phpshop/admpanel/");
            exit("Login Error");
        }
    }

    function UserChek($logPHPSHOP, $pasPHPSHOP, $table_name, $DIR) {
        $this->logPHPSHOP = $logPHPSHOP;
        $this->pasPHPSHOP = $pasPHPSHOP;
        $this->DIR = $DIR;
        $this->ChekBase($table_name);
        $this->BadUser();
    }

    function BadUserForma() {
        $disp = '
	  <table width="100%" height="100%" style="Z-INDEX:2;">
<tr>
	<td valign="middle" align="center">
		<div style="width:400px;height:100px;BACKGROUND: #C0D2EC;padding:10px;border: solid;border-width: 1px; border-color:#4D88C8;FILTER: alpha(opacity=80);" align="left">
<table width="100%" height="100%">
<tr>
	<td width="35" vAlign=center ><IMG 
            hspace=0 src="img/i_support_med[1].gif" align="absmiddle" 
            border=0 ></td>
	<td ><b>Внимание, ' . $this->logPHPSHOP . '!</b><br>У Вас недостаточно прав для выполнения данной операции.<br>Обратитесь к администратору сервера.</td>
</tr>
</table>

		</div>
</td>
</tr>
</table>
	  
	  ';
        return $disp;
    }

    function BadUserFormaWindow() {
        echo'
	  <script>
	  if(confirm("Внимание ' . $this->logPHPSHOP . '!\nУ Вас недостаточно прав для выполнения данной операции.\nЗакрыть это окно?"))
	  window.close();
	  </script>';
    }

}

session_start();
@mysql_query("SET NAMES 'cp1251'");


$UserChek = new UserChek($_SESSION['logPHPSHOP'], $_SESSION['pasPHPSHOP'], $GLOBALS['SysValue']['base']['table_name19'], $GLOBALS['SysValue']['dir']['dir']);
$UserStatus = $UserChek->statusPHPSHOP;

// Поддержка прав модулей.
if (@$_classPath == "../../../" and CheckedRules($UserStatus["option"], 1) == 1)
    $UserChek->statusPHPSHOP = 0;

// Проверка прав
function CheckedRules($a, $b) {
    $array = explode("-", $a);
    return $array[$b];
}

// Secure Fix 6.5
function RequestSearch($search) {
    $pathinfo = pathinfo($_SERVER['PHP_SELF']);
    $f = $pathinfo['basename'];
    if ($f != "adm_sql.php" and $f != "adm_sql_file.php" and $f != "action.php" and $f != "adm_upload.php") {
        $com = array("union", "document.cookie");
        $mes = '
<html>
<head>
	<title>Secure Fix 6.0</title>
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
</head>

<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Безопасноть под угрозой</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_domainmanager_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="0"  cellspacing="7" width="100%" height="100%">
<tr>
	<td>
	
	

<h4 style="color:red">Внимание!!!</h4><br>Работа скрипта ' . $_SERVER['PHP_SELF'] . ' прервана из-за использования внутренней команды';
        $mes2 = "<br>Удалите все вхождения этой команды в водимой информации.";
        foreach ($com as $v)
            if (@preg_match("/" . $v . "/i", $search)) {
                $search = preg_replace("/$v/i", "!!!$v!!!", $search);
                exit($mes . " " . strtoupper((string)$v) . $mes2 . "<br><br><br><textarea style='width: 100%;height:50%'>" . substr($search, 0, 11) . "</textarea><p>Команда к тексте выделена знаками !!! с обеих сторон</p>
<hr>
<div align=right>
<input type=button value=Вернуться onclick=\"history.back(1)\">
<input type=button value=Закрыть onclick=\"self.close()\">
</div>
</td>
</tr>
</table>
");
            }
    }
}

foreach ($_REQUEST as $val)
    RequestSearch($val);
?>