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
        <title>Редактирование сообщения пользователя</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 600, 410);
        </script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?= $SysValue['lang']['lang_enabled'] ?>);
                preloader(0)">
              <?
// Редактирование записей 
              $sql = "select * from " . $SysValue['base']['table_name37'] . " where id=" . intval($id);
              $result = mysql_query($sql);
              $row = mysql_fetch_array($result);
              $Subject = $row['Subject'];
              $Message = $row['Message'];
              $DateTime = $row['DateTime'];
              $UID = $row['UID'];
              $ID = $row['ID'];
              ?>

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
        <form name="product_edit"  method=post>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Редактирование сообщения пользователя</span></b><br>
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <br>
            <table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td>

                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>аголовок сообщения</span></LEGEND>
                            <div style="padding:10">
                                <input type=text name="Subject_new"  style="width: 100%" value="<?= $Subject ?>">
                                </FIELDSET>
                                </TD>
                                <TD>
                                    <FIELDSET>
                                        <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>ата сообщения</span></LEGEND>
                                        <div style="padding:10">
                                            <input type=HIDDEN name="DateTime_new" value="<?= $DateTime ?>">
                                            <input readonly disabled type=text style="width: 100%" value="<?= $DateTime ?>">
                                            </FIELDSET>

                                            </TD>
                                        </TR><TR>     <TD colspan=2>

                                            <FIELDSET>
                                                <LEGEND><span name=txtLang id=txtLang><u>Т</u>екст сообщения</span></LEGEND>
                                                <div style="padding:10">
                                                    <textarea cols="" rows="" name="Message_new" style="width:100%;height:100px;"><?= $Message ?>
                                                    </textarea>
                                                </div>
                                            </FIELDSET>
                                        </td>
                                    </tr>


                                    </table>
                                    <hr>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                                        <tr>
                                            <td align="left" style="padding:10">
                                                <?
                                                if (isset($UID)) {//Отправка сообщения пользователю
                                                    $UsersId = $UID;
                                                    $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=" . intval($UsersId) . " LIMIT 0, 1";
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
                                                }
                                                echo "<BUTTON style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"GetMailTo('" . $mail . "','Re: " . $GetSystems['name'] . "');return false;\"> <img src=\"../img/icon_email.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">E-mail</BUTTON>";
                                                ?>
                                            </td>
                                            <td align="right" style="padding:10">

                                                <input type="hidden" name="ids" value="<?= $ID ?>" >
                                                <input type="submit" name="editID" value="OK" class=but>
                                                <input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
                                                <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                                                <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>


                                            </td>
                                        </tr>
                                    </table>                                                                            
                                    </form>
                                    <?
/////////////////////////
                                    if (isset($editID) and !empty($Message_new)) {// Запись редактирования
                                        if (CheckedRules($UserStatus["delivery"], 1) == 1) {
                                            $sql = "UPDATE " . $SysValue['base']['table_name37'] . "
SET 
DateTime='$DateTime_new',
Subject='$Subject_new',
Message='$Message_new', 
enabled='1' where ID=$ids";
                                            $result = mysql_query($sql) or @die("" . mysql_error() . "");

//echo $sql;
///*
                                            echo'
	  <script>
CLREL("right");
</script>
	   ';
//*/
                                        }
                                        else
                                            $UserChek->BadUserFormaWindow();
                                    }
                                    if (@$productDELETE == "doIT") {// Удаление
                                        if (CheckedRules($UserStatus["delivery"], 1) == 1) {
                                            $sql = "delete from " . $SysValue['base']['table_name37'] . "
where ID='$ids'";
                                            $result = mysql_query($sql) or @die("Невозможно изменить запись");
                                            echo'
	  <script>
CLREL("right");
</script>
	   ';
                                        }
                                        else
                                            $UserChek->BadUserFormaWindow();
                                    }
                                    ?>



