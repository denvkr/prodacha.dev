<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("���������� �������������� � ����");
mysql_select_db("$dbase")or @die("���������� �������������� � ����");
require("../enter_to_admin.php");

// �����
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>�������� ��������</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,600,350);
function DoSet(){
var sum=document.getElementById('taxa').value;
if (sum>0) {document.getElementById('taxa_enabled').checked=true;} else {document.getElementById('taxa_enabled').checked=false;}
}


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
	<b><span name=txtLang id=txtLang>�������� ������ �������� ��������</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>������� ������ ��� ������ � ����</span>.
	</td>
	<td align="right">
	<img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td colspan=3>

	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>�</u>������</span>: <?$categoryID?></LEGEND>
	<div style="padding:10">
	<?
function Disp_cat_pod($category)// ����� ��������� � ������ ������������
{
global $SysValue;
$sql="select city from ".$SysValue['base']['table_name30']." where id='$category'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
@$name=$row['city'];
return @$name." -> ";
}



function Disp_cat($category)// ����� ��������� � ������
{
global $SysValue;
	$sql="select city,PID from ".$SysValue['base']['table_name30']." where id=$category";
	$result=mysql_query($sql);
	@$row = mysql_fetch_array(@$result);
	@$num = mysql_num_rows(@$result);
	if($num>0){
	$name=$row['city'];
	$parent_to=$row['PID'];
	$dis=Disp_cat_pod($parent_to).$name;
	}
	return @$dis;
}


echo '
<input type=text id="myName"  style="width: 500" value="'.Disp_cat($categoryID).'">
<input type="hidden" value="'.$categoryID.'" name="NPID" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category='.$categoryID.'\',300,400,300,200)"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>';
	?>
	</FIELDSET>
</TD></TR><TR>     <TD>

	<FIELDSET style="height:80px">
<LEGEND><span name=txtLang id=txtLang><u>�</u>�������</span></LEGEND>
<div style="padding:10">
<input name="city_new" style="width:100%"><br>
<input type="checkbox" name="flag_new" value="1"><span name=txtLang id=txtLang>�������� �� ���������</span>
</div>
</FIELDSET>



	</td>
	<td>
	</td>
	<td>
	<FIELDSET style="height:80px">
<LEGEND><span name=txtLang id=txtLang><u>�</u>��������</span></LEGEND>
<div style="padding:10">
<input type="radio" name="enabled_new" value="1" checked><span name=txtLang id=txtLang>��</span><br>
<input type="radio" name="enabled_new" value="0"><font color="#FF0000"><span name=txtLang id=txtLang>���</span></font>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
  <td colspan="3">

  </td>
</tr>

<tr>
  <td colspan="3">

  </td>
</tr>


</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
   <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('delivery')">�������</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" class=but value="��������">
	<input type="button" name="btnLang" value="������" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?

if(isset($editID) and !empty($city_new))// ������ ��������������
{
if(CheckedRules($UserStatus["delivery"],2) == 1){
$sql="INSERT INTO ".$SysValue['base']['table_name30']."
VALUES ('','$city_new','$price_new','$enabled_new','$flag_new','$price_null_new','$price_null_enabled_new','$NPID','$taxa_new','1')";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo'
	  <script>
CLREL("left");
</script>
	   ';
}else $UserChek->BadUserFormaWindow();
}
?>



