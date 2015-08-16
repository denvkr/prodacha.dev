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
	<title>Создание Рейтинга</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,630,450);
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
	<b><span name=txtLang id=txtLang>Создание Рейтинга</span></b><br>
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
<input type="text" name="name_new" class=s style="width:100%;">
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td width="70%" rowspan=2>
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>П</u>ривязка к категориям товаров</span></LEGEND>


<DIV  style="overflow-y:auto; height:155px; padding:10;">
<?
function dispcats($PID=0,$LVL=0) {
global $SysValue;
$sql='select * from '.$SysValue['base']['table_name'].' where parent_to='.$PID.' order by num';
$result=mysql_query($sql);
$dis='0';
$LVL++;
while (@$row = mysql_fetch_array($result)) {
	$id=$row['id'];
	$name=$row['name'];
	$parent_to=$row['parent_to'];
	$mover='';
	for($i=0;$i<$LVL-1;$i++) {$mover.='&nbsp;&nbsp;&nbsp;&nbsp;';}
	if (dispcats($id,$LVL)!="0") {
		$dis.='<optgroup label="'.$name.'">'.dispcats($id,$LVL).'</optgroup>';
	} else {
		@$dis.='<option value="'.$id.'">'.$mover.$name.'</option>';
	}
}
return $dis;	
} //Конец функции



?>
<select size=1 name=idsdir_new[] style="height:135;width:100%;" multiple>
<? echo dispcats(); ?>
</select>

</DIV>
</FIELDSET>
	</td>
	<td>
<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>В</u>ывод</span></LEGEND>
<div style="padding:10">
<input type="radio" name="enabled_new" value="1" checked><span name=txtLang id=txtLang>Показать</span>&nbsp;&nbsp;
<input type="radio" name="enabled_new" value="0"  ><font color="#FF0000"><span name=txtLang id=txtLang>Скрыть</span></font>
<br><br>
</div>
</FIELDSET>
	</td>
</tr>
<TR>
	<td>
<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>ереголосование</span></LEGEND>
<div style="padding:10">
<input type="radio" name="revoting_new" value="1" checked><span name=txtLang id=txtLang>Разрешено</span>&nbsp;&nbsp;
<input type="radio" name="revoting_new" value="0"><font color="#FF0000"><span name=txtLang id=txtLang>Запрещено</span></font>
<br><br>
</div>
</FIELDSET>
	</td>
</TR>


</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('rating')">Справка</BUTTON></BUTTON>
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
if(CheckedRules($UserStatus["rating"],2) == 1){
foreach ($idsdir_new as $cid) {$idsd.=','.$cid.',';}

$sql="INSERT INTO ".$SysValue['base']['table_name50']." VALUES ('','$idsd','$name_new','$enabled_new','$revoting_new')";
$result=mysql_query($sql)or @die("".mysql_error()."");
//die($sql);
echo"
	  <script>
DoReloadMainWindow('rating');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



