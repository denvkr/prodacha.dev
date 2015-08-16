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
	<title>Редактирование Адреса</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,400,250);
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
// Редактирование записей книги
	  $sql="select * from $table_name9 where id=$id";
      $result=mysql_query($sql);
	  $row = mysql_fetch_array($result);
	  $id=$row['id'];
	  $data=$row['datas'];
	  $mail=$row['mail'];
echo('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Редактирование Адреса Рассылки</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<form name="product_edit"  method=post >
<table  cellpadding="2" cellspacing="0" border="0">
<tr valign="top">
	<td align="left" width="70%">
<FIELDSET>
<LEGEND >E-mail: </LEGEND>
<div style="padding:10">
<input type="text" name="mail_new" value="'.$mail.'" size="68">
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
	<td align="right" style="padding:10">
<input type=submit name="editID" value="OК" class=but>
	<input type=submit name="btnLang" name="delID" value="Удалить" class=but>
	<input type=submit name="btnLang" value=Отмена class=but onClick="return onCancel();">
	<input type="hidden" name="id" value="'.$id.'">
	</td>
</tr>
</table>
</form>
');

if(isset($editID))// Запись редактирования
{
if(CheckedRules($UserStatus["news_writer"],1) == 1){
$sql="UPDATE $table_name9
SET
mail='$mail_new'
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo('
<script>
DoReloadMainWindow(\'news_writer\');
</script>
	   ');
}else $UserChek->BadUserFormaWindow();
}
if(isset($delID))// Удаление
{
if(CheckedRules($UserStatus["news_writer"],1) == 1){
$sql="delete from $table_name9
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo('
<script>
DoReloadMainWindow(\'news_writer\');
</script>
	   ');
}else $UserChek->BadUserFormaWindow();
}
?>
