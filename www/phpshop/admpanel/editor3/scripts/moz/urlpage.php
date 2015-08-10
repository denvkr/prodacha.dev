<?
require("../../../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../../../enter_to_admin.php");

function GetPageLink(){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name11']." order by link";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $link=$row['link'];
    $name=substr($row['name'],0,50);
    @$dis.="<option value=\"/page/$link.html\" $sel>$link.html - $name</option>\n";
	}
@$disp="
<select name='pageName' id='pageName' size=15 style=\"width: 290px;\">
$dis
</select>
";
return @$disp;
}
?>
<html>
<head>
<title>Мои страницы</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style/editor.css" rel="stylesheet" type="text/css">
<script>
function doApply()
	{
        window.opener.document.getElementById('inpBookmark').value=window.document.getElementById('pageName').value;
        return self.close();
	}
</script>
</head>
<body style="margin:0;overflow:hidden">

<table width=100% height=100% align=center cellpadding=0 cellspacing=0>
<tr>
<td valign=top style="padding:4;padding-left:0;width:100%">
	<table cellpadding="0" cellspacing="0" style="width:100%">
	<tr>
	<td style="width:100%">
	<?= GetPageLink()?>
	</td>
	</tr>
	</table>	
</td>
</tr>
<tr>
<td class="dialogFooter" colspan=2 style="padding:6;" align="right">
	<input type=button name=btnCancel id=btnCancel value="cancel" onclick="self.close()" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
	<input type=button name=btnOk id=btnOk value=" ok " onclick="doApply();return false;" class="inpBtn" onmouseover="this.className='inpBtnOver';" onmouseout="this.className='inpBtnOut'">
</td>
</tr>
</table>



</body>
</html>
