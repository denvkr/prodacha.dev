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
	<title>Создание Нового Банера</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
?>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,630,600);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Банера</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_select_another_account_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>

<form name="product_edit"  method=post onsubmit="Save()">
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">

<tr>
  <td>
  <FIELDSET id=fldLayout style="height: 8em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание банера</span></LEGEND>
<div style="padding:10">
<input type="text" name="name_new" style="width: 100%; "><br><br>
<input type="radio" name="flag_new" checked value="1"><span name=txtLang id=txtLang>Показывать банер</span>&nbsp;&nbsp;&nbsp;
<input type="radio" name="flag_new" value="0"><span name=txtLang id=txtLang>Скрыть банер</span>
</div>
</FIELDSET>
  </td>
  <td>
  <FIELDSET id=fldLayout style="height: 8.3em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Л</u>имит показов</span></LEGEND>
<div style="padding:10">
<input type="text" name="limit_all_new" style="width: 100%; " value="0"><br>
<span name=txtLang id=txtLang>* После окончания показов администратор будет уведомлен.</span>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td colspan="2">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>ривязка к странице</span></LEGEND>
<div style="padding:10">
<input type="text" name="dir_new"  style="width:100%"><br>
<span name=txtLang id=txtLang>* Пример: page/,news/. Можно указать несколько адресов через запятую без пробелов.</span>
</FIELDSET>
  </td>
</tr>
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang>Содержание</span></LEGEND>
<div style="padding:10">
<?
$systems=GetSystems();
$option=unserialize($systems['admoption']);
if($option['editor_enabled']  == 1){
$MyStyle=$SysValue['dir']['dir'].chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.@$content.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\''.$SysValue['dir']['dir'].'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=600;
		oEdit1.height=200;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
</div>
	';
	}
else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:200px">'.$content.'</textarea>
';
}
	?>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
   <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('baner')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
		<input type="hidden" name="id" value="<?=$id?>" >
	<input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" name="delID" value="Сбросить" class=but>
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and @$name_new!="")// Запись редактирования
{
if(CheckedRules($UserStatus["baner"],2) == 1){
$datas=date("d.m.y");
$sql="INSERT INTO $table_name15 VALUES ('','$name_new','".addslashes($EditorContent)."','','','$flag_new','$datas','$limit_all_new','$dir_new')";
$result=mysql_query($sql)or @die("Невозможно изменить запись");

echo"
	  <script>
DoReloadMainWindow('baner');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>
