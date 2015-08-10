<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Вывода Информации           |
+-------------------------------------+
*/


function DispNav()// Навигация 
{
global $SysValue,$LoadItems,$_POST;

// Кол-во страниц, далее идут точки
$nav_len=3;

$id=TotalClean($SysValue['nav']['id'],1);
$v=$SysValue['nav']['query']['v'];
$p=$SysValue['nav']['page']; 
if(@!$p and !@$_POST['priceSearch']) $p=1;


// Хвост
if(!empty($SysValue['nav']['querystring']))
$querystring="?".$SysValue['nav']['querystring'];
  else $querystring="";

// Все страницы
if($p=="ALL" or isset($_POST['priceSearch']))
$productSortD="sortActiv";
else $p=TotalClean($p,1);

// Сортировка по характеристикам
if(is_array($v)){
foreach($v as $key=>$value){
$hash=$key."-".$value;
@$sort.=" and vendor REGEXP 'i".$hash."i'";
}
}

// Для каждого каталога
if($LoadItems['Catalog'][$id]['num_cow']>0)
$num_row=$LoadItems['Catalog'][$id]['num_cow'];
else $num_row=$LoadItems['System']['num_row'];


$num_page=NumFrom("table_name2","where category=$id and enabled='1' and parent_enabled='0'");

$i=1;
$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
	
	if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	
	
	if($i>($p-$nav_len) and $i<($p+$nav_len))
    @$navigat.="
	     <a href=\"./CID_".$id."_".$i.".html".$querystring."\">".$pageOt."-".$pageDo."</a> | ";
		 else if($i-($p+$nav_len)<3 and (($p-$nav_len)-$i)<3) @$navigat.=".";
		 
		 
		 
		 
		 
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
<a href=\"./CID_".$id."_".($p-1).".html".$querystring."\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat<a href=\"./CID_".$id."_".$p_to.".html".$querystring."\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"./CID_".$id."_ALL.html".$querystring."\" class=\"$productSortD\">Все позиции</a>
		";
	}
return @$nava;
}



function PageDisp($n)// Создание страниц
{
global $SysValue,$LoadItems,$_POST;
$sort="";
$n=TotalClean($n,1);
$p=$SysValue['nav']['page']; if(@!$p) $p=1;
@$v=$SysValue['nav']['query']['v'];
@$s=TotalClean($SysValue['nav']['query']['s'],1);
@$f=TotalClean($SysValue['nav']['query']['f'],1);

if($p!="ALL")
$p=TotalClean($p,1);

if($LoadItems['Podcatalog'][$n]['num_cow']>0)
$num_row=$LoadItems['Podcatalog'][$n]['num_cow'];
else $num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;


// Все страницы
if($p=="ALL") {
$sql="select id,uid,name,description,vendor,vendor_array,parent from ".$SysValue['base']['table_name2']." where (category=$n and enabled='1' and parent_enabled='0') ".$sort." ".@$string;
}

while($q<$p)
  {
   $sql="select id,uid,name,description,vendor,vendor_array,parent from ".$SysValue['base']['table_name2']." where category=$n and enabled='1' and parent_enabled='0' $sort ".@$string." LIMIT $num_ot, $num_row";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
@$SysValue['sql']['num']++;
return $sql;
}


///////////////////////////////////////////////////////////////////////////////////////

function DispKratko($n)// выбор товаров из данного подкатолога
{
global $SysValue,$LoadItems,$_POST,$_SESSION;
$p=$SysValue['nav']['page'];
if(@!$p) $p=1;
$n=TotalClean($n,1);

if($p!="ALL")
$p=TotalClean($p,1);
$sql=PageDisp($n);

$result=mysql_query($sql);
@$SysValue['sql']['num']++;
@$num_rows=mysql_num_rows($result);


$i=0;

// Если не задано кол-во сетки
if(empty($LoadItems['Podcatalog'][$n]['num_row'])) $LoadItems['Podcatalog'][$n]['num_row']=2;


while(@$row = mysql_fetch_array(@$result))
    {
    $id=$row['id'];
	$uid=$row['uid'];
    $name=stripslashes($row['name']);
	$category=$n;
	$description=stripslashes($row['description']);


// Пустая картинка
if(empty($LoadItems['Product'][$id]['pic_small']))
$LoadItems['Product'][$id]['pic_small']="images/shop/no_photo.gif";

// Определяем переменые
$SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
$SysValue['other']['productName']= $name;
$SysValue['other']['productArt']= $uid;


// Описание
$SysValue['other']['productDes']= mySubstr($description,300);

$SysValue['other']['productValutaName']= GetValuta();
$SysValue['other']['productImg']= $LoadItems['Product'][$id]['pic_small'];

// Показывать состояние склада
if($admoption['sklad_enabled'] == 1)
$SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".  $LoadItems['Pro'][$id]['sklad']." ".$SysValue['lang']['product_on_sklad_i'];
 else $SysValue['other']['productSklad']="";

// Преобразование ссылок
//$SysValue['other']['productNameLat']=NameToLatin($LoadItems['Product'][$id]['name']);



if($LoadItems['Product'][$id]['sklad']==0){// Если товар на складе

// Коменты
$SysValue['other']['Notice']="";
$SysValue['other']['ComStartCart']="";
$SysValue['other']['ComEndCart']="";
$SysValue['other']['ComStartNotice']="<!--";
$SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
if(empty($LoadItems['Product'][$id]['priceNew'])){
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$id]['price']);
$SysValue['other']['productPriceRub']= "";
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$id]['price']);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($LoadItems['Product'][$id]['priceNew'])." ".GetValuta()."</strike>";
}}else{ // Товар под заказ
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$id]['price']);
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['ComStartNotice']="";
$SysValue['other']['ComEndNotice']="";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
$SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
}

