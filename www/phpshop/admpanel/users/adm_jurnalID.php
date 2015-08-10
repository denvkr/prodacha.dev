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
        <title>Добавление адреса в черный список</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 400, 270);
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
        if (isset($id)) {
            $sql = "select * from " . $SysValue['base']['table_name10'] . " where id=" . intval($id);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $id = $row['id'];
            $user = $row['user'];
            $ip = $row['ip'];
            ?>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Редактирование Пользователя c</span> IP "<?= $ip ?>"</b><br>
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_spam_filter_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <form name="product_edit">
                <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                    <tr>
                        <td colspan="2">
                            <FIELDSET id=fldLayout style="width: 100%; height: 8em;">
                                <LEGEND id=lgdLayout><u>I</u>P </LEGEND>
                                <div style="padding:10">
                                    <input type="text" name="ip_new" value="<?= $ip ?>" style="width: 100%;"><br><br>
                                    * <span name=txtLang id=txtLang>IP-адрес будет добавлен в черный список. Пользователь с данным адресом не сможет пройти авторизацию</span>.
                                    </td>
                                    </tr>
                                    </table>
                                    <hr>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                                        <tr>
                                            <td align="right" style="padding:10">
                                                <input type="hidden" name="userID" value="<?= $id ?>" >
                                                <input type="submit" name="editID" value="OK" class=but>
                                                <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                                            </td>
                                        </tr>
                                    </table>
                                    </form>
    <?
}
if (isset($editID) and @$ip_new != "") {
    if (CheckedRules($UserStatus["users"], 2) == 1) {
        $sql = "INSERT INTO " . $SysValue['base']['table_name22'] . "
VALUES ('','$ip_new','" . date("d-m-y H:s a") . "')";
        $result = mysql_query($sql) or @die("Невозможно изменить запись");
        echo"
<script>
DoReloadMainWindow('users_jurnal_black');
</script>
	   ";
    }
    else
        $UserChek->BadUserFormaWindow();
}
?>



