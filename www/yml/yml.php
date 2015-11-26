<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации XML Yandex        |
+-------------------------------------+
*/
$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("modules");

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

// Promo - преобразования
function NameToLatin($str){
$str=strtolower($str);
$str=str_replace("/", "", $str);
$str=str_replace("\\", "", $str);
$str=str_replace("(", "", $str);
$str=str_replace(")", "", $str);
$str=str_replace(":", "", $str);
$str=str_replace("-", "", $str);
$str=str_replace(" ", "", $str);



foreach($chars as $val)
if(empty($_Array[$val])) @$new_str.=$val;

return @$new_str;
}

// Вывод каталогов
function  Catalog()
 {
global $SysValue;
$sql="select id,name from ".$SysValue['base']['table_name']." where parent_to=0 order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
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
$sql="select id,name,parent_to from ".$SysValue['base']['table_name']." where parent_to!=0   order by id";
$result=mysql_query($sql);
$FROM=explode(",",$SysValue['yml']['catalog']);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
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

function Vendor($vendor)// проверка вывода вендора для этого каталога
{
global $SysValue;
$sql="select name from ".$SysValue['base']['table_name4']." where id='$vendor'";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$name=$row['name'];
return $name;
}

function DispValuta($n)// вывод валют
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." where id=$n";
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

$SYSTEM=Systems();
 
// Вывод продуктов
function  Product()
 {
global $SysValue,$SYSTEM;
$sql="select * from ".$SysValue['base']['table_name2']." where enabled='1'  order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
    	    	
	$id=$row['id'];
	$name=htmlspecialchars($row['name'],ENT_QUOTES,'cp1251');
	$category=$row['category'];
    $uid=$row['uid'];
	$price=$row['price'];
	if($row['p_enabled'] == 1) $p_enabled="true";
	else $p_enabled="false";
	$d=mySubstr($row['description'],200);
	$description=htmlspecialchars(strip_tags($d),ENT_QUOTES,'cp1251');

	$mod_price=strval($price);

	switch (strlen($mod_price)) {
		case 4:
			$mod_price=substr($mod_price,0,1).' '.substr($mod_price,1,strlen($mod_price)-1);
			break;
		case 5:
			$mod_price=substr($mod_price,0,2).' '.substr($mod_price,2,strlen($mod_price)-2);
			break;	
		case 6:
			$mod_price=substr($mod_price,0,3).' '.substr($mod_price,3,strlen($mod_price)-3);
			break;				
	}
	$price=$mod_price;
	//echo  'qq'.$price.'qq';	
//получаем исходную цену
	$baseinputvaluta=$row['baseinputvaluta'];
	$defvaluta=$SYSTEM['dengi'];
if ($baseinputvaluta) { //Если прислали баз. валюту
	if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
		$sql2="select kurs from ".$SysValue['base']['table_name24']." where id=".$baseinputvaluta;
		$result2=mysql_query($sql2);
		$row2 = mysql_fetch_array($result2);
		$vkurs=$row2['kurs'];
		$price=$price/$vkurs; //Приводим цену в базовую валюту
		$formatPrice = unserialize($SYSTEM['admoption']);
		$format=$formatPrice['price_znak'];
		$price=round($price,$format);
	}
} //Если прислали баз. валюту
//получаем исходную цену

	$array=array(
	"id"=>"$id",
	"category"=>"$category",
	"name"=>"$name",
	"picture"=>$row['pic_small'],
	"price"=>"$price",
	"p_enabled"=>"$p_enabled",
	"yml_bid_array"=>unserialize($row['yml_bid_array']),
	"uid"=>"$uid",
	"description"=>$description
	);
	$Products[$id]=$array;
	}
return $Products;
 }

$CATALOG=Catalog();
$PODCATALOG=Podcatalog();
$PRODUCT=Product();
//$SYSTEM=Systems();
$VALUTA=DispValuta($SYSTEM['dengi']);

$XML=('<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:m').'">

<shop>
<name>'.$SYSTEM['name'].'</name>
<company>'.$SYSTEM['company'].'</company>
<url>http://'.$_SERVER['SERVER_NAME'].'</url>

<currencies>
');
  foreach($VALUTA as $v)
  $XML.='<currency id="'.$v['iso'].'" rate="1"/>';
$XML.=('
</currencies>

<categories>');
while (list($key, $val) = @each($CATALOG)) 
$XML.= ('
<category id="'.$key.'">'.$CATALOG[$key]['name'].'</category>');

while (list($key, $val) = @each($PODCATALOG)) 
$XML.= ('
<category id="'.$key.'" parentId="'.$PODCATALOG[$key]['parent_to'].'">'.$PODCATALOG[$key]['name'].'</category>');

$XML.=('</categories>
<offers>');
while (list($key, $val) = @each($PRODUCT)) {
    
	$bid_str="";

   // Если есть bid
   if($PRODUCT[$key]['yml_bid_array']['bid_enabled'] == 1) $bid_str='  bid="'.$PRODUCT[$key]['yml_bid_array']['bid'].'" ';
   
   // Если есть cbid
   if($PRODUCT[$key]['yml_bid_array']['cbid_enabled'] == 1) @$bid_str.='  cbid="'.$PRODUCT[$key]['yml_bid_array']['cbid'].'" ';
   
$XML.= ('
<offer id="'.$PRODUCT[$key]['id'].'" available="'.$PRODUCT[$key]['p_enabled'].'" '.$bid_str.'>
 <url>http://'.$_SERVER['SERVER_NAME'].'/shop/UID_'.$PRODUCT[$key]['id'].'.html?from=yml</url>
      <price>'.$PRODUCT[$key]['price'].'</price>
      <currencyId>'.$VALUTA[$SYSTEM['dengi']]['iso'].'</currencyId>
      <categoryId>'.$PRODUCT[$key]['category'].'</categoryId>
      <picture>http://'.$_SERVER['SERVER_NAME'].$PRODUCT[$key]['picture'].'</picture>
      <name>'.$PRODUCT[$key]['name'].'</name>
      <description>'.$PRODUCT[$key]['description'].'</description>
    </offer>
');
}
$XML.= ('</offers>
</shop>
</yml_catalog>');

echo $XML;
?>
