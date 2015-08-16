<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Файл Выдачи Даты Изменения         |
+-------------------------------------+
*/

// Дата модификации документа 
function GetLastModifiedTime(){// Изменения
global $LoadItems;
$updateU = $LoadItems['System']['updateU'];
$updateDate=gmdate("D, d M Y H:i:s",$updateU);
$array=array($updateU,$updateDate." GMT");
return $array;
}

// Дата модификации документа новости
function GetNewsDate($id){
global $SysValue;
$id=TotalClean($id,1);
if(empty($id)) $string="";
  else $string="where id=$id";

$sql="select datas from ".$SysValue['base']['table_name8']." $string LIMIT 0, 1";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$num=mysql_numrows($result);
if($num>0){
$array=explode(".",$row['datas']);
$updateU=mktime(12, 0, 0, $array[1], $array[0], $array[2]);
@$updateDate=gmdate("D, d M Y H:i:s",@$updateU);
$arrayS=array($row['datas'],$updateDate." GMT");
return $arrayS;
}
}

// Дата модификации документа подкаталога
function GetCatalogDate($productID){
global $SysValue;
$sql="select MAX(datas) as datas from ".$SysValue['base']['table_name2']." where category='$productID'";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
$array=array($row['datas'],$updateDate." GMT");
return $array;
}

// Дата модификации документа подробного описания товара
function GetProductDate($productID){
global $SysValue;
$sql="select datas from ".$SysValue['base']['table_name2']." where id=$productID";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
$array=array($row['datas'],$updateDate." GMT");
return $array;
}

// Дата модификации документа информациооной страницы
function GetPageDate($pageID){
global $SysValue;
$sql="select datas from ".$SysValue['base']['table_name11']." where link='$pageID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
$array=array($row['datas'],$updateDate." GMT");
return $array;
}


if($SysValue['nav']['nav']=="UID" or $SysValue['nav']['nav']=="id")
$lastmodified=GetProductDate($SysValue['nav']['id']);

elseif($SysValue['nav']['nav']=="CID")
$lastmodified=GetCatalogDate($SysValue['nav']['id']);

elseif($SysValue['nav']['path']=="page")
$lastmodified=GetPageDate($SysValue['nav']['name']);

elseif($SysValue['nav']['path']=="news")
$lastmodified=GetNewsDate($SysValue['nav']['id']);

else $lastmodified=GetLastModifiedTime();



// Посылаем заголовки
//if($SysValue['nav']["path"]!="")
if($SysValue['cache']['last_modified'] == "true"){
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
Header("Pragma: no-cache"); // HTTP/1.1 
Header("Last-Modified: ".$lastmodified[1]);
}
?>
