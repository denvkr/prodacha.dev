<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Поиска                      |
+-------------------------------------+
*/

function SearchJurnalWrite($name,$num,$cat,$set){// Запись в журнал
global $SERVER_NAME,$SysValue,$HTTP_REFERER ;
$sql="INSERT INTO ".$SysValue['base']['table_name18']." VALUES ('','$name','$num','".date("U")."','".$HTTP_REFERER ."','$cat','$set')";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
}


function DispCategory($cat)// вывод категорий
{
global $LoadItems,$SysValue;
$sql="select id,name from ".$SysValue['base']['table_name']." where parent_to=0 order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$name=$row['name'];
	if ($id==$cat)
	   {
	   $sel="selected";
	   }
	   else
	      {
		  $sel="";
		  }
    @$dis.="<option value=\"$id\" $sel>$name</option>\n";
	}
@$disp="<select name=\"cat\" size=1>
<option value=\"0\">Все разделы</option>
$dis
</select>
";
return @$disp;
}

function DispCategoryParent($cat)// вывод категорий для каталогов
{
global $LoadItems,$SysValue;
$sql="select id from ".$SysValue['base']['table_name']." where parent_to=".$cat;
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    @$dis.=" or category=$id";
	}
@$disp="(category=0".$dis.")";
@$SysValue['sql']['num']++;
return @$disp;
}


// Secure Fix 3.0
function CleanSearch($search){
$search=eregi_replace("union","",$search);
$search=eregi_replace("select","",$search);
$search=eregi_replace("insert","",$search);
$search=eregi_replace("update","",$search);
$search=eregi_replace("delete","",$search);
$search=eregi_replace("'","",$search);
return $search;
}


function Page_search($words,$cat)// Создание страниц
{
global $SysValue,$LoadItems,$p,$set,$pole;
$words=trim($words);
$num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;

$words=TotalClean($words,2);
$words=CleanSearch($words);// Secure Fix 3.0

if(empty($pole)) $pole=1;
if(empty($set)) $set=1;

if($set == 1){
$_WORDS=explode(" ",$words);

switch($pole){
     
	 case(1):
     foreach($_WORDS as $w)
	 @$sort.="name REGEXP '$w' and ";
	 break;
	 
	 case(2):
     foreach($_WORDS as $w)
	 @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or uid = '$w' or id = '$w') and ";
	 break;
}}
else{
// Разделяем слова
$_WORDS=explode(" ",$words);


switch($pole){
     
	 case(1):
     foreach($_WORDS as $w)
@$sort.="(name REGEXP '$w' or uid = '$w' or id = '$w') or ";
	 break;
	 
	 case(2):
	 foreach($_WORDS as $w)
	 @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or uid = '$w' or id = '$w') or ";
	 break;
}}



// По категориям
if($cat!=0)
$string=DispCategoryParent($cat)." and ";
$prewords=PreSearchBase($words);
// Все страницы
if($p=="all") {
  $sql="select * from ".$SysValue['base']['table_name2']." where  $string $sort id !=0 $prewords and enabled='1' GROUP BY name";
}
else while($q<$p)
  {
  if($set==1)
  $sql="select * from ".$SysValue['base']['table_name2']." where  $string $sort id!=0 $prewords and enabled='1' GROUP BY name LIMIT $num_ot, $num_row";
  else $sql="select * from ".$SysValue['base']['table_name2']." where  $string $sort id=0 $prewords and enabled='1' GROUP BY name LIMIT $num_ot, $num_row";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
@$SysValue['sql']['num']++;
return $sql;
//exit($sql);
}

function SearchNav($words,$cat)// Навигация 
{
global $SysValue,$LoadItems,$set,$p,$pole;

if(empty($pole)) $pole=1;
if(empty($set)) $set=1;

// Все страницы
if($p=="all")
$productSortD="sortActiv";
else $p=TotalClean($p,1);

// По категориям
if($cat!=0)
$string=DispCategoryParent($cat)." and ";

$words=TotalClean($words,2);


if($set == 1){
$_WORDS=explode(" ",$words);

switch($pole){
     
	 case(1):
     foreach($_WORDS as $w)
	 @$sort.="name REGEXP '$w' and ";
	 break;
	 
	 case(2):
     foreach($_WORDS as $w)
	 @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or uid = '$w' or id = '$w') and ";
	 break;
}}
else{
// Разделяем слова
$_WORDS=explode(" ",$words);


switch($pole){
     
	 case(1):
     foreach($_WORDS as $w)
@$sort.="(name REGEXP '$w' or uid = '$w' or id = '$w') or ";
	 break;
	 
	 case(2):
	 foreach($_WORDS as $w)
	 $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or uid = '$w' or id = '$w') or ";
	 break;
}}


$num_row=$LoadItems['System']['num_row'];
$i=1;
if($set == 1)
$num_page=NumFrom("table_name2","where $string $sort id!=0 and enabled='1'");
else $num_page=NumFrom("table_name2","where $string $sort id=0 and enabled='1'");

// пишем в журнал
$SearchJurnalWrite=SearchJurnalWrite($words,$num_page,$cat,$set);

