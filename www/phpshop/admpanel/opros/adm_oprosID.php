<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("���������� �������������� � ����");
mysql_select_db("$dbase") or @die("���������� �������������� � ����");
require("../enter_to_admin.php");

// �����
$GetSystems = GetSystems();
$option = unserialize($GetSystems['admoption']);
$Lang = $option['lang'];
require("../language/" . $Lang . "/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>�������������� ������</title>
        <META http-equiv=Content-Type content="text/html; <?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon'] ?>, 630, 530);
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

// ����� �������
        function dispFaq($n) {
            global $table_name5, $systems;
            $sql = "select SUM(total) as sum from $table_name5 where category='" . intval($n) . "'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $sum = $row['sum'];
            $sql = "select * from $table_name5 where category='" . intval($n) . "' order by num";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $total = $row['total'];
                $num = $row['num'];
                @$disp.='
	<tr onclick="miniWin(\'adm_valueID.php?id=' . $id . '\',500,370)"  onmouseover="show_on(\'r' . $id . '\')" id="r' . $id . '" onmouseout="show_out(\'r' . $id . '\')" class=row>
	<td class="forma">' . $name . '</td>
	<td class="forma">' . $total . '</td>
	<td class="forma">' . number_format(($total * 100) / $sum, "1", ".", "") . '%</td>
</tr>
	';
            }
            return @$disp;
        }

        // �������������� ������� �����
        $sql = "select * from $table_name6 where id='".intval($id)."'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $id = $row['id'];
        $name = $row['name'];
        $dir = $row['dir'];
        if ($row['flag'] == 1)
            $sel1 = "checked";
        else
            $sel2 = "checked";
        ?>
        <form name="product_edit"  method=post>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>�������������� ������</span> "<?= $name ?>"</b><br>
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>������� ������ ��� ������ � ����</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_website_statistics_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <br>
            <table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>�</u>��������</span> </LEGEND>
                            <div style="padding:10">
                                <textarea name="name_new" class=s style="width:100%; height:30"><?= $name ?></textarea>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td width="70%">
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang><u>�</u>������� � ���������</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="dir_new" class="full" value="<?= $dir ?>"><br>
                                * <span name=txtLang id=txtLang>������: page/,news/. ����� ������� ��������� ������� ����� �������</span>.
                            </div>
                        </FIELDSET>
                    </td>
                    <td>
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>�</u>����</span></LEGEND>
                            <div style="padding:10">
                                <input type="radio" name="flag_new" value="1" <?= @$sel1 ?>><span name=txtLang id=txtLang>��������</span>&nbsp;&nbsp;
                                <input type="radio" name="flag_new" value="0" <?= @$sel2 ?>><font color="#FF0000"><span name=txtLang id=txtLang>������</span></font>
                                <br><br>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%"  cellpadding="0" cellspacing="0" style="border: 1px;
                               border-style:inset;" >
                            <tr>
                                <td valign="top">
                                    <div align="left" style="width:100%;height:150;overflow:auto"> 
                                        <table cellpadding="0" cellspacing="1" width="100%" border="0" bgcolor="#808080">
                                            <tr>
                                                <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>������� ������</span></td>
                                                <td id=pane align=center ><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>������</span></td>
                                                <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>���������</span></td>
                                            </tr>
<?= dispFaq($id); ?>
                                        </table>


                                </td>
                            </tr>
                        </table>
                        <div align="right" style="padding:10">
                            <BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin('../window/adm_window.php?do=12&ids=<?= $id ?>', 300, 220)">
                                <img src="../img/i_delete[1].gif" width="16" height="16" border="0" align="absmiddle">
                                <span name=txtLang id=txtLang>�������� ������</span>
                            </BUTTON>
                            <BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin('adm_value_new.php?categoryID=<?= $id ?>', 500, 370)">
                                <img src="../img/icon-move-banner.gif" width="16" height="16" border="0" align="absmiddle">
                                <span name=txtLang id=txtLang>����� �������</span>
                            </BUTTON>
                        </div>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('opros')">�������</BUTTON></BUTTON>
                    </td>
                    <td align="right" style="padding:10">
                        <input type="hidden" name="id" value="<?= $id ?>" >
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="button" name="btnLang" class=but value="�������" onClick="PromptThis();">
                        <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                        <input type="button" name="btnLang" value="������" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
        <?
        if (isset($editID) and !empty($name_new)) {// ������ ��������������
            if (CheckedRules($UserStatus["opros"], 1) == 1) {
                $sql = "UPDATE $table_name6
SET
name='$name_new',
dir='$dir_new',
flag='$flag_new'
where id='$id'";
                $result = mysql_query($sql) or @die("" . mysql_error() . "");
                echo"
	  <script>
DoReloadMainWindow('opros');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        if (@$productDELETE == "doIT") {// ��������
            if (CheckedRules($UserStatus["opros"], 1) == 1) {
                $sql = "delete from $table_name6
where id='$id'";
                $result = mysql_query($sql) or @die("���������� �������� ������");
                echo"
	  <script>
DoReloadMainWindow('opros');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>



