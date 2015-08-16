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
	<title>Создание Нового Способа Оплаты</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>

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


// Выбор файла
function GetTipPayment($dir){

$path="../../../payment/";

    if ($dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
		     if ($file != "." && $file != "..") {
			   
			   if(is_dir($path.$file)){
			     if($dir == $file) $s="SELECTED"; 
			       else $s="";
			   @$dis.="<option value=$file $s>".TipPayment($file)."</option>";
                 }
			   }
					}
        closedir($dh);
    }

$dis="
<select name=\"path_new\">
$dis
</select>
";
return $dis;
}

	  ?>
<form name="product_edit"  method=post onsubmit="Save()">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Способа Оплаты</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_visa_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
  <td>
  <FIELDSET>
<LEGEND><span name=txtLang id=txtLang>Наименование</span></LEGEND>
<div style="padding:10">
<input type="text" name="name_new" value="" style="width: 100%; "><br><br>
<input type="radio" name="enabled_new" value="1"  checked><span name=txtLang id=txtLang>Показывать</span>&nbsp;&nbsp;&nbsp;
<input type="radio" name="enabled_new" value="0"><span name=txtLang id=txtLang>Скрыть</span>
</div>
</FIELDSET>
  </td>
  <td valign="top">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang>Тип подключения</span></LEGEND>
<div style="padding:10">
<?=GetTipPayment("message")?><br><br>
Сортировка: <input type="text" name="num_new" value="0" style="width: 30px; ">
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td colspan="2">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>аголовок сообщения после оплаты</span></LEGEND>
<div style="padding:10">
<input type="text" name="message_header_new" value="" style="width:100%"><br>

</FIELDSET>
  </td>
</tr>
<tr>
  <td colspan="2">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>C</u>ообщения после оплаты</span></LEGEND>
<div style="padding:10">
<textarea name="message_new" style="width:100%;height: 150px"></textarea>

</FIELDSET>
  </td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('payment')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="hidden" name="id" value="<?=$id?>" >
	<input type="submit" name="editID" value="OK" class=but>
	<input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
    <input type="hidden" class=but  name="productDELETE" id="productDELETE">
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and @$name_new!="")// Запись редактирования
{
if(CheckedRules($UserStatus["visitor"],2) == 1){
$sql="INSERT INTO ".$GLOBALS['SysValue']['base']['table_name48']." SET
name='$name_new',
path='$path_new',
num='$num_new',
enabled='$enabled_new',
message='$message_new',
message_header='$message_header_new'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
DoReloadMainWindow('payment');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