$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
   if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
    @$navigat.="
	     <a href=\"./?words=".$words."&pole=".$pole."&set=".$set."&p=".$i."&cat=".$cat."\">".$pageOt."-".$pageDo."</a> | ";
	}
	else{
	
     if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	 @$navigat.="
	     <b>".$pageOt."-".$pageDo."</b> | ";
	}
	$i++;
	}
 if($num>1)
  {
 if($p>=$num){$p_to=$i-1;}else{$p_to=$p+1;}
 $nava=$SysValue['lang']['page_now'].":
<a href=\"./?words=".$words."&set=".$set."&p=".($p-1)."&cat=".$cat."\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat<a href=\"./?words=".$words."&set=".$set."&p=".$p_to."&cat=".$cat."\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"./?words=".$words."&set=".$set."&p=all&cat=".$cat."&pole=".$pole."\" class=\"$productSortD\">Все позиции</a>
		";
	}
return @$nava;
}

function PreSearchBase($words){
global $SysValue,$LoadItems;
$sql="select uid from ".$SysValue['base']['table_name26']." where name REGEXP 'i".$words."i'";
@$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result)){
     $uid=$row['uid'];
	 $uids=explode(",",$uid);
	 foreach($uids as $v) @$string.=" or id=$v";
}
return $string;
}


function DisSearch($words,$cat)// поиск по базе
{
global $SysValue,$LoadItems,$set,$p,$pole,$_SESSION;
$words=TotalClean($words,2);
$cat=TotalClean($cat,1);
$i=0;
$j=1;
$n=0;
if($words!="")
{
$sql=Page_search($words,$cat);
$result=mysql_query($sql);

while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$uid=$row['uid'];
	$name=$row['name'];
	$category=$row['category'];
	$price=$row['price'];
    $sklad=$row['sklad'];
	$priceNew=$row['price_n'];
	$price=($price+(($price*$LoadItems['System']['percent'])/100));
	$title_enabled=$row['title_enabled'];
	$descrip_enabled=$row['descrip_enabled'];
	$keywords_enabled=$row['keywords_enabled'];
	$datas=$row['datas'];
	$page=explode(",",$row['page']);
	$user=$row['user'];
	$pic_small=$row['pic_small'];
	$pic_big=$row['pic_big'];
	
	
	// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $polePrice="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$polePrice];
	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
	   }
	}
	
	
	if($priceSklad==0){// Если товар на складе
// Если нет новой цены
if(empty($priceNew)){
$SysValue['other']['productPrice']=GetPriceValuta($price);
$SysValue['other']['productPriceRub']= "";
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($price);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($priceNew)." ".GetValuta()."</strike>";
}}else{ // Товар по заказ
$SysValue['other']['productPrice']=$SysValue['lang']['sklad_no'];
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
}
	
	// Проверка на нулевую цену
	if(!is_numeric($row['price']))
	$sklad = 1;
	
	$uid=$row['uid'];
	$description=$row['description'];
	


// Пустая картинка
if(empty($pic_small))
$pic_small="images/shop/no_photo.gif";
	
// Определяем переменые
$SysValue['other']['productPriceMoney']= $LoadItems['System']['dengi'];
$SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
$SysValue['other']['productName']= $name;
$SysValue['other']['productArt']= $uid;
// Описание
$SysValue['other']['productDes']= mySubstr($description,300);

$SysValue['other']['productId']= $id;
$SysValue['other']['productUid']= $id;
//$SysValue['other']['productWords']= $words;
$SysValue['other']['productImgWidth']= $LoadItems['System']['width_icon'];
$SysValue['other']['productValutaName']= GetValuta();
$SysValue['other']['productImg']= $pic_small;
$SysValue['other']['productImgWidth']= $Options['width_kratko'];

if($set==1) $SysValue['other']['searchSetA']="checked";
elseif($set==2) $SysValue['other']['searchSetB']="checked";
   else $SysValue['other']['searchSetA']="checked";

if($pole==1) $SysValue['other']['searchSetC']="checked";
elseif($pole==2) $SysValue['other']['searchSetD']="checked";
   else $SysValue['other']['searchSetC']="checked";
   



@$dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_1']);
$td="<tr><TD colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
$td.="<tr ><td width=\"100%\" valign=\"top\">"; $j++; $td2="</td>";
$td2.="</TD></tr>";
@$disp.=$td.$dis.$td2;


	$i++;
	}

//if($i==0)$i="ничего не найдено";

// Определяем переменые
$SysValue['other']['catalog']= $SysValue['lang']['catalog'];
//@$SysValue['other']['catalogCat']= $LoadItems['Product'][$id]['category'];;
$SysValue['other']['searchString']=$words;
$SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
$SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
$SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
$SysValue['other']['productPageThis']=$p;
$SysValue['other']['productPageDis']=$disp;
$SysValue['other']['searchPageNav']=SearchNav($words,$cat);
$SysValue['other']['searchPageCategory']=DispCategory($cat);

// Подключаем шаблон
$disp=ParseTemplateReturn($SysValue['templates']['search_page_list']);
}
else
    {
	
	if($set==1) $SysValue['other']['searchSetA']="checked";
elseif($set==2) $SysValue['other']['searchSetB']="checked";
   else $SysValue['other']['searchSetA']="checked";

if($pole==1) $SysValue['other']['searchSetC']="checked";
elseif($pole==2) $SysValue['other']['searchSetD']="checked";
   else $SysValue['other']['searchSetC']="checked";
	
	$dis="Ничего не найдено".$words;
	$SysValue['other']['searchPageCategory']=DispCategory($cat);
	// Подключаем шаблон
    $disp=ParseTemplateReturn($SysValue['templates']['search_page_list']);
	}
return $disp;
}
?>
