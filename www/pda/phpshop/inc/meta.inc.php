<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Вывода Мета-тегов           |
+-------------------------------------+
*/

// генерация тегов для подкаталога
function ReturnCIDmeta2($n,$flag,$tip){
global $LoadItems;

if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon'];
elseif($tip == 1) $Shablon=ReturnData("","where id=".$n,$flag);
elseif($tip == 2) $Shablon=ReturnData("","where id=".$n,$flag.'_shablon');

if($tip !=1){
$cat=$LoadItems['Catalog'][$n]['parent_to'];
$Catalog=$LoadItems['Catalog'][$cat]['name'];
$Podcatalog=$LoadItems['Catalog'][$n]['name'];
$Title=$LoadItems['System'][$flag];

$Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
$Shablon=str_replace("@Podcatalog@", $Podcatalog, $Shablon);
$Shablon=str_replace("@System@", $Title, $Shablon);

if($flag == "keywords"){
$Generator=GetProductContent("","where id=".$n,"content");
$Shablon=str_replace("@Generator@", $Generator, $Shablon);}
}

return $Shablon;
}


// генерация тегов для каталога
function ReturnCIDImeta($n,$flag,$tip){
global $LoadItems;

if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon3'];
elseif($tip == 1) $Shablon=ReturnData("","where id=".$n,$flag);
elseif($tip == 2) $Shablon=ReturnData("","where id=".$n,$flag.'_shablon');
//exit($Shablon);


if($tip !=1){
$Catalog=$LoadItems['Catalog'][$n]['name'];
$Title=$LoadItems['System'][$flag];

$Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
$Shablon=str_replace("@System@", $Title, $Shablon);

if($flag == "keywords"){
$Generator=GetProductContent("","where id=".$n,"content");
$Shablon=str_replace("@Generator@", $Generator, $Shablon);}
}
return $Shablon;
}


// генерация тегов для товара
function ReturnUIDmeta($n,$flag,$tip){
global $LoadItems;

if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon2'];
elseif($tip == 1) $Shablon=ReturnData(2,"where id=".$n,$flag);
elseif($tip == 2) $Shablon=ReturnData(2,"where id=".$n,$flag.'_shablon2');

if($tip !=1){
$cat=$LoadItems['Product'][$n]['category'];
$parent=$LoadItems['Catalog'][$cat]['parent_to'];
$Catalog=$LoadItems['Catalog'][$parent]['name'];
$Podcatalog=$LoadItems['Catalog'][$cat]['name'];
$Product=$LoadItems['Product'][$n]['name'];
$Title=$LoadItems['System'][$flag];

$Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
$Shablon=str_replace("@Podcatalog@", $Podcatalog, $Shablon);
$Shablon=str_replace("@Product@", $Product, $Shablon);
$Shablon=str_replace("@System@", $Title, $Shablon);
if($flag == "keywords"){
$Generator=GetProductContent("2","where id=".$n,"content");
$Shablon=str_replace("@Generator@", $Generator, $Shablon);}
}

return $Shablon;
}


// Вывод данных
function ReturnData($from,$sql,$pole)// вывод меты для страниц
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name'.$from]." ".$sql."";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row[$pole];
@$SysValue['sql']['num']++;
return @$name;
}



