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
        <title>Редактирование Отзыва</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/dateselector.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language=JavaScript src="../java/popup_lib.js"></SCRIPT>
        <SCRIPT language=JavaScript src="../java/dateselector.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 630, 580);
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
        // Редактирование записей книги
        $sql = "select * from " . $SysValue['base']['table_name36'] . " where id=".intval($id);
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $id = $row['id'];
        $data = $row['datas'];
        $name = $row['name'];
        $content = $row['content'];
        $parent_id = $row['parent_id'];
        $user_id = $row['user_id'];

        if ($row['enabled'] == 1)
            $fl = "checked";
        else
            $fl = "";
        ?>
        <form name="product_edit"  method=post>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Редактирование Комментария от</span> "<?= $name ?>"</b><br>
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_account_properties_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <br>
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr valign="top">
                    <td width="130">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>ата</span> </LEGEND>
                            <div style="padding:10">
                                <input type="text"  name="data_new" size="8" value="<?= date("d-m-Y", $data) ?>" class=s>
                                <IMG onclick="popUpCalendar(this, product_edit.data_new, 'dd-mm-yyyy');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
                            </div>
                        </FIELDSET>
                    </td>
                    <td align="left" width="500">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>тправитель</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="name_new" style="width: 300; " value="<?= $name ?>">
                                <button style="width: 12em; height: 2.2em; margin-left:5"  onclick="miniWin('../shopusers/adm_userID.php?id=<?= $user_id ?>', 500, 580)"> <img src="../img/icon_user.gif"  border="0" align="absmiddle" hspace="5">
                                    <span name=txtLang id=txtLang>Пользователь</span></button>
                            </div>
                        </FIELDSET>
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>бъект</span> </LEGEND>
                            <div style="padding:10">
                                <?
                                $sql = "select name from " . $SysValue['base']['table_name2'] . " where id=$parent_id";
                                $result = mysql_query($sql);
                                $row = mysql_fetch_array($result);
                                echo '
	  <img src="../img/icon-setup.gif"  border="0" align="absmiddle" hspace="5"><a href="http://' . $_SERVER[SERVER_NAME] . '/shop/UID_' . $parent_id . '.html"  target="_blank" title="Переход">' . $row['name'] . '</a>
	  ';
                                ?>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>омментарий</span> <input type="checkbox" value="1" name="enabled_new" <?= $fl ?>> Цензура пройдена</LEGEND>
                            <div style="padding:10">
                                <textarea name="otsiv_new" class=s style="width:100%; height:200"><?= $content ?></textarea>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('comment')">Справка</BUTTON></BUTTON>
                    </td>
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
        if (isset($editID)) {// Запись редактирования
            if (CheckedRules($UserStatus["gbook"], 1) == 1) {
                if ($otsiv_new != "")
                    $flag_new = 1;
                $sql = "UPDATE " . $SysValue['base']['table_name36'] . "
SET
datas='" . GetUnicTime($data_new) . "',
name='$name_new',
content='$otsiv_new',
enabled='$enabled_new' 
where id='$id'";
                $result = mysql_query($sql) or @die("" . mysql_error() . "");
                echo"
	  <script>
DoReloadMainWindow('comment');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        if (@$productDELETE == "doIT") {// Удаление записи
            if (CheckedRules($UserStatus["gbook"], 1) == 1) {
                $sql = "delete from " . $SysValue['base']['table_name36'] . "
where id='$id'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
                echo"
	  <script>
DoReloadMainWindow('comment');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>



