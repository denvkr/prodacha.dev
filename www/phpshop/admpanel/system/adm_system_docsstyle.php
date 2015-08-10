<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems = GetSystems();
$systems = $GetSystems;
$option = unserialize($GetSystems['admoption']);
$Lang = $option['lang'];
require("../language/" . $Lang . "/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Документооборот</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <meta http-equiv="MSThemeCompatible" content="Yes">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/tab.css" type=text/css rel=stylesheet>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" src="../java/tabpane.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?= $Lang ?>/language_windows.js"></script>

        <script type="text/javascript">
            DoResize(<? echo $GetSystems['width_icon'] ?>, 500, 400);
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
        $sql = "select * from $table_name3";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);


        if ($row['1c_load_accounts'] == 1)
            $load_accounts = "checked";
        else
            $load_accounts = "";

        if ($row['1c_load_invoice'] == 1)
            $load_invoice = "checked";
        else
            $load_invoice = "";

        $с_option = unserialize($row['1c_option']);

        if ($с_option['update_name'] == 1)
            $update_name = "checked";
        else
            $update_name = "";

        if ($с_option['update_content'] == 1)
            $update_content = "checked";
        else
            $update_content = "";

        if ($с_option['update_description'] == 1)
            $update_description = "checked";
        else
            $update_description = "";

        if ($с_option['update_category'] == 1)
            $update_category = "checked";
        else
            $update_category = "";

        if ($с_option['update_category'] == 1)
            $update_category = "checked";
        else
            $update_category = "";

        if ($с_option['update_sort'] == 1)
            $update_sort = "checked";
        else
            $update_sort = "";


        echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b><span name=txtLang id=txtLang>Документооборот</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Настройки для интернет-магазина</span>.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_display_settings_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>
<form name=product_edit  method=post>
<!-- begin tab pane -->
<div class=\"tab-pane\" id=\"article-tab\" style=\"margin-top:5px;\">

<script type=\"text/javascript\">
tabPane = new WebFXTabPane( document.getElementById( \"article-tab\" ), true );
</script>



<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Основное</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>

<table width=\"95%\">
<tr>
	<td colspan=3>
	<FIELDSET id=fldLayout>
	<legend>Автоматическая загрузка бухгалтерских документов из 1С:Предприятие *</legend>
<div style=\"padding:10\">
	
	<input type=\"checkbox\" name=\"load_accounts_new\" value=\"1\" $load_accounts> Оригинальный счет с печатью и подписями<br>
	<input type=\"checkbox\" name=\"load_invoice_new\" value=\"1\" $load_invoice> Оригинальная счет-фактура с печатью <br><br>
* Только для версий PHPShop Enterprise Pro 1C
</div>
</FIELDSET>
	</td>
</tr>
</table>

</div>

         <div class=\"tab-page\" id=\"sklad\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Склад</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"sklad\" ) );
</script>

<table width=\"95%\">
<tr>
	<td colspan=3>
	<FIELDSET id=fldLayout>
	<legend>Обновление данных</legend>
<div style=\"padding:10\">

	<input type=\"checkbox\" name=\"update_name_new\" value=\"1\" $update_name> Название номенклатуры<br>
        <input type=\"checkbox\" name=\"update_description_new\" value=\"1\" $update_description> Краткое описание <br>
	<input type=\"checkbox\" name=\"update_content_new\" value=\"1\" $update_content> Подробное описание <br>
        <input type=\"checkbox\" name=\"update_category_new\" value=\"1\" $update_category> Родительская категория <br>
        <input type=\"checkbox\" name=\"update_sort_new\" value=\"1\" $update_sort> Характериcтики <br>
         <br>
* Только для версий PHPShop Enterprise Pro 1C
</div>
</FIELDSET>
	</td>
</tr>
</table>

</div>

</div>



<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
    <td align=\"left\" style=\"padding:10\">
    <BUTTON class=\"help\" onclick=\"helpWinParent('docsstyle')\">Справка</BUTTON>
	</td>
	<td align=\"right\" style=\"padding:10\">
<input type=submit value=ОК class=but name=optionsSAVE>
	<input type=submit name=btnLang value=Отмена class=but onClick=\"return onCancel();\">
	</td>
</tr>
</table>
</form>
";



        if (isset($optionsSAVE)) {
            if (CheckedRules($UserStatus["option"], 1) == 1) {

                $с_option["update_name"] = $update_name_new;
                $с_option["update_content"] = $update_content_new;
                $с_option["update_description"] = $update_description_new;
                $с_option["update_category"] = $update_category_new;
                $с_option["update_sort"] = $update_sort_new;
                $с_option_new = serialize($с_option);


                $sql = "UPDATE $table_name3
SET
1c_load_accounts='$load_accounts_new',
1c_load_invoice='$load_invoice_new',
1c_option='$с_option_new'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись" . $sql . mysql_error());
                echo"
	 <script>
	 CL();
	 </script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>


