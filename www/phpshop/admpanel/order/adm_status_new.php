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
	<title>Создание Статуса Заказа</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,460,270);
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

<form name="product_edit"  method=post>
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Статуса Заказа</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_billing_history_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>Н</u>аименование</span> </LEGEND>
<div style="padding:10">
<input type="text" name="name_new" class="full" ><br><br>
<input type="checkbox" value="1" name="sklad_action_new"> Списывать со склада
</div>
</FIELDSET>
	</td>
	<td valign="top">
		<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>Ц</u>вет фона</span> </LEGEND>
<div style="padding:10">
<input type="text" name="color_new" id="color_new" class="full"><br>
<table border="1">
<tr>
	<td width="10" height="10" bgcolor="red"><a href="javascript:DoColor('red')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="green"><a href="javascript:DoColor('green')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="blue"><a href="javascript:DoColor('blue')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="#ff00ff"><a href="javascript:DoColor('#ff00ff')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#00ffff"><a href="javascript:DoColor('#00ffff')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#00ff00"><a href="javascript:DoColor('#00ff00')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#c0c0c0"><a href="javascript:DoColor('#c0c0c0')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#008080"><a href="javascript:DoColor('#008080')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#ccff00"><a href="javascript:DoColor('#ccff00')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#ffccff"><a href="javascript:DoColor('#ffccff')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
</tr>
<tr>
	<td width="10" height="10" bgcolor="#33cc00"><a href="javascript:DoColor('#33cc00')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="#ccff99"><a href="javascript:DoColor('#ccff99')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="#ff9900"><a href="javascript:DoColor('#ff9900')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
	<td width="10" height="10" bgcolor="#ff6600"><a href="javascript:DoColor('#ff6600')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#00ccff"><a href="javascript:DoColor('#00ccff')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#ffffff"><a href="javascript:DoColor('#ffffff')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#ccffcc"><a href="javascript:DoColor('#ccffcc')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#99cccc"><a href="javascript:DoColor('#99cccc')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#6666cc"><a href="javascript:DoColor('#6666cc')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
<td width="10" height="10" bgcolor="#990000"><a href="javascript:DoColor('#990000')" ><img src="../img/blank.gif" alt="Выбрать цвет" width="10" height="10" border="0"></a></td>
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
    <BUTTON class="help" onclick="helpWinParent('statusID')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" class=but value="Сбросить">
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?

if(isset($editID))// Запись редактирования
{
if(CheckedRules($UserStatus["visitor"],2) == 1){
$sql="INSERT INTO ".$SysValue['base']['table_name32']."
VALUES ('','$name_new','$color_new','$sklad_action_new')";
$result=mysql_query($sql) or die("".$sql.mysql_error()."");
echo('
<script>
DoReloadMainWindow("order_status");
</script>
	   ');
}else $UserChek->BadUserFormaWindow();
}
?>



