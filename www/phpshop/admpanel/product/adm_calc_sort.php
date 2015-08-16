<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");


// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Калькулятор Характеристик</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2"> 
window.resizeTo(500, 600);
</script>
<script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript" src="../language/<?=$Lang?>/language_windows.js"></script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
<?

function Disp_cat($category)// вывод каталогов в выборе
{
global $table_name;
$sql="select name,parent_to from $table_name where id=$category";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
$parent_to=$row['parent_to'];
$dis=Disp_cat_pod($parent_to).$name;
return @$dis;
}


function dispValue($n){ // вывод характеристик
global $SysValue,$_POST;
$sql="select * from ".$SysValue['base']['table_name21']." where category='$n' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=substr($row['name'],0,35);
	$sel="";
	if(is_array($_POST['vendor_new']))
	foreach($_POST['vendor_new'] as $k=>$v){
	
	      if(is_array($v)){
		     foreach($v as $o=>$p){
			        if ($id == $p) $sel="selected";
			 }
	      }
	}
	if ($id == $v) $sel="selected";
	
    @$dis.="<option value=".$id." ".$sel." >".$name."</option>\n";
	}
@$disp="
<select name=vendor_new[".$n."][] size=1 style=\"width: 350; height: 50\" multiple>
<option>Нет данных</option>
$dis
</select>
";
return @$disp;
}


function DispCatSort($category){
global $SysValue;
$sql="select sort from ".$SysValue['base']['table_name']." where id=$category";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$sort=unserialize($row["sort"]);
if(is_array($sort))
foreach($sort as $v){
$sql="select * from ".$SysValue['base']['table_name20']." where id=$v order by name";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
@$disp.= '
<div style="padding-top:7">
<FIELDSET id=fldLayout >
<LEGEND id=lgdLayout>'.$name.'</LEGEND>
<div style="padding:10">
'.dispValue($id).'
</div>
</FIELDSET>
</div>
';
}
}
$dis='
<tr>
    <td align=left>

'.@$disp.'

</td>
</tr>
';
return @$dis;
}

	echo ('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Калькулятор Характеристик</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Выберете нужные характеристики</span>.
	</td>
	<td align="right">
	<img src="../img/i_account_contacts_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<div style="height:370px;overflow:auto">
<table>
<form method="post">
'.DispCatSort($id).'
</table>
</div>
<table>
<tr>
  <td>
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>абличная запись для загрузки базы через Excel</span>:</LEGEND>
<div style="padding:10">
<textarea style="height:50px;width:440px"  id="upload_log" name="upload_log">');
if(isset($start))
echo base64_encode(serialize(@$vendor_new));
echo('</textarea>
<div align="right" style="padding:10px">
<input type="submit" name="btnLang" value="Пересчитать" name=start>
<!--[if IE]>
<input type="button" value="Копировать" onclick="copyToClipboard()">
<![endif]-->
<input type="button" name="btnLang" value="Отмена" onclick="window.close()">
<input type="hidden" name="start" value="on">
</div>
</div>
</FIELDSET>
  </td>
</tr>
</table>

</form>
	');
?>
</body>
</html>
