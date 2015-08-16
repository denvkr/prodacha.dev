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


function Delivery_Cat($PID=0) {
global $SysValue;

$sql='select * from '.$SysValue['base']['table_name30'].' where (PID='.$PID.' AND is_folder="1") order by city';
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$PID=$row['PID'];
	$city=$row['city'];
	$enabled=$row['enabled'];
	$free_en=$row['price_null_enabled'];
	$free=$row['price_null'];
	$taxa=$row['taxa'];
	$price=$row['price'];
	if(($enabled)=="1"){$checked="";}else{$checked='[ОТКЛ!]';};

	$sqlnums='select * from '.$SysValue['base']['table_name30'].' where PID='.$id.' order by city';
	$resultnums=mysql_query($sqlnums);
	@$nums=mysql_num_rows(@$resultnums);

	if ($nums) {$nums='('.$nums.')';} else {$nums='';}

	$name=trim($checked.' '.$city.' '.$nums);
	 @$display.="
	d4.add($id,$PID,'$name','admin_delivery_content.php?id=$id');
	".Delivery_Cat($id);
	}

return $display;

} //Конец Delivery_Cat



function Vivod_pot()// вывод каталогов
{
global $SysValue;
$dis="
<script type=\"text/javascript\">
		<!--
		d4 = new dTree('d4');
		d4.add(0,-1,'<B>".$SysValue['Lang']['Category'][16]."</B>','admin_delivery_content.php?id=0');
        ".Delivery_Cat()."
		document.write(d4);
		//-->
	</script>";
return $dis;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/dtree.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="../java/dtree.js" type=text/javascript></SCRIPT>
</head>
<body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="#ffffff">
<div style="padding:10px">
<?echo Vivod_pot();?>
</div>
</body>
</html>

