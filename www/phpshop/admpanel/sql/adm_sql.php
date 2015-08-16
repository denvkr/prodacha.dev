<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Работа с базой</title>
        <META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
        <LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon']?>,500,430);
        </script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">

        <table id="loader">
            <tr>
                <td valign="middle" align="center">
                    <div id="loadmes" onclick="preloader(0)">
                        <table width="100%" height="100%">
                            <tr>
                                <td id="loadimg"></td>
                                <td ><b><?=$SysValue['Lang']['System']['loading']?></b><br><?=$SysValue['Lang']['System']['loading2']?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>
        <?

        echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b><span name=txtLang id=txtLang>Работа с базой</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите команды для MySQL</span>.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_databases_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>
<br>
";
        if(@$sql_go) {
            if(CheckedRules($UserStatus["sql"],1) == 1) {
                $IdsArray=split(";\r",$sql_area);
                foreach ($IdsArray as $v)
                    $result=mysql_query($v);
                if(@$result) $disp= "
> <b>MySQL</b>: $sql_area<br><br>
> <b>MySQL: запрос выполнен.</b>";
                else $disp="
> <b>MySQL</b>: ".mysql_error()."";
                echo ('
<div align="left" style="width:100%;height:250;overflow:auto;">
<table bgcolor="white" style="border: 2px;
	border-style: inset;" width="100%"  cellpadding="0" cellspacing="0" height="250">
<tr>
	<td style="padding:5" valign="top">
	'.@$disp.'
	</td>
</tr>
</table>

</div>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50">
<tr>
	<td align="right" style="padding:10">
<input type=submit value=Вернуться class=but onClick="history.back(1);" name="btnLang2">
<input type=submit value=Закрыть class=but onClick="return onCancel();" name="btnLang2">
	</td>
</tr>
</table>

');
            }else $UserChek->BadUserFormaWindow();
        }
        else {


            foreach($SysValue['base'] as $val)
                $baseArray[$val]=$val;

            unset($baseArray['phpshop_system']);
            unset($baseArray['phpshop_users']);
            $bases=null;
            $TRUNCATE=null;

            foreach($baseArray as $val){
                $bases.=$val.", ";
                $TRUNCATE.='TRUNCATE '.$val.';
';
            }



            $bases=substr($bases,0,strlen($bases)-2);


            ?>
        <TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
        <FORM method="post" name="sql_forma" id="sql_forma">
        <TBODY>
        <TR class=adm vAlign=top align=middle>
        <TD align=left>
<textarea style="WIDTH: 100%; HEIGHT: 20em" name="sql_area" id="sql_area"><?echo $CsvContent?></textarea>
        <br>
<table cellpadding="0" cellpadding="0" width="100%">
<tr>
	<td><select name="sql_query" onchange="SelectQuerySql(this.value)">
                <option value="" SELECTED id=txtLang>Выбрать команду SQL</option>
                <option value="OPTIMIZE TABLE <?=$bases?>" id=txtLang>Оптимизировать базу</option>
                <option value="REPAIR TABLE <?=$bases?>" id=txtLang>Починить базу</option>
                <option value="DELETE FROM <?=$table_name?> WHERE ID=" id=txtLang>Удалить каталог</option>
                <option value="DELETE FROM <?=$table_name12?> WHERE ID=" id=txtLang>Удалить страницу</option>
                <option value="<?=$TRUNCATE?>" id=txtLang>Очистить базу</option>
                <option value="DROP DATABASE <?=$bases?>" id=txtLang>Уничтожить базу</option>
                </select> * Выберите "Очистить базу" для удаления тестовой базы.</td>
                </tr>
                </table>


                </TD>
                </TR>
                </TABLE>
                <hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50">
<tr>
    <td align="left" style="padding:10">
            <BUTTON class="help" onclick="helpWinParent('sql')">Справка</BUTTON></BUTTON>
            </td>
	<td align="right" style="padding:10">
                <INPUT class=but onclick="SqlSend()" type=button value=OK> <INPUT class=but type=reset value=Сброс name="btnLang">
<input type="hidden" name="sql_go" value="ok">
	<input type=submit value=Отмена class=but onClick="return onCancel();" name="btnLang">

                </td>
                </tr>
                </table>
                </FORM>
                <?
            }
            ?>
        </body>
        </html>