$SysValue['other']['productId']= $LoadItems['Product'][$id]['category'];
$SysValue['other']['productPageThis']=$p;
$SysValue['other']['productUid']= $id;

// Подтипы
if($row['parent']!=""){
$SysValue['other']['ComStart']="<!--";
$SysValue['other']['ComEnd']="-->";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
}

// Поддержка Pro
$SysValue['other']['productPrice']=GetPriceValuta(ReturnTruePriceUser($uid,$LoadItems['Product'][$id]['price']));

// Подключаем шаблон взависисмости от сетки
@$dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_1']);



// Сетка 1*1
 $td="<tr><TD class=setka colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
 $td.="<tr><td valign=\"top\">"; @$j++; $td2="</td>";

 @$disp.=$td.$dis;


}

// Определяем переменые
@$SysValue['other']['catalog']= $SysValue['lang']['catalog'];
@$cat=$LoadItems['Podcatalog'][$n]['parent_to'];

if($cat == 0){
@$SysValue['other']['catalogCat']= $SysValue['lang']['catalog'];
@$SysValue['other']['catalogId']="00";
}
else{
@$SysValue['other']['catalogCat']= $LoadItems['Catalog'][$cat]['name'];
@$SysValue['other']['catalogId']= $cat;
}

@$SysValue['other']['catalogCategory']=$LoadItems['Podcatalog'][$n]['name'];
@$SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
@$SysValue['other']['productPodcat']=$category;
@$SysValue['other']['pcatalogId']= $LoadItems['Podcatalog'][$category]['id'];

@$SysValue['other']['productPodcatId']=$LoadItems['Podcatalog'][$category]['parent_to'];


// Навигация подкаталогов
$podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
	  foreach($podcatalog_id as $key){
	  if($key == $n)
	    @$disL.="<a href=\"/shop/CID_$key.html\" class=\"activ_catalog\">".$LoadItems['Catalog'][$key]['name']."</a> | ";
	  else 
	  @$disL.="<a href=\"/shop/CID_$key.html\" title=\"".$LoadItems['Catalog'][$key]['name']."\">".$LoadItems['Catalog'][$key]['name']."</a> | ";
	  }
if(count($podcatalog_id)) $disp_list="<h1>".$SysValue['other']['catalogCat']."</h1>$disL";


