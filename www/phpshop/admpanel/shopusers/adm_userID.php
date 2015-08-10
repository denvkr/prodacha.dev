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

function GetUsersStatus($n) {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name28'] . " order by discount";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $discount = $row['discount'];
        $sel = "";
        if ($n == $id)
            $sel = "selected";
        @$dis.="<option value=" . $id . " " . $sel . " >" . $name . " - " . $discount . "%</option>\n";
    }
    @$disp = "
<select name=status_new size=1>
<option value=0 id=txtLang>Авторизованный пользователь</option>
$dis
</select>
";
    return @$disp;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Редактирование Пользователя</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 500, 580);
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
        $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=" . intval($id);
        $result = mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        $id = $row['id'];
        $login = $row['login'];
        $password = $row['password'];
        $status = $row['status'];
        $mail = $row['mail'];
        $name = $row['name'];
        $company = $row['company'];
        $inn = $row['inn'];
        $tel = $row['tel'];
        $tel_code = $row['tel_code'];
        $kpp = $row['kpp'];
        $adres = $row['adres'];
        if ($row['enabled'] == 1) {
            $fl = "checked";
        } else {
            $fl2 = "checked";
        }
        ?>
        <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
            <tr bgcolor="#ffffff">
                <td style="padding:10">
                    <b><span name=txtLang id=txtLang>Редактирование Пользователя</span> "<?= $login ?>"</b><br>
                    &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                </td>
                <td align="right">
                    <img src="../img/i_groups_med[1].gif" border="0" hspace="10">
                </td>
            </tr>
        </table>
        <form name="product_edit">
            <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td colspan="2">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>татус</span></LEGEND>
                            <div style="padding:10">
                                <?= GetUsersStatus($status) ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="1" <?= @$fl ?>><span name=txtLang id=txtLang>Учитывать</span>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="0" <?= @$fl2 ?>><font color="#FF0000"><span name=txtLang id=txtLang>Заблокировать</span></font>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <FIELDSET id=fldLayout style="width: 480; height: 8em;">
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>оступ</span></LEGEND>
                            <div style="padding:10">
                                <table>
                                    <tr>
                                        <td>Login</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="login_new" value="<?= $login ?>" style="width:250px;" ></td>
                                        <td rowspan="2" valign="top" style="padding-left:10">
                                            <button style="width: 14em; height: 2.2em; margin-left:5"  onclick="miniWin('../order/adm_visitor_new.php?userAdd=<?= $id ?>', 650, 550);
        return false;">
                                                <img src="../img/page_attachment.gif" border="0" align="absmiddle" hspace=5>
                                                <span name=txtLang id=txtLang>Новый заказ</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Password</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="password_new" style="width:250px;" value="<?= base64_decode($password); ?>"></td>
                                    </tr>
                                </table>

                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Л</u>ичные данные</span></LEGEND>
                            <div style="padding:10">
                                <table>
                                    <tr>
                                        <td>E-mail:
                                        </td>
                                        <td><input type="text" name="mail_new" value="<?= $mail ?>" style="width:350px"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>Контактное лицо</span>:
                                        </td>
                                        <td><input type="text" name="name_new" value="<?= $name ?>" style="width:350px"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>Компания</span>: </td>
                                        <td><input type="text" name="company_new" style="width:350px;" value="<?= $company ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>ИНН</span>: </td>
                                        <td><input type="text" name="inn_new" style="width:350px;" value="<?= $inn ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>КПП</span>: </td>
                                        <td><input type="text" name="kpp_new" style="width:350px;" value="<?= $kpp ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>Телефон</span>: </td>
                                        <td><input type="text" name="tel_code_new" style="width:50px;" value="<?= $tel_code ?>"> -
                                            <input type="text" name="tel_new" style="width:290px;" value="<?= $tel ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>Адрес</span>: </td>
                                        <td><textarea style="width:350px; height:50px;" name="adres_new"><?= $adres ?></textarea></td>
                                    </tr>
                                </table>

                            </div>
                        </FIELDSET>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('shopusersID')">Справка</BUTTON></BUTTON>
                    </td>
                    <td align="right" style="padding:10">
                        <input type="hidden" name="userID" value="<?= $id ?>" >
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
                        <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                        <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
        <?
        if (isset($editID) and !empty($login_new) and !empty($password_new)) {
            if (CheckedRules($UserStatus["shopusers"], 1) == 1) {
                $sql = "UPDATE " . $SysValue['base']['table_name27'] . "
SET
login='$login_new',
password='" . base64_encode($password_new) . "',
mail='$mail_new',
name='$name_new',
company='$company_new',
inn='$inn_new',
tel='$tel_new',
adres='$adres_new',
enabled='$enabled_new',
status='$status_new',
kpp='$kpp_new',
tel_code='$tel_code_new' 
where id='$userID'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
                echo"
<script>
DoReloadMainWindow('shopusers');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        if (@$productDELETE == "doIT") {// Удаление
            if (CheckedRules($UserStatus["shopusers"], 1) == 1) {
                $sql = "delete from " . $SysValue['base']['table_name27'] . "
where id='$userID'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
                echo"
	  <script>
DoReloadMainWindow('shopusers');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>



