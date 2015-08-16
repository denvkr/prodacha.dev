<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems = GetSystems();
$option = unserialize($GetSystems['admoption']);
$Lang = $option['lang'];
require("../language/" . $Lang . "/language.php");

// Данные по валюте
function GetValuta($id) {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name24'] . " where id=" . intval($id);
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row;
}

function Ras_data_content($string) {// Состав рассылки
    global $table_name8, $GetSystems, $SysValue;
    $sql = "select * from $table_name8  where id='0' $string";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $data = $row['datas'];
        $zag = $row['zag'];
        $kratko = strip_tags($row['kratko']);
        $podrob = $row['podrob'];
        if ($podrob != "") {
            $link = "<a href=\"http://" . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . "/news/ID_" . $id . ".html\">далее &raquo;</a>";
        } else {
            $link = "";
        }
        @$content.="
  <p>
<table>
<tr>
  <td class=date>$data</td>
  <td><strong>$zag</strong></td>
</tr>
</table>
$kratko
<div align=\"right\">" . $link . "</div>
</p>";
    }

    $disp = '
<html>
<head>
<style>
body, td{
font-family: Tahoma;
font-size: 11px;
background-color: #FFFFFF;
}
H1{
   FONT-SIZE: 15px;
   color: #0068B9;
}

.date{
   background-color:#1982C6;
   color: white;
   padding:5px
}


a{
  color: #0068B9;
}

</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
  <td>
  <h1>Здравствуйте, представляем новости с сайта "' . $GetSystems['name'] . '"</h1>
  </td>
  <td align="right"><a href="http://' . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . '" target="_blank" title="' . $SERVER_NAME . '"><img src="http://' . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . $GetSystems['logo'] . '" alt="' . $GetSystems['name'] . '"  border="0"></a></td>
</tr>
<tr>
   <td colspan="2" style="background-color:#1982C6;" height="3"></td>
</tr>
</table>

' . @$content . '


<em>С уважением,<br>
Коллектив ' . $GetSystems['company'] . '</em>
<br><br><br>
</body>
</html>
';
    return @$disp;
}

function Ras_data_mail($content, &$num, $id = "") {// По мылу марш...
    global $table_name27, $GetSystems;
    $codepage = "windows-1251";
    $header = "MIME-Version: 1.0\n";
    $header .= "From: " . $GetSystems['name'] . " <" . $GetSystems['adminmail2'] . ">\n";
    $header .= "Content-Type: text/html; charset=$codepage\n";
    $header .= "X-Mailer: PHP/";
    $zag = "Анонсы новостей " . $GetSystems['name'];

    if (empty($id))
        $sql = "select mail from $table_name27 where enabled='1'";
    else
        $sql = "select mail from $table_name27 where id=" . intval($id);

    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $mail_to = $row['mail'];
        mail($mail_to, $zag, $content, $header);
        @$num++;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Действие</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>


        <LINK href="../skins/<?= $_SESSION['theme'] ?>/dateselector.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="../java/popup_lib.js"></SCRIPT>
        <SCRIPT language="JavaScript" src="../java/dateselector.js"></SCRIPT>

        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 300, 220);
        </script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?= $SysValue['lang']['lang_enabled'] ?>);
                preloader(0)">
        <table id="loader">
            <tr>
                <td valign="middle" align="center">
                    <div id="loadmes" onclick="preloader(0)">
                        <table width="100%" height="100%">
                            <tr>
                                <td id="loadimg"></td>
                                <td ><b><?= $SysValue['Lang']['System']['loading'] ?></b><br><?= $SysValue['Lang']['System']['loading2'] ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>
        <?
        $Name = $SysValue['Lang']['Window'];

        function GetInfoUsers($n) {
            global $SysValue;
            $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=".intval($n);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            return $row;
        }

        function GetInfoProduct($n) {
            global $SysValue;
            $sql = "select name, sklad, uid, price, datas, baseinputvaluta  from " . $SysValue['base']['table_name2'] . " where id=".intval($n);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            return $row;
        }

        function dispPage() { // вывод статей по теме
            global $SysValue;
            $array = explode(",", $array);
            $sql = "select * from " . $SysValue['base']['table_name11'] . " order by num";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $link = $row['link'];
                $name = substr($row['name'], 0, 200);
                @$dis.="<option value=" . $link . ">" . $name . "</option>\n";
            }
            @$disp = "
<select name=page_new[] size=5 style=\"width: 280;\" multiple>

$dis
</select>
";
            return @$disp;
        }

        function dispValue($n) { // вывод характеристик
            global $SysValue;
            $dis=null;
            $sql = "select * from " . $SysValue['base']['table_name21'] . " where category=".intval($n)." order by num";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = substr($row['name'], 0, 35);
                $dis.="<option value=" . $id . ">" . $name . "</option>\n";
            }
            $disp = "
