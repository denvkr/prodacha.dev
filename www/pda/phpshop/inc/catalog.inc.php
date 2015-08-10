<?
/*
+-------------------------------------+
|  PHPShop 2.1                        |
|  Модуль Генерации Каталогов         |
+-------------------------------------+
*/


function DispCatalogTreeRevers($n){ // Каталог
global $LoadItems,$SysValue;

$LoadItems['Catalog'][0]['name']="Каталог";

// Описание каталога
$sql="select content from ".$SysValue['base']['table_name']." where id=$n";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);


$podcatalog_id = array_keys($LoadItems['CatalogKeys'],$n);
	  foreach($podcatalog_id as $key){
	  @$dis.="<li><a href=\"/shop/CID_$key.html\" title=\"".$LoadItems['Catalog'][$key]['name']."\">".$LoadItems['Catalog'][$key]['name']."</a>";
	  }
if(count($podcatalog_id)) $disp="<h1>".$LoadItems['Catalog'][$n]['name']."</h1><ul>$dis</ul>";
return @$disp;
}

function DispCatalogPageTreeRevers($n){ // страницы
global $LoadItems,$SysValue;

$LoadItems['Catalog'][0]['name']="Каталог";

$podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$n);
	  foreach($podcatalog_id as $key){
	  @$dis.="<li><a href=\"/page/CID_$key.html\" title=\"".$LoadItems['CatalogPage'][$key]['name']."\">".$LoadItems['CatalogPage'][$key]['name']."</a>";
	  }
if(count($podcatalog_id)) $disp="<h1>".$LoadItems['CatalogPage'][$n]['name']."</h1><ul>$dis</ul>";
return @$disp;
}

// Ввыод дерева каталога страниц
function DispCatalogPageTree($n){
global $LoadItems,$SysValue;
$n=TotalClean($n,1);

// Определяем переменые
$parent_to=$LoadItems['CatalogPage'][$n]['parent_to'];


if($parent_to == 0) {
  $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
  $SysValue['other']['catalogId']= "00";
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  }
  else {
  $SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];
  $SysValue['other']['catalogId']= $parent_to;
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  $SysValue['other']['thisCat']= $parent_to;
  }


$SysValue['other']['catalogList']=DispCatalogPageTreeRevers($n);


// Подключаем шаблон
@$dis.=ParseTemplateReturn('catalog/catalog_page_info_forma.tpl');

return @$dis;
}

// Ввыод дерева каталога
function DispCatalogTree($n){
global $LoadItems,$SysValue;
$n=TotalClean($n,1);

// Определяем переменые
$SysValue['other']['catalogContent']= $content;
$parent_to=$LoadItems['Catalog'][$n]['parent_to'];


if($parent_to == 0) {
  $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
  $SysValue['other']['catalogId']= "00";
  $SysValue['other']['catalogName']= $LoadItems['Catalog'][$n]['name'];
  $SysValue['other']['thisCat']= $n;
  }
  else {
  $SysValue['other']['parentName']= $LoadItems['Catalog'][$parent_to]['name'];
  $SysValue['other']['catalogId']= $parent_to;
  $SysValue['other']['catalogName']= $LoadItems['Catalog'][$n]['name'];
  $SysValue['other']['thisCat']= $parent_to;
  }


$SysValue['other']['catalogList']=DispCatalogTreeRevers($n);


// Подключаем шаблон
@$dis.=ParseTemplateReturn('catalog/catalog_info_forma.tpl');

return @$dis;
}


function CatalogFilter($id){
global $LoadItems;
if($LoadItems['Podcatalog'][$id]['parent_to'] == 0)
return $id;
}


function Vivod_pot($n)// вывод подкаталогов
{
global $LoadItems,$SysValue;
      $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$n);
	  foreach($podcatalog_id as $key){
	  // Определяем переменые
      $SysValue['other']['catalogId']= $n;
      $SysValue['other']['catalogUid']= $key;
      $SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$n]['name'];
      $SysValue['other']['catalogName']= $LoadItems['Catalog'][$key]['name'];
	  // Подключаем шаблон
      @$dis.=ParseTemplateReturn($SysValue['templates']['podcatalog_forma']);
	  }
return @$dis;
}

function Vivod_cats()// вывод каталогов
{
global $SysValue,$LoadItems;
$admoption=unserialize($LoadItems['System']['admoption']);

if($admoption['base_enabled'] == 1)
$sort="and servers REGEXP 'i".$admoption['base_id']."i'";

$sql="select id from ".$SysValue['base']['table_name']." where parent_to=0 ".@$sort." order by num";
$result=mysql_query($sql);

while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	
   // Определяем переменые
$SysValue['other']['catalogId']= $id;
$SysValue['other']['catalogI']= $i;
$SysValue['other']['catalogTemplates']=$SysValue['dir']['templates'].chr(47).$LoadItems['System']['skin'].chr(47);
$SysValue['other']['catalogPodcatalog']= Vivod_pot($id);
$SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$id]['name'];
$SysValue['other']['catalogName']= $LoadItems['Catalog'][$id]['name'];


$podcatalog_id = array_keys($LoadItems['CatalogKeys'],$id);

if(!count($podcatalog_id))
@$dis.=ParseTemplateReturn("catalog/catalog_forma_3.tpl");
 else{
// Подключаем шаблон
if($LoadItems['Catalog'][$id]['vid'] == 1)
@$dis.=ParseTemplateReturn("catalog/catalog_forma_2.tpl");
  else @$dis.=ParseTemplateReturn($SysValue['templates']['catalog_forma']);
 }
	 }

@$SysValue['sql']['num']++;
return @$dis;
}


function Vivod_page_pot($n)// вывод подкаталогов страниц
{
global $LoadItems,$SysValue;
      $podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$n);
	  foreach($podcatalog_id as $key){
	  // Определяем переменые
      $SysValue['other']['catalogId']= $n;
      $SysValue['other']['catalogUid']= $key;
      $SysValue['other']['catalogTitle']= $LoadItems['CatalogPage'][$n]['name'];
      $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$key]['name'];
	  // Подключаем шаблон
      @$dis.=ParseTemplateReturn($SysValue['templates']['podcatalog_page_forma']);
	  }
return @$dis;
}


function Vivod_page_cat()// вывод каталогов страниц
{
global $SysValue,$LoadItems;
$sql="select * from ".$SysValue['base']['table_name29']." where parent_to=0 order by num";
$result=mysql_query($sql);


while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	
   // Определяем переменые
$SysValue['other']['catalogId']= $id;
$SysValue['other']['catalogTemplates']=$SysValue['dir']['templates'].chr(47).$LoadItems['System']['skin'].chr(47);
$SysValue['other']['catalogPodcatalog']= Vivod_page_pot($id);
$SysValue['other']['catalogTitle']= $LoadItems['CatalogPage'][$id]['name'];
$SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$id]['name'];


$podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$id);

if(!count($podcatalog_id))
@$dis.=ParseTemplateReturn("catalog/catalog_page_forma_2.tpl");
  else @$dis.=ParseTemplateReturn($SysValue['templates']['catalog_page_forma']);
 }
	 
@$SysValue['sql']['num']++;
return @$dis;
}


function Vivod_cat(){
global $SysValue;
$sql="select id from ".$SysValue['base']['table_name']." where parent_to=0";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
exit($num);
}

?>
