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

function dispPage($array){ // вывод статей по теме
global $SysValue;
$array=explode(",",$array);
$sql="select * from ".$SysValue['base']['table_name11']." where enabled='1' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $link=$row['link'];
    $name=substr($row['name'],0,100);
	$sel="";
	if(is_array($array))
	foreach($array as $v){
	if ($link == $v) $sel="selected";
	}
    @$dis.="<option value=".$link." ".$sel." >".$name."</option>\n";
	}
@$disp="
<select name=page_new>
<option value=''>Нет описания</option>\n
$dis
</select>
";
return @$disp;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Создание Набора Характеристик</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<LINK href="../skins/<?=$_SESSION['theme']?>/tab.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script type="text/javascript" src="../java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,500,450);
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
	<b><span name=txtLang id=txtLang>Создание Новой Характеристики</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_billing_history_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<!-- begin tab pane -->
<div class="tab-pane" id="article-tab" style="margin-top:5px;height:300px">

<script type="text/javascript">
tabPane = new WebFXTabPane( document.getElementById( "article-tab" ), true );
</script>



<!-- begin intro page -->
<div class="tab-page" id="intro-page" style="height:250px">
<h2 class="tab"><span name=txtLang id=txtLang>Основное</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "intro-page" ) );
</script>
<table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>Н</u>аименование</span> </LEGEND>
<div style="padding:10">
<input type="text" name="name_new" class="full">
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td>
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>О</u>пции </span></LEGEND>
<div style="padding:10">
<input type="checkbox" value="1" name="flag_new"> <span name=txtLang id=txtLang>Подкаталог 3-го уровня</span>&nbsp;&nbsp;&nbsp;
<input type="checkbox" value="1" name="filtr_new"><span name=txtLang id=txtLang>Фильтр</span>
<input type="checkbox" value="1" name="goodoption_new"><span name=txtLang id=txtLang>Товарная опция</span>
<input type="checkbox" value="1" name="optionname_new"><span name=txtLang id=txtLang>Показывать описание опции в корзине</span>
</div>
</FIELDSET>
	</td>
	</td>
	<td>
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>П</u>озиция</span> </LEGEND>
<div style="padding:10">
<input type="text" name="num_new" class="full">
</div>
</FIELDSET>
	</td>
</tr>
</table>
</div>
<div class="tab-page" id="content-page" style="height:250px">
<h2 class="tab"><span name=txtLang id=txtLang>Описание</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "content-page" ) );
</script>

<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td>
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>П</u>одсказка</span></LEGEND>
<div style="padding:10">
<textarea class=full name=description_new style="height:40px"><?=$description?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td>
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>С</u>ылка на описание</span></LEGEND>
<div style="padding:10">
<? echo dispPage($page) ?>
<p>* Используется при выводе имени характеристики в подробном описании товара в виде ссылки на указанную страницу (описание характеристики "мощность" становится доступной в подробной форме товара в таблице вывода характеристик).</p>
</div>
</FIELDSET>
	</td>
</tr>
</table>
</div>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
   <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('sortID')">Справка</BUTTON>
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
if(isset($editID) and !empty($name_new))// Запись редактирования
{
if(CheckedRules($UserStatus["cat_prod"],2) == 1){
$sql="INSERT INTO ".$SysValue['base']['table_name20']." VALUES ('','$name_new','$flag_new','$num_new','-1','$filtr_new','$description_new','$goodoption_new','$optionname_new','$page_new')";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo"
	 <script>
DoReloadMainWindow('sort');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



