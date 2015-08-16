<?
require("../connect.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
	<title>YML</title>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript" src="../language/<?=$lang?>/language_windows.js"></script>
<script language="JavaScript" type="text/javascript">
//window.resizeTo(300,200);
// Закрывает окно
function CloseWindow() 
{
window.close();
}

// Значения при загрузки
function setDefaults()
{
var p1=document.getElementById("yml_new");
p1.value="<?=@$yml_new?>";
var p2=document.getElementById("p_enabled_1");
p2.value="<?=@$p_enabled?>";
var p3=document.getElementById("bid").value="<?=@$yml_bid?>";
var p4=document.getElementById("cbid").value="<?=@$yml_cbid?>";
var p5=document.getElementById("bid_enabled");
p5.value="<?=@$bid_enabled?>";
var p6=document.getElementById("cbid_enabled");
p6.value="<?=@$cbid_enabled?>";

if(p5.value == 1) p5.checked=true;
if(p6.value == 1) p6.checked=true;

if(p1.value == 1) p1.checked=true;
if(p2.value == 1) p2.checked=true;
else document.getElementById("p_enabled_2").checked=true;
}

function OkWindow(){
var p1=document.getElementById("yml_new");
if(p1.checked == true) window.opener.document.getElementById('yml_new').value=1;
else window.opener.document.getElementById('yml_new').value=0;

var p1=document.getElementById("p_enabled_1");
if(p1.checked == true) window.opener.document.getElementById('p_enabled').value=1;
else window.opener.document.getElementById('p_enabled').value=0;

var p3=document.getElementById("bid").value;
window.opener.document.getElementById('yml_bid').value=p3;

var p4=document.getElementById("cbid").value;
window.opener.document.getElementById('yml_cbid').value=p4;

var p5=document.getElementById("bid_enabled");
if(p5.checked==true) window.opener.document.getElementById('yml_bid_enabled').value=1;
else window.opener.document.getElementById('yml_bid_enabled').value=0;

var p6=document.getElementById("cbid_enabled");
if(p6.checked==true) window.opener.document.getElementById('yml_cbid_enabled').value=1;
else window.opener.document.getElementById('yml_cbid_enabled').value=0;

self.close();
}
</script>

</head>

<body bottommargin="5" leftmargin="5" topmargin="5" rightmargin="5"  onload="setDefaults();DoCheckLang(location.pathname,1)">
<form  method="post" name="EditPrice">
<input type="hidden" name="id" value="<?=@$id?>">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
  <td align="center">
   <FIELDSET style="padding:5px">
<LEGEND><input type="checkbox" id="yml_new" value="1"><span name=txtLang id=txtLang>Показывать в YML-Маркете</span></LEGEND>
  <input type="radio" id="p_enabled_1" name="p_enabled" value="1"><span name=txtLang id=txtLang>В наличии</span>
  &nbsp;&nbsp;
  <input type="radio" id="p_enabled_2" name="p_enabled" value="1"><span name=txtLang id=txtLang>Под заказ</span>
</FIELDSET>

  </td>
</tr>
<tr>
  <td>
  <table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td >
	  <FIELDSET style="padding:5px">
<LEGEND><input type="checkbox" id="bid_enabled" value="1"><span name=txtLang id=txtLang>Ставка BID</span></LEGEND>
<input type="text" style="width: 50px" maxlength="3" id="bid">
 </FIELDSET>  
	</td>
	<td>
	  <FIELDSET style="padding:5px">
<LEGEND><input type="checkbox" id="cbid_enabled" value="1"><span name=txtLang id=txtLang>Ставка CBID</span></LEGEND>
<input type="text" style="width: 50px" maxlength="3" id="cbid">
 </FIELDSET>
	
	</td>
</tr>
</table>
  </td>
</tr>
</table>
<hr>
<div align="right">
<input type="button" name="btnLang" class=but value="Принять" onClick="OkWindow();">
<input type="button" name="btnLang" class=but value="Отмена" onClick="CloseWindow();">
</div>
</form>
</body>
</html>