<select name=vendor_new[" . $n . "][] size=1 style=\"width: 250;\">
<option value=''>Нет данных</option>
$dis
</select>
";
            return @$disp;
        }

        function DispCatSort($category, &$h) {
            global $SysValue;
            $disp=null;
            $sql = "select sort from " . $SysValue['base']['table_name'] . " where id=".intval($category);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $sort = unserialize($row["sort"]);
            if (is_array($sort))
                foreach ($sort as $v) {
                    $sql = "select * from " . $SysValue['base']['table_name20'] . " where id=$v order by name";
                    $result = mysql_query($sql);
                    while (@$row = mysql_fetch_array($result)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $disp.= '
<div style="padding-top:7">
<FIELDSET id=fldLayout >
<LEGEND><input type="checkbox" name="vendor_cat[' . $id . ']" value="1"> ' . $name . '</LEGEND>
<div style="padding:10">
' . dispValue($id) . '
</div>
</FIELDSET>
</div>
';
                        @$n++;
                    }
                }
            $h = $h + (55 * $n);
            return $disp;
        }

//Конец ФУНКЦИЙ

        if ($do == 14) {
            echo'<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  <span name=txtLang id=txtLang><u>П</u>еренести в каталог</span><br>
  <input type=text id="myName"  style="width: 230" value="">
<input type="hidden" name="category_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'../product/adm_cat.php\',300,400,300,200)"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 37) { // Поменять статус заказов
            $sql = "select * from " . $SysValue['base']['table_name32'];
            $result = mysql_query($sql);
            while (@$row = mysql_fetch_array(@$result)) {
                if ($n == $row['id'])
                    $sel2 = "selected";
                else
                    $sel2 = "";

                if ($row['sklad_action'] == 1)
                    $sel1 = " (списать/открыть файл)";
                else
                    $sel1 = "";

                @$dis.="<option value='" . $row['id'] . "' $sel2>" . $row['name'] . $sel1 . "</option>";
            }

            echo'<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  Статус:<br>
<select name=statusi_new>
<option value=0>Новый заказ</option>
' . @$dis . '
</select>
  <br>
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        }
        elseif ($do == "rss4") { // Поменять дату работы RSS каналов
            echo'<form name="product_edit" id="product_edit"  method="post">
<script>
DoResize(' . $GetSystems['width_icon'] . ',400,300);
</script>
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  <FIELDSET style="height:30px">
<LEGEND>Срок работы (dd-mm-yyyy)</LEGEND>
<div style="padding:10">
С&nbsp;&nbsp;
<input type="text" name="start_date_new" id="start_date_new"  maxlength="10" value="' . date("d-m-Y") . '" style="width:80px;">
<IMG onclick="popUpCalendar(this, product_edit.start_date_new, \'dd-mm-yyyy\');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
по
<input type="text" name="end_date_new"  maxlength="10" value="' . date("d-m-Y") . '" style="width:80px;" >
<IMG onclick="popUpCalendar(this, product_edit.end_date_new, \'dd-mm-yyyy\');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
</div>
</FIELDSET>
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 34) { // Страницы переснести в каталог
            echo'<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  <span name=txtLang id=txtLang><u>П</u>еренести в каталог</span><br>
  <input type=text id="myName"  style="width: 230" value="">
<input type="hidden" name="category_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'../page/adm_cat.php\',300,400,300,200)"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 15) {
            echo'<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  <span name=txtLang id=txtLang><u>Т</u>овары для перехвата поиска</span><br>
  <input type=text name="uid_new"  style="width: 280"><br>
  <span name=txtLang id=txtLang>* Введите идентификаторы (ID) товаров через запятую</span> (100,101). 
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 35) { // Рекомендованные товары для страниц
            echo'
<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  <span name=txtLang id=txtLang><u>Т</u>овары для рекомендаций</span><br>
  <input type=text name="uid_new"  style="width: 280"><br>
  <span name=txtLang id=txtLang>* Введите идентификаторы (ID) товаров через запятую</span> (100,101). Данные будут полностью заменены.
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr>
  <td align="right" style="padding:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 222) { //Новое. Рассылка по выбранным 
            $systems = GetSystems();

            echo'
<script>
DoResize(' . $GetSystems['width_icon'] . ',600,450);
function enable_div() {
if (document.getElementById(\'nid_new\').value=="0") {
//alert("we");
  document.getElementById(\'Message_new\').disabled=false;
  document.getElementById(\'Message_new\').value="";

} else {
//alert("we");
  document.getElementById("Message_new").disabled=true;
  document.getElementById("Message_new").value="Будет отправлен текст выбранной вами новости!";

}
}


</script>
<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Введите сообщение для рассылки</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<table cellpadding="0"  cellspacing="7" style="width: 100%;">
<tr><td>
  <span name=txtLang id=txtLang><u>Т</u>екст сообщения для отправки:</span><br>
  <input type="TEXT" id="Message_new" name="Message_new" style="width:100%; height:200px;">
  </td></tr><tr><td>
  <span name=txtLang id=txtLang>Или выберите новость для отправки:</span><br>
  <select id="nid_new" name="nid_new" onChange="enable_div(); ">
  <option value="0" selected>[Разослать вышестоящее сообщение]</option>';
            $sql = 'select id,zag,datas from ' . $SysValue['base']['table_name8'] . ' ORDER by id LIMIT 25';
            $result = mysql_query($sql);
            $lvl++;
            while ($row = mysql_fetch_array($result)) {
                $nid = $row['id'];
                $nzag = $row['zag'];
                $ndate = $row['datas'];
                echo '<option value="' . $nid . '">' . $nzag . ' (' . $ndate . ')</option>';
            }

            echo'</select></td></tr></table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="40" >
<tr><td align="right" style="padding:10">
  <input type=submit value=ОК class=but name=productSAVE>
  <input type=submit value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td></tr></table></form>';
        } elseif ($do == 23) {
            echo'<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<table cellpadding="0"  cellspacing="7">
<tr>
  <td>
  ' . dispPage() . '
  </td>
</tr></table>

<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="30" >
<tr>
  <td align="right" style="padding-right:10">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } elseif ($do == 24) {
            $h = 220;
            echo'
<form  method="post">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_documentation_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<div style="height:350;overflow:auto"> 
<table width="100%">
<tr>
  <td>
  ' . DispCatSort($catal, $h) . '
  </td>
</tr>
</table>
</div>
<script>
DoResize(' . $GetSystems['width_icon'] . ',300,500);
</script>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="30" >
<tr>
  <td align="right" style="padding-right:10">
  <input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
  <input type=hidden name=IDS value="' . $ids . '">
  <input type=hidden name=DO value="' . $do . '">
  </td>
</tr>
</table>
</form>
';
        } else {
            echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
  <td style=\"padding:10\">
  <b><span name=txtLang id=txtLang>Действие</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align=\"right\">
  <img src=\"../img/i_documentation_med[1].gif\" border=\"0\" hspace=\"10\">
  </td>
</tr>
</table>
<br>
<form action=\"$PHP_SELF\" method=\"post\">
  <div style=\"padding:10\" align=\"center\">
  ";
            if ($action == "wait")
                echo $SysValue['Lang']['System']['load'] . "
  <div align=center style=\"padding: 5px\">
  <img src=\"../img/loader2.gif\"  width=220 height=19 border=0>
  </div>
  ";
            else
                echo "
  <span name=txtLang id=txtLang>Вы уверены, что хотите</span> <b>" . $SysValue['Lang']['Window'][$do] . "</b>? <br>
";
            echo "
  </div>
<hr>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
  <td align=\"right\" style=\"padding:10\">
<input type=submit value=ОК class=but name=productSAVE>
  <input type=submit name=btnLang value=Отмена class=but onClick=\"return onCancel();\">
  <input type=hidden name=IDS value='$ids'>
  <input type=hidden name=DO value='$do'>
  <input type=hidden name=action value='wait'>
  </td>
</tr>
</table>
</form>
";
        }
        if (CheckedRules($UserStatus["cat_prod"], 1) == 1) {
            if (isset($productSAVE)) {// Запись редактирования
                $IdsArray = split(",", $IDS);

                if ($DO == 1) {// Удалить товар
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from $table_name2 
    where id='0' $string;";
                    $pageReload = "cat_prod";
                } elseif ($DO == 2) {// В спецпредложенние
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
spec='1'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 3) {// Из спецпредложения
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
spec='0'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 4) {// Из продажи
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
enabled='0'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 5) {// в продажу
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
enabled='1'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 47) {// Разослать пользователям
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";



                    $num = 0;
                    $content = Ras_data_content($string);
                    Ras_data_mail($content, $num);


                    $pageReload = "news";
                } elseif ($DO == 48) {// отключить отзывы
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name7'] . "
SET
flag='0'
where id='0' $string";
                    $pageReload = "gbook";
                } elseif ($DO == 50) {// включить отзывы
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name7'] . "
SET
flag='1'
where id='0' $string";
                    $pageReload = "gbook";
                } elseif ($DO == 49) {// удалить отзывы
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";


                    $sql = "delete from " . $SysValue['base']['table_name7'] . " 
    where id='0' $string;";

                    $pageReload = "gbook";
                } elseif ($DO == 6) {// из прайса
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
yml='0'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 7) {// в прайс
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
yml='1'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 8) {// в CSV
                    $sql = "--";
                    echo"
<script>
window.open('../export/adm_csv.php?IDS=" . $IDS . "');
</script>
";
                    $pageReload = "cat_prod";
                } elseif ($DO == 10) {// в новинки
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
newtip='1'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 11) {// из новинок
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
newtip='0'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 12) {// обнулить опрос
                    $sql = "UPDATE $table_name5
SET
total='0'
where category='$IDS'";
                    $pageReload = "opros";
                } elseif ($DO == 14 and !empty($category_new)) {// перенести в каталог
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE $table_name2
SET
category='$category_new'
where id='0' $string";
                    $pageReload = "cat_prod";
                } elseif ($DO == 34 and !empty($category_new)) {// перенести в каталог
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
category='$category_new'
where id='0' $string";
                    $pageReload = "page";
                } elseif ($DO == 37) {// поменять статусы заказов
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name1'] . "
SET
statusi='$statusi_new'
where id='0' $string";
                    $pageReload = "orders";
                } elseif ($DO == 41) {// удалить комментарии
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name36'] . "
    where id='0' $string";
                    $pageReload = "comment";
                } elseif ($DO == 35 and !empty($uid_new)) {// рекомендованные товары для страниц
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
odnotip='$uid_new'
where id='0' $string";
                    $pageReload = "page";
                } elseif ($DO == 15) {// перенести слова в базу поиска
                    foreach ($IdsArray as $v)
                        @$string.="i" . $v . "i";
                    $sql = "INSERT INTO " . $SysValue['base']['table_name26'] . " 
VALUES ('','$string','$uid_new','1')";
                    $pageReload = "search_pre";
                } elseif ($DO == 16) {// Удалить из журнала
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name18'] . "
    where id='0' $string";
                    $pageReload = "search_jurnal";
                } elseif ($DO == 17) {// Удалить из базы поиска
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name26'] . "
    where id='0' $string";
                    $pageReload = "search_pre";
                } elseif ($DO == 18) {// Не учитывать для поиска
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";
                    $pageReload = "search_pre";
                    $sql = "UPDATE " . $SysValue['base']['table_name26'] . "
SET
enabled='0'
where id='0' $string";
                    $pageReload = "search_pre";
                } elseif ($DO == 19) {// Разблокировать для поиска
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name26'] . "
SET
enabled='1'
where id='0' $string";
                    $pageReload = "search_pre";
                } elseif ($DO == 20) {// Заблокировать пользователей
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name27'] . "
SET
enabled='0'
where id='0' $string";
                    $pageReload = "shopusers";
                } elseif ($DO == 21) {// Разблокировать пользователей
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name27'] . "
SET
enabled='1'
where id='0' $string";
                    $pageReload = "shopusers";

/////////////////////////////////////////////////////////////////////НОВОЕ///////////////////
                } elseif ($DO == 222) {// Разослать сообщения

                    function Systems() {// вывод настроек
                        global $SysValue;
                        $sql = "select * from " . $SysValue['base']['table_name3'];
                        $result = mysql_query($sql);
                        $row = mysql_fetch_array($result);
                        return $row;
                    }

                    $systems = Systems();

                    if ($nid_new == 0) { //Если групповая рассылка сообщений, действуем по стд. алг-му отправки сообщения админом
                        foreach ($IdsArray as $v) {//Перебор пользователей, отправка каждому сообещния и фиксация этого в базе
                            $UID = $v;
                            $DateTime_new = date("Y-m-d H:i:s");
                            $Subject_new = 'Сообщение администратора!';
//$Message_new=$Message_new;
                            $sql = 'INSERT INTO ' . $SysValue['base']['table_name37'] . '
VALUES ("",0,' . $UID . ',' . $_SESSION['idPHPSHOP'] . ',\'' . $DateTime_new . '\',\'' . $Subject_new . '\',\'' . $Message_new . '\',1)';
                            $result = mysql_query($sql) or @die("" . mysql_error());

//Отправка сообщения пользователю
                            $UsersId = $UID;
                            $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=".intval($UsersId)." LIMIT 0, 1";
                            $result = mysql_query($sql);
                            $row = mysql_fetch_array($result);
                            $id = $row['id'];
                            $login = $row['login'];
                            $password = $row['password'];
                            $status = $row['status'];
                            $mail = $row['mail'];
                            $name = $row['name'];
                            $company = $row['company'];
                            $inn = $row['inn'];
                            $tel = $row['tel'];
                            $adres = $row['adres'];



                            $codepage = "windows-1251";
                            $header_adm = "MIME-Version: 1.0\n";
                            $header_adm .= "From:   <" . $systems['adminmail2'] . ">\n";
                            $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
                            $header_adm .= "X-Mailer: PHP/";
                            $zag_adm = $systems['name'] . " -  Сообщение от Администратора";
                            $content_adm = "
Доброго времени!
--------------------------------------------------------

Поступило сообщение администратора в интернет-магазине '" . $systems['name'] . "'
---------------------------------------------------------

" . $Message_new . "

Дата/время: " . date("d-m-y H:i a") . "
---------------------------------------------------------

Вы всегда можете просмотреть ваши сообщения
он-лайн через 'Личный кабинет' -> 'Связь с менеджером' 
или по ссылке http://" . $SERVER_NAME . $SysValue['dir']['dir'] . "/users/message.html

Powered & Developed by www.PHPShop.ru";
                            mail($mail, $zag_adm, $content_adm, $header_adm);
                        }//Конец перебора отправки сообщения

                        echo '<B>Сообщения отправлены!</B> Можете отправить еще если нужно.';
                    } //Конец если отправляем сообщение
                    else { //Если выбрана новость, действуем по стандартному алгоритму рассылки новостей
                        foreach ($IdsArray as $v) {//Перебор пользователей, отправка каждому сообещния
                            $content = Ras_data_content("or id=" . $nid_new);
                            Ras_data_mail($content, $num, $v);
                        }

                        echo '<B>Новость разослана!</B> Можете отправить еще, если нужно!';
                    } //Конец если выбрана новость
/////////////////////////////////////////////////////////////////////НОВОЕ///////////////////
                } elseif ($DO == 42) {// Удалить переписку пользователей
                    $sql = "delete from " . $SysValue['base']['table_name37'] . "
where UID='$IDS'";
                    $pageReload = "shopusers_messages";
                } elseif ($DO == 22) {// Удалить пользователей
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name27'] . "
    where id='0' $string";
                    $pageReload = "shopusers";
                } elseif ($DO == 43) {// Пройти цензуру комментариев
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";


                    $sql = "UPDATE " . $SysValue['base']['table_name36'] . "
SET
enabled='1' 
where id='0' $string";
                    $pageReload = "comment";
                } elseif ($DO == 46) {// удалить новости
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name8'] . "
    where id='0' $string";
                    $pageReload = "news";
                } elseif ($DO == 44) {// Заблокировать вывод комментариев
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";


                    $sql = "UPDATE " . $SysValue['base']['table_name36'] . "
SET
enabled='0' 
where id='0' $string";
                    $pageReload = "comment";
                } elseif ($DO == 23) {// Тематические статьи
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    if (is_array($page_new))
                        foreach ($page_new as $value)
                            @$page.=$value . ",";

                    $sql = "UPDATE $table_name2
SET
page='$page' 
where id='0' $string";
                    $pageReload = "cat_prod";
                }
                elseif ($DO == 25) {// разослать уведомления
                    require_once "../../lib/sms/smsmmapi.php";
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "select * from " . $SysValue['base']['table_name34'] . " where id='0' $string";
                    @$result = mysql_query(@$sql);
                    while (@$row = mysql_fetch_array(@$result)) {
                        $id = $row['id'];
                        $datas = $row['datas'];
                        $datas_start = $row['datas_start'];
                        $user_id = $row['user_id'];
                        $product_id = $row['product_id'];

                        $User = GetInfoUsers($user_id);
                        $Product = GetInfoProduct($product_id);
                        $price = ($Product['price'] + (($Product['price'] * $GetSystems['percent']) / 100));
                        $valuta = GetValuta($Product['baseinputvaluta']);

// Шлем заявку
                        $codepage = "windows-1251";
                        $header_adm = "MIME-Version: 1.0\n";
                        $header_adm .= "From:   <" . $GetSystems['adminmail2'] . ">\n";
                        $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
                        $header_adm .= "X-Mailer: PHP/";
                        $zag_adm = $GetSystems['name'] . " - Поступило уведомление о товаре, заявка №$id ";



                        $content_adm = "
Доброго времени!
--------------------------------------------------------

Поступило уведомление №$id с интернет-магазина '" . $GetSystems['name'] . "' для пользователя " . $User['name'] . "

Товар: " . $Product['name'] . "
АРТ: " . $Product['uid'] . "
Базовая стоимость: " . $price . " " . $valuta['code'] . "
Дата изменения информации по товару: " . dataV($Product['datas']) . "
Линк: http://" . $SERVER_NAME . "/shop/UID_" . $product_id . ".html
Дата поступления заявки: " . dataV($datas_start) . "
Активность заявки до: " . dataV($datas) . "

---------------------------------------------------------

Powered & Developed by www.PHPShop.ru";
                        mail($User['mail'], $zag_adm, $content_adm, $header_adm) or die("Не могу отправить уведомление");

// Обновляем уведомление
                        mysql_query("UPDATE " . $SysValue['base']['table_name34'] . " SET enabled='1' where id=".intval($id));

// Отсылаем SMS
                        if ($option['notice_enabled'] == 1) {
                            $msg = "Товар появился в продаже, подробности: http://$SERVER_NAME/shop/UID_$product_id.html";
                            $phone = $User['tel_code'] . "" . $User['tel'];
                            SendSMS($msg, $phone);
                        }
                    }

                    $pageReload = "shopusers_notice";
                } elseif ($DO == 26) {// Удалить уведомление
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name34'] . "
    where id='0' $string";
                    $pageReload = "shopusers_notice";
                } elseif ($DO == 39) {// Удалить страницу
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name11'] . "
    where id='0' $string";
                    $pageReload = "page";
                } elseif ($DO == 40) {// Удалить изображения к товару при новом товаре
                    $sql = "delete from " . $SysValue['base']['table_name35'] . " where parent=$IDS";
                    $pageReload = "";
                } elseif ($DO == 27) {// Пометить как нет в наличии
                    foreach ($IdsArray as $v) {
                        //@$string.="or id='$v' ";
                        //выбираем поле vendor чтобы убрать i186-1200i
                    	$sql = "select vendor,vendor_array from " . $SysValue['base']['table_name2'] . " where id='$v'";
                    	$result = mysql_query($sql);
                    	while (@$row = mysql_fetch_array(@$result)) {
                    		$vendor = $row['vendor'];
                    		$vendor_array=unserialize($row['vendor_array']);
                    	}
                    	//echo '$vendor='.$vendor;
                    	//var_dump($vendor_array);
                    	//ищем и удаляем i186-1200i
                    	$haracts=explode("i",$vendor);
                    	$haract_str='';
                    	foreach ( $haracts as $haract ) {
                    		if (!empty($haract)) {
	                   			if ( $haract!='186-1200' ) {
                    				$haract_str.='i'.$haract.'i';
                    			} else {
                    				$haract_str.='i186-i';
                    			}
                    		}
                    	}

                    	foreach ($vendor_array as $key=>$vendor_array_item) {
                    		//echo  $vendor_array_item[0].' ';
                    		if ($vendor_array_item[0]=='1200'){
                    			unset($vendor_array[$key][0]);
                    			//echo'!'. $vendor_array_item[0].'!';
                    		}
                    	}
                    	//echo '$haract_str='.$haract_str; 
                        $sql = "UPDATE " . $SysValue['base']['table_name2'] . "
                        SET
                        sklad='1',
                        p_enabled='0',
                        yml='0',
                        vendor='$haract_str',
                        vendor_array='".serialize($vendor_array)."'
                        where id='$v'";//$string";
                        //echo $sql;
                        mysql_query($sql);
                    }


                    $pageReload = "cat_prod";
                } elseif ($DO == 28) {// Пометить как есть в наличии
                    foreach ($IdsArray as $v) {
                        //@$string.="or id='$v' ";
                        //выбираем поле vendor чтобы найти i186-1200i
                    	$sql = "select vendor,vendor_array from " . $SysValue['base']['table_name2'] . " where id='$v'";
                    	$result = mysql_query($sql);
                    	while (@$row = mysql_fetch_array(@$result)) {
                    		$vendor = $row['vendor'];
                    		$vendor_array=unserialize($row['vendor_array']);
                    	}
                    	//echo '$vendor='.$vendor;
                    	//var_dump($vendor_array);
                    	//ищем i186-1200i
                    	$haracts=explode("i",$vendor);
                    	$f1200_1=false;
                    	$f1200_2=false;
                    	foreach ( $haracts as $haract ) {
                    		if (!empty($haract)) {
	                   			if ( $haract=='186-1200' ) {
                    				$f1200_1=true;
                    			}
                    		}
                    	}
                    	foreach ($vendor_array as $key=>$vendor_array_item) {
                    		//echo  $vendor_array_item[0].' ';
                    		if ($vendor_array_item[0]=='1200'){
                    			$f1200_2=true;
                    		}
                    	}
                    	//если картинка 1200 есть и vendor и vendor_array
                    	if ($f1200_1==true && $f1200_2==true) {
                    		$haract_str="p_enabled='1'";
                    	} else {
                    		$haract_str="p_enabled='0'";
                    	}               	
                    	//echo '$haract_str='.$haract_str; 
                        $sql = "UPDATE " . $SysValue['base']['table_name2'] . "
                        SET
                        sklad='0',
                        $haract_str,
                        yml='1'
                        where id='$v'";
                        //echo $sql;
                        mysql_query($sql);
                    }

                    $pageReload = "cat_prod";
                } elseif ($DO == 30) {// Страницы открыты для показа
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
enabled='1'
where id='0' $string";

                    $pageReload = "page";
                } elseif ($DO == 31) {// Страницы закрыты для показа
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
enabled='0'
where id='0' $string";

                    $pageReload = "page";
                } elseif ($DO == 32) {// Страницы закрыты регистрацией
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
secure='1'
where id='0' $string";

                    $pageReload = "page";
                } elseif ($DO == 33) {// Страницы открыты регистрацией
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "UPDATE " . $SysValue['base']['table_name11'] . "
SET
secure='0'
where id='0' $string";

                    $pageReload = "page";
                } elseif ($DO == 29) {// Автоматически разослать уведомления
                    $sql = "select * from " . $SysValue['base']['table_name34'] . " where datas>'" . date("U") . "' and enabled='0'";

                    $result = mysql_query($sql);
                    while (@$row = mysql_fetch_array(@$result)) {
                        $id = $row['id'];
                        $datas = $row['datas'];
                        $datas_start = $row['datas_start'];
                        $user_id = $row['user_id'];
                        $product_id = $row['product_id'];

                        $User = GetInfoUsers($user_id);
                        $Product = GetInfoProduct($product_id);

                        if ($Product['sklad'] == 0) {

// Шлем заявку
                            $codepage = "windows-1251";
                            $header_adm = "MIME-Version: 1.0\n";
                            $header_adm .= "From:   <" . $GetSystems['adminmail2'] . ">\n";
                            $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
                            $header_adm .= "X-Mailer: PHP/";
                            $zag_adm = $GetSystems['name'] . " - Поступило уведомление о товаре, заявка №$id ";

                            $content_adm = "
Доброго времени!
--------------------------------------------------------

Поступило уведомление №$id с интернет-магазина '" . $GetSystems['name'] . "' для пользователя " . $User['name'] . "

Товар: " . $Product['name'] . "
АРТ: " . $Product['uid'] . "
Базовая стоимость: " . $Product['price'] . "
Дата изменения информации по товару: " . dataV($Product['datas']) . "
Линк: http://" . $_SERVER['SERVER_NAME'] . $GLOBALS['SysValue']['dir']['dir'] . "/shop/UID_" . $product_id . ".html
Дата поступления заявки: " . dataV($datas_start) . "
Активность заявки до: " . dataV($datas) . "

---------------------------------------------------------

Powered & Developed by www.PHPShop.ru";
                            mail($User['mail'], $zag_adm, $content_adm, $header_adm);

// Обновляем уведомление
                            mysql_query("UPDATE " . $SysValue['base']['table_name34'] . " SET enabled='1' where id=$id");


// Отсылаем SMS
                            if ($option['notice_enabled'] == 1) {
                                $msg = "Товар появился в продаже, подробности: http://$SERVER_NAME/shop/UID_$product_id.html";
                                $phone = $User['tel_code'] . "" . $User['tel'];
                                SendSMS($msg, $phone);
                            }
                        }
                    }

                    $pageReload = "shopusers_notice";
                } elseif ($DO == 36) {// Удалить из заказов
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "delete from " . $SysValue['base']['table_name1'] . "
    where id='0' $string";
                    $pageReload = "orders";
                } elseif ($DO == 45) {// Выгрузить заказы в 1С:Предприятие
                    $sql = "--";
                    echo"
<script>
window.open('../1c/orders_export.php?IDS=" . $IDS . "');
</script>
";
                    $pageReload = "orders";
                } elseif ($DO == "rss1") {// Удалить из RSS каналов
                    if (CheckedRules($UserStatus["rsschanels"], 1) == 1) {
                        foreach ($IdsArray as $v)
                            @$string.="or id='$v' ";

                        $sql = "delete from " . $SysValue['base']['table_name38'] . "
    where id='0' $string";
                        $pageReload = "rssgraber_chanels";
                    }
                    else
                        $UserChek->BadUserFormaWindow();
                }
                elseif ($DO == "rss2") {// Включить RSS каналы
                    if (CheckedRules($UserStatus["rsschanels"], 1) == 1) {
                        foreach ($IdsArray as $v)
                            @$string.="or id='$v' ";

                        $sql = "UPDATE " . $SysValue['base']['table_name38'] . " SET enabled = '1'
    where id='0' $string";
                        $pageReload = "rssgraber_chanels";
                    }
                    else
                        $UserChek->BadUserFormaWindow();
                }
                elseif ($DO == "rss3") {// Включить RSS каналы
                    if (CheckedRules($UserStatus["rsschanels"], 1) == 1) {
                        foreach ($IdsArray as $v)
                            @$string.="or id='$v' ";

                        $sql = "UPDATE " . $SysValue['base']['table_name38'] . " SET enabled = '0'
    where id='0' $string";
                        $pageReload = "rssgraber_chanels";
                    }
                    else
                        $UserChek->BadUserFormaWindow();
                }
                elseif ($DO == "rss4") {// Включить RSS каналы
                    if (CheckedRules($UserStatus["rsschanels"], 1) == 1) {
                        foreach ($IdsArray as $v)
                            @$string.="or id='$v' ";
                        $start_date_new = GetUnicTime($start_date_new);
                        $end_date_new = GetUnicTime($end_date_new);
                        $sql = "UPDATE " . $SysValue['base']['table_name38'] . " SET start_date = '$start_date_new', end_date = '$end_date_new'  
    where id='0' $string";
                        $pageReload = "rssgraber_chanels";
                    }
                    else
                        $UserChek->BadUserFormaWindow();
                }
                elseif ($DO == 24) {// Характеристики
                    foreach ($IdsArray as $v)
                        @$string.="or id='$v' ";

                    $sql = "select id,vendor_array from $table_name2 where id='0' $string";
                    $result = mysql_query($sql);

//echo $sql;
//echo 'всего:"'.mysql_num_rows(@$result).'"';
//Функция удаляет из массива все пустые значения. Если массив пуст, удаляет и сам массив
                    function cleanArray($arr) {
                        foreach ($arr as $key => $value) {
                            if (is_array($value)) {
                                $value = cleanArray($value);
                            } //Если значение - массив,то Вызываем функцию очистки
                            if ($value == "") {
                                unset($arr[$key]);
                                if (count($arr) < 1) {
                                    return;
                                }
                            } //Если удаленный элемент был последним - возвращаем false
                        }
                        return $arr;
                    }

//Конец Функция 

                    while ($row = mysql_fetch_array($result)) { //Перебор выбранных товаров
//Товары редактируются из одной и той же категории
                        $vendor_array = unserialize($row['vendor_array']);  //Принимаем старый массив характеристик
//Корректное склеивание двух массивов
                        if (is_array($save_arr))
                            $vendor_new = $save_arr;
                        $arr1 = cleanArray($vendor_array); //Удаляем пустышки из массива
                        $arr2 = cleanArray($vendor_new); //Удаляем пустышки из массива
                        $save_arr = $vendor_new;
//print_r($arr1);
//print_r($arr2);

                        if (is_array($arr1) && is_array($arr2)) { //Если оба исходника массивы
                            foreach ($arr1 as $id => $values) { //Перебор цикла
                                if (isset($arr2[$id])) { //Если есть элемент во втором массиве, надо корректно провести объединение
                                    /*
                                      if (is_array($values) && is_array($arr2[$id])) { //Если оба элемента массивы
                                      $new_values=array_merge($values,$arr2[$id]); //Новое значение = слитые массивы старого и нового значения
                                      } else if (is_array($values)) { //Если НЕ массив первый
                                      $new_values=$values;
                                      $new_values[]=$arr2[$id];
                                      } else if (is_array($arr2[$id])) { //Если НЕ массив второй
                                      $new_values=$arr2[$id];
                                      $new_values[]=$values;
                                      } else { //Если оба НЕ массивы
                                      if ($values!==$arr2[$id]) {//Если значения отличаются, делаем массив
                                      $new_values=array($values,$arr2[$id]);
                                      } else {//Конец если значения отличаются.
                                      $new_values=$values;
                                      }

                                      }
                                      // */
                                    if ($arr2[$id])
                                        $new_values = $arr2[$id]; //Если новое значение true, присвоить новое значение
//    $new_values=array_unique($new_values); //Оставляем уникальные значения в списке (без слияния не актуально)
                                    $arr1[$id] = $new_values;
                                    unset($arr2[$id]); //Ликвидируем этот найденный элемент во втором массиве
                                } //Конец если есть элемент во втором массиве
                            }//Конец перебор цикла
//$arr3=array_merge($arr1,$arr2); //merge криво обрабатывает сложение ассоциативных. Приходится использовать списки
                            $arr3 = $arr1 + $arr2;
//print_r($arr1);
//print_r($arr2);
                        } else if (is_array($arr1)) {
                            $arr3 = $arr1;
                        } else if (is_array($arr2)) {
                            $arr3 = $arr2;
                        } else {
                            $arr3 = "";
                        }
//КОНЕЦ Корректное склеивание двух массивов

                        $idgood = $row['id']; //Принимаем id товара
                        $vendor_new = $arr3;

                        if (is_array($vendor_new)) { //Если присланные новые характеристики массив
                            $vendor = '';
                            foreach ($vendor_new as $k => $v) { //Перебираем элементы присланного массив
                                if (is_array($v)) { //Если выбраны несколько элементов
                                    foreach ($v as $o => $p)
                                        @$vendor.="i" . $k . "-" . $p . "i";
                                } else { //Если только один элемент
                                    @$vendor.="i" . $k . "-" . $v . "i";
                                }
                            } //Конец Перебираем элементы присланного массив
                        } //Конец проверки массив ли новые хар-ки

                        $sqll = 'UPDATE ' . $table_name2 . ' SET vendor="' . $vendor . '", vendor_array="' . addslashes(serialize($vendor_new)) . '" where id=' . $idgood;
                        $resultt = mysql_query($sqll);
//print_r($vendor_new);
//echo $vendor;
//echo '"'.$sqll.'"';
                    } //Конец Перебор выбранных товаров



                    /*
                      $sql="UPDATE $table_name2
                      SET
                      vendor='$vendor',
                      vendor_array='".serialize($vendor_new)."'
                      where id='0' $string";
                      // */
                    $pageReload = "cat_prod";

//echo($vendor);
                }

                $result = mysql_query($sql);
///*
                echo"
   <script>
   DoReloadMainWindow('$pageReload');
   </script>
     ";
//*/
            }
        }
        else
            $UserChek->BadUserFormaWindow();
        ?>
    </body>
</html>

