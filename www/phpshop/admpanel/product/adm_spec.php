<?
require("../connect.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Дополнительный вывод</title>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script type="text/javascript" language="JavaScript" src="../language/<?=$lang?>/language_windows.js"></script>
<script language="JavaScript">

// Закрывает окно
function CloseWindow() 
{
window.close();
}

// Значения при загрузки
function setDefaults()
{
var p1=document.getElementById("spec_new");
p1.value="<?=@$spec?>";
var p2=document.getElementById("newtip_new");
p2.value="<?=@$newtip?>";
var p3=document.getElementById("enabled_new");
p3.value="<?=@$enabled?>";
var p4=document.getElementById("num_new");
p4.value="<?=@$num?>";
if(p1.value == 1) p1.checked=true;
if(p2.value == 1) p2.checked=true;
if(p3.value == 1) p3.checked=true;
//else document.getElementById("p_enabled_2").checked=true;
//window.resizeTo(100,100);
}

function OkWindow(){

var p1=document.getElementById("spec_new");
if(p1.checked == true) window.opener.document.getElementById('spec_new').value=1;
else window.opener.document.getElementById('spec_new').value=0;

var p2=document.getElementById("newtip_new");
if(p2.checked == true) window.opener.document.getElementById('newtip_new').value=1;
else window.opener.document.getElementById('newtip_new').value=0;

var p3=document.getElementById("enabled_new");
if(p3.checked == true) window.opener.document.getElementById('enabled_new').value=1;
else window.opener.document.getElementById('enabled_new').value=0;

var p4=document.getElementById("num_new").value;
window.opener.document.getElementById('num_new').value=p4;

window.close();
}
</script>

</head>

<body bottommargin="5" leftmargin="5" topmargin="5" rightmargin="5"  onload="setDefaults();DoCheckLang(location.pathname,1)">
<p><br></p>
<table>
<form  method="post" name="EditPrice">
<tr>
	<td width="200">
	<input type="checkbox" id="enabled_new" value="1"><span name=txtLang id=txtLang>Вывод в каталоге</span>
	</td>
	<td rowspan=3 valign="top">
	<BUTTON name="ok" onclick="OkWindow()" style="cursor:hand;">
	<table cellpadding="0" cellspacing="0">
<tr>
    <td width="20">
	<td><span name=txtLang id=txtLang>Принять</span></td>
	<td width="20">
</tr>
</table>
	</BUTTON><br>
	<BUTTON name="cancel" onclick="CloseWindow()" style="margin-top:5px" style="cursor:hand">
	<table cellpadding="0" cellspacing="0">
<tr>
    <td width="20">
	<td><span name=txtLang id=txtLang>Отменить</span></td>
	<td width="20">
</tr>
</table>
	</BUTTON>
</td>
</tr>
<tr>
  <td><input type="checkbox" id="spec_new" value="1"><span name=txtLang id=txtLang>Спецпредложение</span>
  </td>
</tr>
<tr>
  <td><input type="checkbox" id="newtip_new" value="1"><span name=txtLang id=txtLang>Новинка каталога</span>
  </td>
</tr>
<tr>
  <td><input type="text" id="num_new"  size=3 >&nbsp;№ <span name=txtLang id=txtLang>по порядку</span>
  </td>
</tr>
</table>
</form>
</body>
</html>
