<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации XML Yandex        |
+-------------------------------------+
*/

// параметры для выгрузки
/*<Тип обновления>*. Возможны следующие типы обновления:

    * 0 - замещающее (подразумевают полное удаление всех текущих ТП магазина и заливку тех, информация о которых имеется в файле);
    * 1 - редактирующее (перед заливкой новых - удаляются лишь те существующие ТП, для которых в пришедшем файле имеется новая информация);
    * 2 - удаляющее (приводит к удалению из базы тех ТП, номера которых присланы в файле).
*/
$update_type = 0;

/*<тип доставки>  - Вы можете указать один из следующих типов доставки:

    * 0 - нет доставки
    * 1 - курьером по городу;
    * 2 - почтой по городу;
    * 3 - курьером по стране;
    * 4 - почтой по стране;
    * 5 - курьером по городу, почтой по стране;
    * 6 - курьером по нескольким городам, почтой по стране;
    * custom (не более 35 знаков с пробелами).*/
$delivery_type = 0;

/*<стоимость доставки> - здесь Вы можете указать стоимость доставки в валюте товара (0 - в случае бесплатной доставки). 
Если ваша доставка не бесплатна, но ее стоимость зависит от нескольких параметров, 
поставьте в это поле знак +. Он будет обозначать, что доставка происходит за дополнительные деньги.*/
$delivery_price = 0;

// максимальная стоимость за клик.. выставляется в админпанеле в разделе настройки YML для каждого товара в поле CBID 
// либо можно задать самостоятельно тут в виде строки 
// @$bid_str.='  mpc="27.30" ';   
// вместо нижней
@$bid_str.='  mpc="'.$PRODUCT[$key]['yml_bid_array']['cbid'].'" ';
///////////////////////////









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

$_Array=array(
"а"=>"a",
"б"=>"b",
"в"=>"v",
"г"=>"g",
"д"=>"d",
"е"=>"e",
"ё"=>"e",
"ж"=>"gh",
"з"=>"z",
"и"=>"i",
"й"=>"i",
"к"=>"k",
"л"=>"l",
"м"=>"m",
"н"=>"n",
"о"=>"o",
"п"=>"p",
"р"=>"r",
"с"=>"s",
"т"=>"t",
"у"=>"u",
"ф"=>"f",
"х"=>"h",
"ц"=>"c",
"ч"=>"ch",
"ш"=>"sh",
"щ"=>"sh",
"ъ"=>"i",
"ы"=>"yi",
"ь"=>"i",
"э"=>"a",
"ю"=>"u",
"я"=>"ya",
"А"=>"a",
"Б"=>"b",
"В"=>"v",
"Г"=>"g",
"Д"=>"d",
"Ё"=>"e",
"Ж"=>"gh",
"З"=>"z",
"И"=>"i",
"Й"=>"i",
"К"=>"k",
"Л"=>"l",
"М"=>"m",
"Н"=>"n",
"О"=>"o",
"П"=>"п",
"Р"=>"r",
"С"=>"s",
"Т"=>"t",
"У"=>"u",
"Ф"=>"f",
"Х"=>"h",
"Ц"=>"c",
"Ч"=>"ch",
"Ш"=>"sh",
"Щ"=>"sh",
"Э"=>"a",
"Ю"=>"u",
"Я"=>"ya",
"."=>"_",
"$"=>"i",
"%"=>"i",
"&"=>"and"
);

$chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

foreach($chars as $val)
if(empty($_Array[$val])) @$new_str.=$val;

return @$new_str;
}

// Вывод каталогов
function  Catalog()
 {
global $SysValue;
$sql="select id,name from ".$SysValue['base']['table_name']." where parent_to=0 and yml='1' order by id";
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
$sql="select id,name,parent_to from ".$SysValue['base']['table_name']." where parent_to!=0 and yml='1' order by id";
$result=mysql_query($sql);
$FROM=split(",",$SysValue['yml']['catalog']);
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


function DispValuta($n)// вывод валют
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." where 1";
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
$len=strlen($str);
$str = htmlspecialchars(strip_tags($str),ENT_QUOTES,'cp1251');

if($len<$a) return $str;

for ($i = 1; $i <= $a; $i++) {
	if($str{$i} == ".") $T=$i;
}
if($T<1) return substr($str, 0, $a)."...";
  else return substr($str, 0, $T+1);
}

$SYSTEM=Systems();
 
// Вывод продуктов
function  Product()
 {
global $SysValue,$SYSTEM;
$sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and yml='1' order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=htmlspecialchars($row['name'],ENT_QUOTES,'cp1251');
	$category=$row['category'];
        $uid=$row['uid'];
	$price=$row['price'];
	if($row['p_enabled'] == 1) $p_enabled="good";
	else $p_enabled="bad";
	$description=mySubstr($row['description'],300);

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
		
	}
} //Если прислали баз. валюту
//получаем исходную цену

    
	$price=($price+(($price*$SYSTEM['percent'])/100));
	$formatPrice = unserialize($SYSTEM['admoption']);
    $format=$formatPrice['price_znak'];
	$price=round($price,$format);

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
<!DOCTYPE torg_price SYSTEM "shops.dtd">
<torg_price date="'.date('Y-m-d H:m').'">
<shop>
<update_type>'.$update_type.'</update_type>
<shopname>'.$SYSTEM['name'].'</name>
<company>'.$SYSTEM['company'].'</company>
<url>http://'.$SERVER_NAME.'</url>

<currencies>
');
  foreach($VALUTA as $v)
  $XML.='<currency id="'.$v['iso'].'" rate="'.(1/$v['kurs']).'"/>
  ';
$XML.=('
</currencies>

<categories>');
while (list($key, $val) = @each($CATALOG)) 
$XML.= ('
<category id="'.$key.'" parentId="0">'.$CATALOG[$key]['name'].'</category>');

while (list($key, $val) = @each($PODCATALOG)) 
$XML.= ('
<category id="'.$key.'" parentId="'.$PODCATALOG[$key]['parent_to'].'">'.$PODCATALOG[$key]['name'].'</category>');

$XML.=('</categories>
<offers>');
while (list($key, $val) = @each($PRODUCT)) {
    


   
$XML.= ('
<offer id="'.$PRODUCT[$key]['id'].'" type="'.$PRODUCT[$key]['p_enabled'].'" '.$bid_str.'>
 <url>http://'.$SERVER_NAME.'/shop/UID_'.$PRODUCT[$key]['id'].'.html?from=yml</url>
      <price>'.$PRODUCT[$key]['price'].'</price>
      <currencyId>'.$VALUTA[$SYSTEM['dengi']]['iso'].'</currencyId>
      <categoryId>'.$PRODUCT[$key]['category'].'</categoryId>
      <picture>http://'.$SERVER_NAME.$PRODUCT[$key]['picture'].'</picture>
      <name>'.$PRODUCT[$key]['name'].'</name>
      <description>'.$PRODUCT[$key]['description'].'</description>
      <descmore>http://'.$SERVER_NAME.'/shop/UID_'.$PRODUCT[$key]['id'].'.html?from=yml</descmore>
      <delivery_type>'.$delivery_type.'</delivery_type>
      <delivery_price>'.$delivery_price.'</delivery_price>
    </offer>
');
}
$XML.= ('</offers>
</shop>
</torg_price>');

echo $XML;
?>
