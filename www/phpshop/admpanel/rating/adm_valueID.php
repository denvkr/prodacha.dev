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
	<title>�������������� �������������� ��� ��������</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<? 
echo $Lang;?>/language_windows.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,500,420);
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

function Disp_cat($n)// ����� ��������� � ������
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name50']." order by name";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id_category'];
    $name=substr($row['name'],0,35);
	if ($id==$n)
	   {
	   $sel="selected";
	   }
	   else
	      {
		  $sel="";
		  }
    @$dis.="<option value=\"$id\" $sel>$name</option>\n";
	}
@$disp="
<select name=category_new size=1>
$dis
</select>
";
return @$disp;
}


// �������������� �������
	  $sql="select * from ".$SysValue['base']['table_name51']." where id_charact='$id'";
      $result=mysql_query($sql);
	  $row = mysql_fetch_array($result);
	  $id=$row['id_charact'];
	  $name=$row['name'];
	  $category=$row['id_category'];
	  $num=$row['num'];
	  if($row['enabled']==1)  $sel1="checked"; else  $sel2="checked";
	  ?>
<form name="product_edit"  method=post>
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>�������������� ��������������</span> "<?=$name?>"</b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>������� ������ ��� ������ � ����</span>.
	</td>
	<td align="right">
	<img src="../img/i_website_statistics_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>�</u>������� ��������������</span></LEGEND>
<div style="padding:10">
<input type="text" name="name_new" value="<?=$name?>" class="full">
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>�</u>��������</span></LEGEND>
<div style="padding:10">
<?=Disp_cat($category);?>
</div>
</FIELDSET>
	</td>
</tr>

<tr>
  <td width="50%">
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>�</u>����</span></LEGEND>
<div style="padding:10">
<input type="radio" name="enabled_new" value="1" <?=@$sel1?>><span name=txtLang id=txtLang>��������</span>&nbsp;&nbsp;
<input type="radio" name="enabled_new" value="0" <?=@$sel2?>><font color="#FF0000"><span name=txtLang id=txtLang>������</span></font>
<br><br>
</div>
</FIELDSET>
  </td>
  <td width="50%">
    <FIELDSET>
<LEGEND><span name=txtLang id=txtLang><u>�</u>������ �� �������</span></LEGEND>
<div style="padding:10">
<input type="text" name="num_new" value="<?=$num?>" class="full">
</div>
</FIELDSET>
  </td>
</tr>

</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
	<td align="right" style="padding:10">
<input type="hidden" name="id" value="<?=$id?>" >
	<input type="submit" name="editID" value="OK" class=but>
	<input type="button" name="btnLang" class=but value="�������" onClick="PromptThis();">
    <input type="hidden" class=but  name="productDELETE" id="productDELETE">
	<input type="button" name="btnLang" value="������" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and !empty($name_new))// ������ ��������������
{
if(CheckedRules($UserStatus["opros"],1) == 1){
$sql="UPDATE ".$SysValue['base']['table_name51']."
SET
id_category='$category_new',
name='$name_new',
num='$num_new',
enabled='$enabled_new'
where id_charact='$id'";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo"
<script>
CLREL();
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
if(@$productDELETE=="doIT")// ��������
{
if(CheckedRules($UserStatus["opros"],1) == 1){
$sql="delete from ".$SysValue['base']['table_name51']." where id_charact='$id'";
$result=mysql_query($sql)or @die("���������� �������� ������");
echo"
	  <script>
CLREL();
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