switch ($SysValue['nav']['path']){

     case("shop"):
	  if($SysValue['nav']['nav']=="SIDI"){
	   $nameSTR=Vivod_page_meta(19,"where id='".$SysValue['nav']['id']."'","name","content");
       $title=$nameSTR[0]." - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	   $keywords=GetProductContent(19,"where id=".$SysValue['nav']['id'],"content");
	   $metas=strip_tags($nameSTR[1])." - ".$LoadItems['System']['title'];
	  }
	  
	 if($SysValue['nav']['nav']=="CID"){
$title_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['title_enabled'];
$descrip_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['descrip_enabled'];
$keywords_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['keywords_enabled'];
	   $title=ReturnCIDmeta2($SysValue['nav']['id'],"title",$title_enabled);
	   $keywords=ReturnCIDmeta2($SysValue['nav']['id'],"keywords",$keywords_enabled);
	   $metas=ReturnCIDmeta2($SysValue['nav']['id'],"descrip",$descrip_enabled);
	  }
	  /*
	  if($SysValue['nav']['nav']=="CIDI"){
$title_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['title_enabled'];
$descrip_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['descrip_enabled'];
$keywords_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['keywords_enabled'];
	   $title=ReturnCIDImeta($SysValue['nav']['id'],"title",$title_enabled);
	   $keywords=ReturnCIDImeta($SysValue['nav']['id'],"keywords",$keywords_enabled);
	   $metas=ReturnCIDImeta($SysValue['nav']['id'],"descrip",$descrip_enabled);
	  }*/
	  if($SysValue['nav']['nav']=="UID") {
	$title_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['title_enabled'];
	$descrip_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['descrip_enabled'];
    $keywords_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['keywords_enabled'];
		$title=ReturnUIDmeta($SysValue['nav']['id'],"title",$title_enabled);
		$metas=ReturnUIDmeta($SysValue['nav']['id'],"descrip",$descrip_enabled);
		$keywords=ReturnUIDmeta($SysValue['nav']['id'],"keywords",$keywords_enabled);
	  }
	 break;

     case("page"):
	 if(isset($SysValue['nav']['name']))
     {
	 $nameSTR=Vivod_page_meta_title("where link='".$SysValue['nav']['name']."'");
     $title=$nameSTR[0]." ".$LoadItems['System']['title'];
	 $metas=$nameSTR[1]." ".$LoadItems['System']['descrip'];
	 $keywords=$nameSTR[2]." ".$LoadItems['System']['keywords'];
	 }
	 break;
	 
	 case("news"):
	 if($SysValue['nav']['nav']=="ID"){
	 $nameSTR=Vivod_page_meta(8,"where id='".$SysValue['nav']['id']."'","zag","kratko");
     $title=$nameSTR[0]." - Новости - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas=$nameSTR[0];
	 $keywords=GetProductContent("8","where id='".$SysValue['nav']['id']."'","podrob");
	 }
	 else{
	 $nameSTR=Vivod_page_meta(8,"where id='".$SysValue['nav']['id']."'","zag","kratko");
     $title=$nameSTR[0]."Новости - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas=$nameSTR[0]."Новости - ".$LoadItems['System']['descrip']." ".$LoadItems['System']['name'];
	 $keywords=$nameSTR[0]." Новости ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     }
	 break;

	 case("gbook"):
	 $nameSTR=Vivod_page_meta(7,"where otvet!='' order by id desc LIMIT 0, 5","tema","otsiv");
	 $title=$nameSTR[0]." Вопросы и ответы - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas=$nameSTR[0]." Вопросы и ответы ".$LoadItems['System']['descrip'];
     $keywords=$LoadItems['System']['keywords'];
	 break;
	 
	 case("search"):
	 $title="Поиск по сайту - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
     $metas="Поиск по сайту - ".$LoadItems['System']['descrip'];
	 $keywords="Поиск по сайту ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     break;
	 
	 case("price"):
	 $title="Прайс-лист - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
     $metas="Прайс-лист - ".$LoadItems['System']['descrip'];
	 $keywords=$LoadItems['System']['keywords'];
     break;
	 
	 case("links"):
	 $title="Обмен ссылками - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
     $metas="Обмен ссылками - ".$LoadItems['System']['descrip'];
	 $keywords="Обмен ссылками, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("map"):
	 $title="Карта сайта - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Карта сайта - ".$LoadItems['System']['descrip'];
	 $keywords="Карта сайта, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("clients"):
	 $title="On-line проверка состояния заказа - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="On-line проверка состояния заказа - ".$LoadItems['System']['descrip'];
	 $keywords="On-line проверка состояния заказа, ".$LoadItems['System']['keywords'];
     break;
	 
	  case("users"):
	 $title="Личный кабинет - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Личный кабинет - ".$LoadItems['System']['descrip'];
	 $keywords="On-line проверка состояния заказа, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("opros"):
	 $title="Опросы - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Опросы - ".$LoadItems['System']['descrip'];
	 $keywords="Опросы, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("newtip"):
	 $title="Новые поступления - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Новые поступления - ".$LoadItems['System']['descrip'];
	 $keywords="Новинки, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("spec"):
	 $title="Спецпредложения - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Спецпредложения - ".$LoadItems['System']['descrip'];
	 $keywords="Спецпредложения, ".$LoadItems['System']['keywords'];
     break;
	 
	  case("newprice"):
	 $title="Распродажа - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="Распродажа - ".$LoadItems['System']['descrip'];
	 $keywords="Распродажа, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("print"):
     $title=$metas=$keywords=$LoadItems['Product'][$SysValue['nav']['id']]['name'];
     break;

default:
    $title=$LoadItems['System']['title']." - ".$LoadItems['System']['name'];
	$metas=$LoadItems['System']['descrip'];
	$keywords=$LoadItems['System']['keywords'];

}


// Автоподбор слов
function GetProductContent($from,$sql,$f1){
return 0;
}

// Определяем переменые
@$SysValue['other']['pageTitl']= $title;
@$SysValue['other']['pageReg']= $RegTo['RegisteredTo'];
@$SysValue['other']['pageDomen']= $RegTo['DomenLocked'];
@$SysValue['other']['pageProduct']= $RegTo['ProductName'];
@$SysValue['other']['pageDesc']= $metas;
@$SysValue['other']['pageKeyw']= $keywords;
$SysValue['other']['pageCss']=$SysValue['dir']['templates'].chr(47)."pda".chr(47).$SysValue['css']['default'];


function Vivod_page_meta($from,$sql,$f1,$f2)// вывод меты для страниц
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name'.$from]." ".$sql."";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
if($row[$f1]) $name=$row[$f1];
$keywords=$row[$f2];
@$ar=array(@$name,@$keywords);
@$SysValue['sql']['num']++;
return @$ar;
}

function Vivod_page_meta_title($sql)// вывод меты для страниц
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name11']." ".$sql."";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

if(empty($row["title"])) $name=$row["name"];
  else $name=$row["title"];

$description=$row["description"];
$keywords=$row["keywords"];
@$ar=array($name,$description,$keywords);
@$SysValue['sql']['num']++;
return @$ar;
}


function Kratko_metas($n,$flag)// выбор товаров из данного подкатолога for metas
{
global $LoadItems,$SysValue;
$cat=$LoadItems['Podcatalog'][$n]['parent_to'];
if($flag==1)
@$tit=$LoadItems['Catalog'][$cat]['name']." ".$LoadItems['Podcatalog'][$n]['name'];
elseif($flag==2)
@$tit=$LoadItems['Podcatalog'][$n]['name']." ".$LoadItems['Catalog'][$cat]['name'];
else @$tit=$LoadItems['Catalog'][$n]['name'];
return @$tit;
}
?>
