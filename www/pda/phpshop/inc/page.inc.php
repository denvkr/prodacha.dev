<?
/*
+-------------------------------------+
|  PHPShop 2.1 Enterprise             |
|  Модуль вывода статей               |
+-------------------------------------+
*/

function DispContentPage($name){
global $SysValue,$LoadItems;
$name=TotalClean($name,2);

// Страницы только для аторизованных
if(isset($_SESSION['UsersId'])) $sort="  ";
 else $sort=" and secure !='1' ";
$sql="select * from ".$SysValue['base']['table_name11']." where link='$name' and enabled='1'  $sort order by num";


$result=mysql_query($sql);
@$row=mysql_fetch_array($result);
@$SysValue['sql']['num']++;
$id=$row['id'];
$names=$row['name'];
$content=stripslashes($row['content']);
$link=$row['link'];
$category=$row['category'];
$odnotip=$row['odnotip'];


// Страницы
$Content=explode("<HR>",$content);
if(is_array($Content)){
$_Content=array("");
foreach($Content as $val)
$_Content[]=$val;
$p=$SysValue['nav']['id'];
if(empty($p)) $p=1;
$content=$_Content[$p];
$num=count($Content);

if($p=="ALL"){
$content=$row['content'];
$productSortD="sortActiv";
}

$i=1;
while ($i<$num+1)
    {
	if($i!=$p){
    @$navigat.="
	     <a href=\"/page/".$link."_".$i.".html\">".$i."</a> | ";
	}
	else{
	
	 @$navigat.="
	     <b>".$i."</b> | ";
	}
	$i++;
	}
}

// Навигация
if(count($Content)>1){
if($p>=$num){$p_to=$i-1;}else{$p_to=$p+1;}
$nava=$SysValue['lang']['page_now'].":
<a href=\"/page/".$link."_".($p-1).".html\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat<a href=\"/page/".$link."_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"/page/".$link."_ALL.html\" class=\"$productSortD\">Все содержимое</a>
		";
}

// Определяем переменые
$SysValue['other']['pageTitle']= $names;
$SysValue['other']['pageContent']= $content;
$SysValue['other']['pageNav']=@$nava;
$SysValue['other']['catalogId']=@$LoadItems['CatalogPage'][$category]['id'];
$SysValue['other']['catalogCategory']=@$LoadItems['CatalogPage'][$category]['name'];
$parent_to=@$LoadItems['CatalogPage'][$category]['parent_to'];
$SysValue['other']['catalogCat']=@$LoadItems['CatalogPage'][$parent_to]['name'];
$SysValue['other']['thisCat'] = $parent_to;
$SysValue['other']['parentId']= $LoadItems['CatalogPage'][$parent_to]['parent_to'];


// Разделитель
if($parent_to=="") {
$SysValue['other']['catalogCat']= $SysValue['lang']['catalog'];
$SysValue['other']['parentId']= "00";
}


// Однотипы
if($row['odnotip']!=""){
// Определяем переменые
$SysValue['other']['specMainTitle'] =$SysValue['lang']['page_product'];
$SysValue['other']['specMainIcon']= DispOdnotipForPage($odnotip);
$SysValue['other']['productOdnotip']= $SysValue['lang']['page_product'];


// Подключаем шаблон
$odnotipDisp=ParseTemplateReturn($SysValue['templates']['main_product_odnotip_list']);
}

// Подключаем шаблон
$SysValue['other']['odnotipDisp']= @$odnotipDisp;
if($link!="")
$dis=ParseTemplateReturn($SysValue['templates']['page_page_list']);
else $dis=404;
return $dis;
}

function DispOdnotipForPage($odnotip)// выбор товаров однотипов
{
global $SysValue,$LoadItems;

$odnotip=explode(",",$odnotip);

// Собираем массив товаров
foreach($odnotip as $value){
$Product[$value]=ReturnProductData($value);
}

// Собираем массив подкаталогов
foreach($Product as $value){
$Podcatalog[$value['category']]=ReturnCatalogData($value['category']);
}

// Собираем массив каталогов
foreach($Podcatalog as $value){
$Catalog[$value['parent_to']]=ReturnCatalogData($value['parent_to']);
}


foreach($Podcatalog as $v){
    $SysValue['other']['catalogName']=$LoadItems['Catalog'][$v['parent_to']]['name'];
	$SysValue['other']['podcatalogName']=$v['name'];
	$disp="";
	foreach($Product as $val=>$p){
		   if($v['id'] == $p['category']){
		   // Определяем переменые
		   $SysValue['other']['productName']= $p['name'];
		   $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
$SysValue['other']['productValutaName']= GetValuta();
		   if($Product[$val]['priceSklad']==0){// Если товар на складе
		   
// Коменты
$SysValue['other']['Notice']="";
$SysValue['other']['ComStartCart']="";
$SysValue['other']['ComEndCart']="";
$SysValue['other']['ComStartNotice']="<!--";
$SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
if(empty($Product[$val]['priceNew'])){
$SysValue['other']['productPrice']=GetPriceValuta($Product[$val]['price']);
$SysValue['other']['productPriceRub']= $Product[$val]['price_rub'];
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($Product[$val]['price']);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($Product[$val]['priceNew'])." ".GetValuta()."</strike>";
}}else{ // Товар по заказ
$SysValue['other']['productPrice']=$SysValue['lang']['sklad_no'];
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['productValutaName']="";
$SysValue['other']['ComStartNotice']="";
$SysValue['other']['ComEndNotice']="";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
$SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
}

$SysValue['other']['productImg']= $Product[$val]['pic_small'];
//$SysValue['other']['productDes']= $Product[$val]['description'];
$SysValue['other']['productUid']= $val;
	       @$disp.=ParseTemplateReturn("product/product_odnotip_product.tpl");
		   }
		   }
	$SysValue['other']['catalogList']=@$disp;
    @$dis.=ParseTemplateReturn($SysValue['templates']['main_spec_forma_icon']);
}

return @$dis;
}

function DispListPage($n){
global $SysValue,$LoadItems;
$n=TotalClean($n,1);

// Страницы только для аторизованных
if(isset($_SESSION['UsersId'])) $sort="  ";
 else $sort=" and secure !='1' ";
$sql="select name, link  from ".$SysValue['base']['table_name11']." where category=$n and enabled='1'  $sort order by num";
$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result)){

// Определяем переменые
$SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
$parent_to=$LoadItems['CatalogPage'][$n]['parent_to'];
$SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];


if($parent_to == 0) {
  $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
  $SysValue['other']['catalogId']= "00";
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  }
  else {
  $SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];
  $SysValue['other']['catalogId']= $parent_to;
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  }



 @$dis.="<li><a href=\"/page/".$row['link'].".html\" title=\"".$row['name']."\">".$row['name']."</a></li>";

}

$disp="<h1>".$LoadItems['CatalogPage'][$n]['name']."</h1><ul>$dis</ul>";

// Определяем переменые
$SysValue['other']['catalogList']=@$disp;

// Подключаем шаблон
@$dis=ParseTemplateReturn('catalog/catalog_page_info_forma.tpl');
@$SysValue['sql']['num']++;
return @$dis;
}


?>
