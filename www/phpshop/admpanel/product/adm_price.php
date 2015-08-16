<?
require("../connect.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Цена</title>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
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
var p1=document.getElementById("numBox");
p1.value="<?=@$numBox?>";
if(p1.value == 1) p1.checked=true;
document.getElementById("priceOne").value=window.opener.document.getElementById("priceOne").value;
document.getElementById("priceBox").value=window.opener.document.getElementById("priceBox").value;
document.getElementById("price2").value=window.opener.document.getElementById("price2").value;
document.getElementById("price3").value=window.opener.document.getElementById("price3").value;
document.getElementById("price4").value=window.opener.document.getElementById("price4").value;
document.getElementById("price5").value=window.opener.document.getElementById("price5").value;
window.resizeTo(330,280);
}

function OkWindow(){

window.opener.document.getElementById('priceOne').value=document.getElementById("priceOne").value;
window.opener.document.getElementById('priceBox').value=document.getElementById("priceBox").value;
var p1=document.getElementById("numBox");

if(p1.checked == true)
window.opener.document.getElementById('numBox').value=1;
else window.opener.document.getElementById('numBox').value=0;

window.opener.document.getElementById("price2").value=document.getElementById("price2").value;
window.opener.document.getElementById("price3").value=document.getElementById("price3").value;
window.opener.document.getElementById("price4").value=document.getElementById("price4").value;
window.opener.document.getElementById("price5").value=document.getElementById("price5").value;

window.close();
}

function blocPrice(){
var p1=document.getElementById("numBox");
if(p1.checked){
document.getElementById("priceOne").disabled=true;
document.getElementById("priceBox").disabled=true;
}
else{
    document.getElementById("priceOne").disabled=false;
    document.getElementById("priceBox").disabled=false;
    }
}
</script>

</head>

<body bottommargin="5" leftmargin="5" topmargin="5" rightmargin="5"  onload="setDefaults();DoCheckLang(location.pathname,1)">
<p><br></p>
<form  method="post" name="EditPrice">
<input type="hidden" name="id" value="<?=@$id?>">
<table style="margin:5px">
<tr>
	<td><span name=txtLang id=txtLang>Цена</span></td>
	<td width="150"><input type="text" id="priceOne" name="priceOne" maxlength="10"></td>
	<td rowspan=3 valign="top">
	<BUTTON name="ok" onclick="OkWindow()" style="cursor:hand;">
	<table cellpadding="0" cellspacing="0">
<tr>
	<td width="20"></td>
	<td><span name=txtLang id=txtLang>Принять</span></td>
	<td width="20">
</tr>
</table>
	</BUTTON><br>
	<BUTTON name="cancel" onclick="CloseWindow()" style="margin-top:5px" style="cursor:hand">
	<table cellpadding="0" cellspacing="0">
<tr>
	<td width="20"></td>
	<td><span name=txtLang id=txtLang>Отменить</span></td>
	<td width="20">
</tr>
</table>
	</BUTTON>
</td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Старая цена</span></td>
  <td><input type="text" id="priceBox" name="priceBox" maxlength="10"></td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Цена 2</span></td>
  <td><input type="text" id="price2" name="price2" maxlength="10"></td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Цена 3</span></td>
  <td><input type="text" id="price3" name="price3" maxlength="10"></td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Цена 4</span></td>
  <td><input type="text" id="price4" name="price4" maxlength="10"></td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Цена 5</span></td>
  <td><input type="text" id="price5" name="price5" maxlength="10"></td>
</tr>
<tr>
  <td><span name=txtLang id=txtLang>Под заказ</span></td>
  <td>
  <input type="checkbox" id="numBox" name="numBox" onchange="blocPrice()">
  &nbsp;&nbsp;&nbsp;
  <BUTTON name="btnLang" onclick="miniWin('../window/adm_calc.php',230,200);return false;"   class="option" style="width:100px">
	Калькулятор
	</BUTTON>
  </td>
</tr>
</table>
</form>
</body>
</html>
