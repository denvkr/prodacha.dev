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
	<title>Создание Опроса</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,630,330);
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

// Вывод ответов
function dispFaq($n){
global $table_name5,$systems;
$sql="select SUM(total) as sum from $table_name5 where category='$n'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$sum=$row['sum'];
$sql="select * from $table_name5 where category='$n' order by num";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$total=$row['total'];
	$num=$row['num'];
	@$disp.='
	<tr id="rol" onclick="miniWin(\'adm_valueID.php?id='.$id.'\',400,400)">
	<td>'.$name.'</td>
	<td>'.$total.'</td>
	<td>'.number_format(($total*100)/$sum,"1",".","").'%</td>
</tr>
	';
}
return @$disp;
}
	  ?>
<form name="product_edit"  method=post>
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Опроса</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
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
<LEGEND><span name=txtLang id=txtLang><u>З</u>аголовок</span> </LEGEND>
<div style="padding:10">
<textarea name="name_new" class=s style="width:100%; height:30"><?=$name?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td width="70%">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>П</u>ривязка к страницам</span></LEGEND>
<div style="padding:10">
<input type="text" name="dir_new" class="full" value="<?=$dir?>"><br>
* <span name=txtLang id=txtLang>Пример: page/,news/. Можно указать несколько адресов через запятую</span>. 
</div>
</FIELDSET>
	</td>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>В</u>ывод</span></LEGEND>
<div style="padding:10">
<input type="radio" name="flag_new" value="1" ><span name=txtLang id=txtLang>Показать</span>&nbsp;&nbsp;
<input type="radio" name="flag_new" value="0"  checked><font color="#FF0000"><span name=txtLang id=txtLang>Скрыть</span></font>
<br><br>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('opros')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
<input type="hidden" name="valueID" value="<?=$id?>" >
	<input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" class=but value="Сбросить">
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and !empty($name_new))// Запись редактирования
{
if(CheckedRules($UserStatus["opros"],2) == 1){
$sql="INSERT INTO $table_name6 VALUES ('','$name_new','$dir_new','$flag_new')";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo"
	  <script>
DoReloadMainWindow('opros');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