$SysValue['other']['DispCatNav'] = $disp_list;

if($LoadItems['Podcatalog'][$n]['num_cow']>0)
$SysValue['other']['productNumRow']=$LoadItems['Podcatalog'][$n]['num_cow'];
else $SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];


@$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
@$SysValue['other']['productPageNav']=DispNav();


if($num_rows>0) @$SysValue['other']['productPageDis']=@$disp;
else @$SysValue['other']['productPageDis']=
"<DIV style=\"padding:10px\"><h2>Товаров выбранного типа сегодня нет в продаже</h2></DIV>";

// Подключаем шаблон
if(!empty($LoadItems['Podcatalog'][$n]['name']))
@$disp=ParseTemplateReturn($SysValue['templates']['product_page_list']);
else
@$disp=404;

return @$disp;
}


function ReturnCatalogData($n){
global $SysValue;
$n=TotalClean($n,1);
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
@$SysValue['sql']['num']++;
return $array;
}

function ReturnProductData($n){
global $SysValue,$LoadItems,$_SESSION;

$sql="select * from ".$SysValue['base']['table_name2']." where id=$n";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$name=stripslashes($row['name']);
$id=$row['id'];
$uid=$row['uid'];
$category=$row['category'];
$description=stripslashes($row['description']);
$price=$row['price'];
$sklad=$row['sklad'];
$priceNew=$row['price_n'];
$price=($price+(($price*$LoadItems['System']['percent'])/100));
$pic_small=$row['pic_small'];


// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$pole];
	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
	   }
	}


    // Если есть новая цена
	if($priceNew>0){
	$priceNew=($priceNew+(($priceNew*$LoadItems['System']['percent'])/100));
	$priceNew=number_format($priceNew,"2",".","");
	//$priceNew=$priceNew." ".$System['dengi'];
	}
	
	// Проверка на нулевую цену
	if(!is_numeric($row['price']))
	$sklad = 1;
$array=array(
"category"=>$category,
"id"=>$id,
"uid"=>$uid,
"description"=>$description,
"price"=>$price,
"priceNew"=>$priceNew,
"priceSklad"=>$sklad,
"name"=>$name,
"pic_small"=>$pic_small
);
@$SysValue['sql']['num']++;

if($id>=1) return $array;
 else return "false";
}



function DispParent($id)// выбор товаров подтипов
{
global $SysValue,$LoadItems;

// Собираем массив товаров
foreach($LoadItems['Product'][$id]['parent'] as $value){
$Product[$value]=ReturnProductData($value);
}


	foreach($Product as $val=>$p){
		   // Определяем переменые
		   //$SysValue['other']['productName']= $p['name'];
		   $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
$SysValue['other']['productValutaName']= GetValuta();
		   if($Product[$val]['priceSklad']==0){// Если товар на складе
		  $SysValue['other']['productPrice']=GetPriceValuta($Product[$val]['price']);

}

           $SysValue['other']['productUid']= $val;
	       @$disp.="<option value=".$val." >".$p['name']." (".$SysValue['other']['productPrice']." ".$SysValue['other']['productValutaName'].")</option>";
		   }
    @$dis="<select name=\"parentId\" id=\"parentId\">".$disp."</select>";
	$SysValue['other']['parentList']=@$dis;
	
	$dis=ParseTemplateReturn("product/product_odnotip_product_parent.tpl");
return @$dis;
}


///////////////////////////////////////////////////////////////////////////////////////

