<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации XML Rambler       |
+-------------------------------------+
*/


// Парсируем установочный файл
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);


// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES 'cp1251'");

// Настройки
function Systems()// вывод настроек
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
foreach($row as $k=>$v)
$array[$k]=$v;
return $array;
}


// Вывод каталогов
function  Catalog()
 {
global $SysValue;
$sql="select id,name_rambler from ".$SysValue['base']['table_name']." where parent_to=0 and yml='1' order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name_rambler'];
	$array=array(
	"id"=>"$id",
	"name"=>"$name"
	);
	$Catalog[$id]=$array;
	}
return $Catalog;
 }

// Вывод подкаталогов
function  Podcatalog()
 {
global $SysValue;
$sql="select id,name_rambler,parent_to from ".$SysValue['base']['table_name']." where parent_to!=0 and yml='1' order by id";
$result=mysql_query($sql);
$FROM=explode(",",$SysValue['yml']['catalog']);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name_rambler'];
	$parent_to=$row['parent_to'];
	$array=array(
	"id"=>"$id",
	"name"=>"$name",
	"parent_to"=>"$parent_to"
	);
	$Catalog[$id]=$array;
	}
return $Catalog;
 }

// Парсер для изображений
function ImgParser($img){
$array=split("\"",$img);
while (list($key, $value) = each($array))
    //if (preg_match("/\//",$value))
  if (preg_match("/Image/",$value))
    return $array[$key];
}


function DispValuta()// вывод валют
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." where enabled='1' order by num";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$name=$row['name'];
	$code=$row['code'];
	$iso=$row['iso'];
	$kurs=$row['kurs'];
	$array=array(
	"id"=>$id,
	"name"=>$name,
	"code"=>$code,
	"iso"=>$iso,
	"kurs"=>$kurs
	);
	$Valuta[$id]=$array;
	}
return $Valuta;
}

// Отрезаем до точки
function mySubstr($str,$a){
for ($i = 1; $i <= $a; $i++) {
	if($str{$i} == ".") $T=$i;
}
return substr($str, 0, $T+1);
}
 
// Вывод продуктов
function  Product()
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and yml='1' order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=htmlspecialchars($row['name'],ENT_QUOTES,'cp1251');
	$category=$row['category'];
	$price=$row['price'];
	
	if($row['p_enabled'] == 1) $p_enabled="true";
	else $p_enabled="false";
	
	$d=mySubstr($row['description'],200);
	$description=htmlspecialchars(strip_tags($d),ENT_QUOTES,'cp1251');
	$array=array(
	"id"=>"$id",
	"category"=>"$category",
	"name"=>"$name",
	"picture"=>$row['pic_small'],
	"price"=>"$price",
	"p_enabled"=>"$p_enabled",
	"description"=>$description
	);
	$Products[$id]=$array;
	}
return $Products;
 }

$CATALOG=Catalog();
$PODCATALOG=Podcatalog();
$PRODUCT=Product();
$SYSTEM=Systems();
$VALUTA=DispValuta();

$XML=('<?xml version="1.0" encoding="windows-1251"?>
<yml_catalog date="'.date('Y-m-d H:m').'">

<shop>
<name>'.$SYSTEM['name'].'</name>
<company>'.$SYSTEM['company'].'</company>
<url>http://'.$SERVER_NAME.'</url>

<currencies>
');
  foreach($VALUTA as $v)
  $XML.='<currency id="'.$v['iso'].'" rate="'.$v['kurs'].'"/>';
$XML.=('
</currencies>');
while (list($key, $val) = @each($CATALOG)) 
$XML2.= ('
<category id="'.$key.'">'.$CATALOG[$key]['name'].'</category>');

while (list($key, $val) = @each($PODCATALOG)) 
$XML2.= ('
<category id="'.$key.'" parentId="'.$PODCATALOG[$key]['parent_to'].'">'.$PODCATALOG[$key]['name'].'</category>');

$XML.=('
<offers>');
while (list($key, $val) = @each($PRODUCT)){
$CategoryId=$PRODUCT[$key]['category'];
$MainCategoryId=$PODCATALOG[$CategoryId]['parent_to'];;
$XML.= ('
<offer id="'.$PRODUCT[$key]['id'].'">
<category>'.$CATALOG[$MainCategoryId]['name'].' >> '.$PODCATALOG[$CategoryId]['name'].'</category>
<title>'.$PRODUCT[$key]['name'].'</title>
<price>'.$PRODUCT[$key]['price'].'</price>
<currencyId>'.$VALUTA[$SYSTEM['dengi']]['iso'].'</currencyId>
<url>http://'.$SERVER_NAME.'/shop/UID_'.$PRODUCT[$key]['id'].'.html?from=rambler</url>
<img>http://'.$SERVER_NAME.$PRODUCT[$key]['picture'].'</img>
<descript>'.$PRODUCT[$key]['description'].'</descript>
</offer>
');
}
$XML.= ('</offers>
</shop>
</yml_catalog>');

echo $XML;
?>
