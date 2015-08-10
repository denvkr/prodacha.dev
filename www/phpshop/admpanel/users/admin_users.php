<?

function UsersJurnalBlack() {// Вывод журнала
    global $SysValue;


    $numRows = 0;
    $display = null;

    include("../geoip/geoip.inc");
    $gi = geoip_open("../geoip/GeoIP.dat", GEOIP_STANDARD);
    $sql = "select * from " . $SysValue['base']['table_name22'] . " order by id desc";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $ip = $row['ip'];
        $datas = $row['datas'];

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')">';

        $display.="
<td onclick=\"miniWin('users/adm_jurnal_listlID.php?id=$id',400,270)\" align=\"center\">
	$datas
	</td>
	<td onclick=\"miniWin('users/adm_jurnal_listlID.php?id=$id',400,270)\">
	$ip
	</td>
	<td onclick=\"miniWin('users/adm_jurnal_listlID.php?id=$id',400,270)\">
	" . geoip_country_name_by_addr($gi, $ip) . " (" . geoip_country_code_by_addr($gi, $ip) . ")
	</td>
    </tr>
	";
    }
    if ($i > 20)
        $razmer = "height:600;";
    $_Return = "
<div align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"30%\" id=pane align=center>IP</td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Страна</span></td>
</tr>
	" . $display . "
    </table>
	</td>
</tr>
    </table>
</div>
	";
    return $_Return;
}

function ChekBlacklist($n) {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name22'] . " where ip='$n'";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    return $num;
}

function UsersJurnal($pole1, $pole2) {// Вывод журнала
    global $SysValue;

    $numRows = 0;
    $display = null;

    if (empty($pole1))
        $pole1 = date("U") - 86400;
    else
        $pole1 = GetUnicTime($pole1) - 86400;
    if (empty($pole2))
        $pole2 = date("U");
    else
        $pole2 = GetUnicTime($pole2) + 86400;

    include("../geoip/geoip.inc");
    $gi = geoip_open("../geoip/GeoIP.dat", GEOIP_STANDARD);
    $sql = "select * from " . $SysValue['base']['table_name10'] . " where datas<'$pole2' and datas>'$pole1' order by id desc";
 
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $user = $row['user'];
        $datas = $row['datas'];
        $ip = $row['ip'];
        if (($row['flag']) == "0") {
            $checked = "<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"Авторизаван\">";
        } else {
            $checked = "<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Не авторизован\">";
        };
        $black = ChekBlacklist($row['ip']);
        if ($black > 0)
            $blackchecked = "<img src=img/i_spam_filter_med_small.gif  width=\"15\" height=\"15\" alt=\"Черный список\">"; else
            $blackchecked = "";


        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')">';


        $display.="<td align=center class=forma onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">$checked  $blackchecked
	</td>
	<td onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">
	$user
	</td>
	<td  onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">
	" . dataV($datas) . "
	</td>
	<td onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">
	$ip
	</td>
	<td  onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">" . geoip_country_name_by_addr($gi, $ip) . " (" . geoip_country_code_by_addr($gi, $ip) . ")</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 20)
        $razmer = "height:600;";
    $_Return = "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Вход</span></td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Пользователь</span></td>
    <td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"20%\" id=pane align=center>IP</td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Страна</span></td>
</tr>
	" . $display . "
    </table>
	</td>
</tr>
    </table>
</div>
	";
    return $_Return;
}

function GetLastEnter($n) {
    global $SysValue;
    $sql = "select datas from " . $SysValue['base']['table_name10'] . " where user='$n' order by id desc LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $num = mysql_numrows($result);
    if ($num == 0)
        return "-";
    $datas = $row['datas'];
    $data = dataV($datas, "shot");
    return $data;
}

function Users() {
    global $table_name19;

    $numRows = 0;
    $display = null;

    $sql = "select * from $table_name19 order by status";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $id = $row['id'];
        $login = $row['login'];
        if (($row['enabled']) == "1") {
            $checked = "<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"В наличии\">";
        } else {
            $checked = "<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Отсутствует\">";
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')">';

        $display.="
    <td style=\"padding:3\" align=center onclick=\"miniWin('users/adm_userID.php?id=$id',550,500)\">
	" . $checked . "
	</td>
	<td onclick=\"miniWin('users/adm_userID.php?id=$id',550,500)\">
	" . $row['mail'] . "
	</td>
	<td onclick=\"miniWin('users/adm_userID.php?id=$id',550,500)\">
	$login
	</td>
	<td onclick=\"miniWin('users/adm_userID.php?id=$id',550,500)\">
	" . GetLastEnter($login) . "
	</td>
    </tr>
	";
        @$i++;
    }
    if ($i > 20)
        $razmer = "height:600;";
    $_Return = "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;" . @$razmer . ";overflow:auto\"> 
<table width=\"70%\"  cellpadding=\"0\" cellspacing=\"0\" >
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Статус</span></td>
    <td width=\"20%\" id=pane align=center>E-mail</td>
	<td width=\"25%\" id=pane align=center><span name=txtLang id=txtLang>Имя</span></td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Вход</span></td>
</tr>
	" . $display . "
    </table>
	</td>
</tr>
    </table>

<div align=\"right\" style=\"padding:10;width:70%\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('users/adm_users_new.php',500,500)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
	";
    return $_Return;
}

?>
