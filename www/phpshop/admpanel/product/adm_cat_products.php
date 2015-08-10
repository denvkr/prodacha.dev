<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];

require("../language/".$Lang."/language.php");

function ReturnOdnotip($n){
global $SysValue;
$sql="select odnotip from ".$SysValue['base']['table_name2']." where id=$n";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$odnotip=$row['odnotip'];
return $odnotip;
}

$Odnotip=ReturnOdnotip($productID);
$ArrayOdnotip=explode(",",$Odnotip);

// Собираем массив товаров
foreach($ArrayOdnotip as $value){
$Product[$value]=ReturnProductData($value);
}

// Собираем массив подкаталогов
foreach($Product as $k=>$value){
$Podcatalog[$value['category']]=ReturnCatalogData($value['category']);
}

// Собираем массив каталогов
foreach($Podcatalog as $k=>$value){
$Catalog[$value['parent_to']]=ReturnCatalogData($value['parent_to']);
}


function ReturnCatalogData($n){
global $SysValue;
$sql="select id,name,parent_to from ".$SysValue['base']['table_name']." where id=$n";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$id=$row['id'];
	$name=$row['name'];
	$parent_to=$row['parent_to'];
	$array=array(
	"id"=>$n,
	"name"=>$name,
	"parent_to"=>$parent_to
	);
return $array;
}

function ReturnProductData($n){
global $SysValue;
$sql="select id,name,category from ".$SysValue['base']['table_name2']." where id=$n";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$name=$row['name'];
$category=$row['category'];
$array=array(
"category"=>$category,
"id"=>$n,
"name"=>$name
);
return $array;
}


function Vivod_pot()// вывод каталогов
{
global $Catalog,$Podcatalog,$Product;
foreach($Catalog as $value){
	@$dis.="
	d2.add(".$value['id'].",0,'".$value['name']."','');";
	foreach($Podcatalog as $v){
	if($value['id'] == $v['parent_to'])
	@$dis.="d2.add(".$v['id'].",".$value['id'].",'".$v['name']."','');";
	       foreach($Product as $p){
		   if($v['id'] == $p['category'])
	       @$dis.="d2.add(".$p['id'].",".$v['id'].",'".$p['name']."','');";
		   }
	}
}
$disp="
<script type=\"text/javascript\">
		try{
		d2 = new dTree('d2');
		d2.add(0,'-1','<b>Каталог связанных товаров</b>');
        ".$dis."
		document.write(d2);
		d2.openAll()
		}catch(e){}
	</script>
";
return $disp;
}


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title><?=$SysValue['Lang']['Category'][1]?></title>
<LINK rel="StyleSheet" href="dialog.css" type="text/css">
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/dtree.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript1.2 src="../java/dtree2.js" type=text/javascript></SCRIPT>
<script language="JavaScript">
// Закрывает окно
function CloseWindow() {
window.close();
}
</script>

</head>

<body bottommargin="0" leftmargin="5" topmargin="0" rightmargin="5">
<div align="center" style="padding:5"><a href="javascript: window.d2.openAll();"><?=$SysValue['Lang']['Category'][5]?></a> | <a href="javascript: window.d2.closeAll();"><?=$SysValue['Lang']['Category'][6]?></a> | <a href="javascript: window.close()"><?=$SysValue['Lang']['Category'][7]?></a></div>
<table cellpadding="0" cellspacing="0" bgcolor="ffffff" style="border: 2px;border-style: inset;" width="100%" height="350">
<tr>
	<td valign="top">
<?
echo "<pre>";
//print_r($Product);
//print_r($Podcatalog);
//print_r($Catalog);

echo Vivod_pot();?>
	</td>
</tr>
</table>
<div align="center" style="padding:5"><a href="javascript: window.d2.openAll();"><?=$SysValue['Lang']['Category'][5]?></a> | <a href="javascript: window.d2.closeAll();"><?=$SysValue['Lang']['Category'][6]?></a> | <a href="javascript: window.close()"><?=$SysValue['Lang']['Category'][7]?></a></div>
</body>
</html>
