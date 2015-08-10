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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Редактирование Вопроса для Опроса</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 500, 380);
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

        function Disp_cat($n) {// вывод каталогов в выборе
            global $table_name6;
            $sql = "select * from $table_name6 order by name";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = substr($row['name'], 0, 35);
                if ($id == $n) {
                    $sel = "selected";
                } else {
                    $sel = "";
                }
                @$dis.="<option value=\"$id\" $sel>$name</option>\n";
            }
            @$disp = "
<select name=category_new size=1>
$dis
</select>
";
            return @$disp;
        }

// Редактирование записей
        $sql = "select * from $table_name5 where id='" . intval($id) . "'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $id = $row['id'];
        $name = $row['name'];
        $category = $row['category'];
        $total = $row['total'];
        $num = $row['num'];
        ?>
        <form name="product_edit"  method=post>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Редактирование Ответа</span> "<?= $name ?>"</b><br>
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_website_statistics_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <br>
            <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>О</u>твет</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="name_new" value="<?= $name ?>" class="full">
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>К</u>атегория</span></LEGEND>
                            <div style="padding:10">
<?= Disp_cat($category); ?>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                </tr>
                <tr>
                    <td width="50%">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>Г</u>олоса</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="total_new" value="<?= $total ?>" class="full">
                            </div>
                        </FIELDSET>
                    </td>
                    <td width="50%">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>П</u>озиция по порядку</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="num_new" value="<?= $num ?>" class="full">
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="right" style="padding:10">
                        <input type="hidden" name="id" value="<?= $id ?>" >
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
                        <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                        <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
<?
if (isset($editID) and !empty($name_new)) {// Запись редактирования
    if (CheckedRules($UserStatus["opros"], 1) == 1) {
        $sql = "UPDATE $table_name5
SET
category='$category_new',
name='$name_new',
total='$total_new',
num='$num_new'
where id='$id'";
        $result = mysql_query($sql) or @die("" . mysql_error() . "");
        echo"
<script>
CLREL();
</script>
	   ";
    }
    else
        $UserChek->BadUserFormaWindow();
}
if (@$productDELETE == "doIT") {// Удаление
    if (CheckedRules($UserStatus["opros"], 1) == 1) {
        $sql = "delete from $table_name5
where id='$id'";
        $result = mysql_query($sql) or @die("Невозможно изменить запись");
        echo"
	  <script>
CLREL();
</script>
	   ";
    }
    else
        $UserChek->BadUserFormaWindow();
}
?>



