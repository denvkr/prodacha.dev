<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

if((isset($productSAVE)) and $name_new!="" and $flag==0)// запись в базу
{
$sql="INSERT INTO $table_name4
VALUES ('','$name_new')";
$result=mysql_query($sql)or @die("".mysql_error()."");
}
elseif((isset($productSAVE)) and $name_new!="" and $flag==1)// запись в базу
{
$sql="UPDATE $table_name4
SET
name='$name_new'
where id='$nameId'";
$result=mysql_query($sql)or @die("".mysql_error()."");
}
elseif((isset($productSAVE)) and $name_new!="" and $flag==2)// запись в базу
{
$sql="
delete from $table_name4 
where id='$nameId'";
$result=mysql_query($sql)or @die("".mysql_error()."");
}

function Vivod_pot()// вывод каталогов
{
global $table_name4,$system,$category;
$sql="select * from $table_name4 order by name";
$result=mysql_query($sql);
$i=0;
$j=0;
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=$row['name'];
	@$dis.="
	d2.add($id,0,'$name','$name');
	";
	$i++;
	 }
$dis="
<script type=\"text/javascript\">
		<!--
		d2 = new dTree('d2');
		d2.add(0,-1,'<b>Каталог  категорий</b>');
        ".$dis."
		document.write(d2);
		//-->
	</script>
";
return $dis;
}

?>

<html>
<head>
<title>Категории</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<LINK href="../css/dtree.css" type=text/css rel=stylesheet>
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript1.2 src="../java/dtree2.js" type=text/javascript></SCRIPT>
<script>
function UpdateCat(cat){
window.opener.document.getElementById("myCat").value=cat;
window.close();
}

// Дерево
function My(name,cat){
var TIP = window.document.getElementById('FlagReturn');
if(TIP.checked==true){
window.opener.document.getElementById('myVendor').value=name;
window.opener.document.getElementById('myVendorId').value=cat;
window.close();
}
else{
window.document.getElementById('nameNew').value=name;
window.document.getElementById('butName').value='Изменить';
window.document.getElementById('nameId').value=cat;
}
}
</script>
</head>
<body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0">
<table cellpadding="2"  cellspacing="2" width="98%"bgcolor="ffffff" height="85%" align="center" style="border: 2px;border-style: inset;">
<tr>
	<td valign="top">
<?echo Vivod_pot();?>
	</td>
</tr>
</table>
<table cellpadding="0"  cellspacing="0" width="100%" style="background: threedface; color: windowtext;padding:5;padding-top:5" align="center">
<form action="adm_vendor.php" method="post">
<tr valign="middle">
	<td colspan="3" align="center">
	<input type="radio" name="flag" value="0" checked id="FlagReturn"><u>П</u>ринять
	
<input type="radio" name="flag" value="1" id="FlagEdit"><u>Р</u>едактировать
	
<input type="radio" name="flag" value="2" id="FlagDelete"><u>У</u>далить
	</td>
</tr>
<tr valign="middle">
	<td width="40">
	<u>Н</u>азвание:
	</td>
	<td>
<input type=text name="name_new" size=20 id="nameNew">
<input type="hidden" name="nameId" id="nameId">
<input type="submit" value="Добавить" name="productSAVE" id="butName">
	</td>
</tr>
</form>
</table>
</body>
</html>