// Вывод подробной инфы о товаре 
// убран кеш товаров
function DispPodrobno($n)
{
global $SysValue,$LoadItems,$cat,$p,$v,$_SESSION;
$n=TotalClean($n,1);
$cat=TotalClean($cat,1);
$i=0;
$sql="select * from ".$SysValue['base']['table_name2']." where id=$n and enabled='1'";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
$row = mysql_fetch_array($result);
@$SysValue['sql']['num']++;
    $id=$row['id'];
	$uid=$row['uid'];
    $name=stripslashes($row['name']);
	$category=$row['category'];
	$content=stripslashes($row['content']);
	$price=$row['price'];
    $sklad=$row['sklad'];
	$priceNew=$row['price_n'];
	$price=($price+(($price*$LoadItems['System']['percent'])/100));
	$pic_big=$row['pic_small'];
	$items=$row['items'];

	
	// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$pole];
	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
	   }
	}
	
	// Если есть новая цена
	if($priceNew>0){
	$priceNew=($priceNew+(($priceNew*$LoadItems['System']['percent'])/100));
	$priceNew=number_format($priceNew,"2",".","");
	}
	
	// Проверка на нулевую цену
	if(!is_numeric($row['price']))
	$sklad = 1;
	$uid=$row['uid'];
	$parent=explode(",",$row['parent']);
	$vendor=$row['vendor'];
	
	

	

// Показывать состояние склада
if($admoption['sklad_enabled'] == 1)
$SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".$items." ".$SysValue['lang']['product_on_sklad_i'];
 else $SysValue['other']['productSklad']="";


// Пустая картинка
if(empty($pic_big))
$pic_big="images/shop/no_photo.gif";


	// Определяем переменые
$SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productBack']= $SysValue['lang']['product_back'];
$SysValue['other']['productArt']= $uid;
$SysValue['other']['productDes']= strip_tags($content,"<br>");
$SysValue['other']['productValutaName']= GetValuta();
$SysValue['other']['productImg']= $pic_big;
$SysValue['other']['productName']= $name;


if($sklad==0){// Если товар на складе

// Коменты
$SysValue['other']['Notice']="";
$SysValue['other']['ComStartCart']="";
$SysValue['other']['ComEndCart']="";
$SysValue['other']['ComStartNotice']="<!--";
$SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
if(empty($priceNew)){
$SysValue['other']['productPrice']=GetPriceValuta($price);
$SysValue['other']['productPriceRub']= "";
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($price);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($priceNew)." ".GetValuta()."</strike>";
}}else{ // Товар под заказ
$SysValue['other']['productPrice']=GetPriceValuta($price);
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['productValutaName']=GetValuta();
$SysValue['other']['ComStartNotice']="";
$SysValue['other']['ComEndNotice']="";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
$SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
}



@$SysValue['other']['productId']= $id;
@$SysValue['other']['productUid']= $id;
@$SysValue['other']['productCat']= $LoadItems['Catalog'][$category]['parent_to'];


// Подтипы
if($row['parent']!=""){
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndVart']="-->";

}else{
$SysValue['other']['ComStart']="";
$SysValue['other']['ComEnd']="";
}



// Подтипы
if($row['parent']!=""){
// Определяем переменые
$SysValue['other']['productParentList']= DispParent($id);
$SysValue['other']['productPrice']="";
$SysValue['other']['productPriceRub']="";
$SysValue['other']['productValutaName']="";
}



// Подключаем шаблон
@$dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_full']);


// Определяем переменые
$SysValue['other']['catalog']= $SysValue['lang']['catalog'];
$SysValue['other']['odnotipDisp']= @$odnotipDisp;
$SysValue['other']['pagetemaDisp']=@$pagetemaDisp;
@$cat=$LoadItems['Catalog'][$category]['parent_to'];
@$SysValue['other']['catalogCat']= $LoadItems['Catalog'][$cat]['name'];
@$SysValue['other']['catalogId']= $LoadItems['Catalog'][$cat]['id'];
@$SysValue['other']['catalogUId']= $cat;
@$SysValue['other']['pcatalogId']= $LoadItems['Catalog'][$category]['id'];
@$SysValue['other']['catalogCategory']=$LoadItems['Catalog'][$category]['name'];
$SysValue['other']['productPageDis']=$dis;
$SysValue['other']['productPageNum']=$p;
$SysValue['other']['productPodcat']=$category;
$SysValue['other']['productName']= $name;



// Подключаем шаблон
if(@$name)
@$disp=ParseTemplateReturn($SysValue['templates']['product_page_full']);
else
@$disp=404;
return @$disp;
}









?>
