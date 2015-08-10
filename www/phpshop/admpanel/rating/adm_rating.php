<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

function Systems()// вывод настроек
{
global $table_name3;
$sql="select * from $table_name3";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row;
}
$systems=Systems();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Рейтинга</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script>
window.resizeTo(500, 430);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
<?
if($UserChek->statusPHPSHOP < 2){

echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b>Редактирование Рейтинга</b><br>
	&nbsp;&nbsp;&nbsp;Укажите данные для записи в базу.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_website_statistics_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>
<br>
";
if(@$sql_go){
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
<input type=submit value=Вернуться class=but onClick="history.back(1);">
<input type=submit value=Закрыть class=but onClick="return onCancel();">
	</td>
</tr>
</table>

');
}
else{

while (list($val) = each($SysValue['base']))
@$bases.=$SysValue['base'][$val].", ";

$bases=substr($bases,0,strlen($bases)-2);
$bases2=ereg_replace(",phpshop_system","",$bases);
$bases2=ereg_replace(",phpshop_users","",$bases2);
?>
<TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
<FORM method="post" name="sql_forma" id="opros_forma">
<TBODY>
<TR class=adm vAlign=top align=middle>
<TD align=left>




</TD>
</TR>
</TABLE>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50">
	<td align="right" style="padding:10">
<INPUT class=but onclick="SqlSend()" type=button value=OK> <INPUT class=but type=reset value=Сброс> 
<input type="hidden" name="sql_go" value="ok">
	<input type=submit value=Отмена class=but onClick="return onCancel();">
	</td>
</tr>
</table>
</FORM>
<?}


}else $UserChek->BadUserFormaWindow();
?>
</body>
</html>

